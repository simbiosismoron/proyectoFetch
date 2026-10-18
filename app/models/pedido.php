<?php

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../exceptions/StockInsuficienteException.php";
require_once __DIR__ . "/../exceptions/ProductoNoEncontradoException.php";

class pedido
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function obtenerItemsPorVenta(int $idVenta): array
    {
        $sql = "SELECT
                    i.idProducto,
                    p.nombre,
                    i.cantidad,
                    i.precio,
                    i.subtotal
                FROM items AS i
                LEFT JOIN productos AS p
                    ON i.idProducto = p.idProducto
                WHERE i.idVenta = :idVenta";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            "idVenta" => $idVenta
        ]);

        return $stmt->fetchAll();
    }



    public function obtenerPedidosPorUsuario(int $idUsuario): array
    {
        $sql = "SELECT idVenta, fecha, total, estadoPago, estadoEnvio
        FROM ventas
        WHERE idUsuario = :idUsuario
        ORDER BY fecha DESC, idVenta DESC";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            "idUsuario" => $idUsuario
        ]);

        return $stmt->fetchALL();
    }



    public function obtenerItemsPorVentas(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }

        $placeholders = implode(",", array_fill(0, count($ids), "?"));

        $sql = "SELECT
                    i.idVenta,
                    i.idProducto,
                    p.nombre,
                    i.cantidad,
                    i.precio,
                    i.subtotal
                FROM items AS i
                LEFT JOIN productos AS p
                    ON i.idProducto = p.idProducto
                WHERE i.idVenta IN ($placeholders)";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($ids);

        return $stmt->fetchAll();
    }



    public function realizarPedido($idUsuario, $productos)
    {
        $total = 0;
        $itemsvalidados = [];
        $idsProcesados = [];

        $this->conn->beginTransaction();
        try {


            foreach ($productos as $producto) {

                $idProducto = $producto['idProducto'];
                if (in_array($idProducto, $idsProcesados)) {
                    throw new InvalidArgumentException("Producto esta duplicado en el pedido: " . $idProducto);
                }
                $idsProcesados[] = $idProducto;

                $sql = "SELECT * FROM productos WHERE idProducto = :idProducto FOR UPDATE";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([
                    'idProducto' => $idProducto
                ]);

                $productobd = $stmt->fetch();
                if (!$productobd) {
                    throw new ProductoNoEncontradoException("Producto no encontrado: " . $idProducto);
                }

                $cantidad = (float) $producto['cantidad'];

                if ($cantidad <= 0) {
                    throw new Exception("Cantidad no válida para el producto: " . $idProducto);
                }

                $incremento = (float) $productobd["incremento"];

                if ($incremento <= 0) {
                    throw new RuntimeException("Incremento mal configurado");
                }

                $unidades = $cantidad / $incremento;
                if (abs($unidades - round($unidades)) > 0.00001) {
                    throw new InvalidArgumentException(
                        "La cantidad no respeta el incremento del producto " . $idProducto
                    );
                }

                if ($cantidad > $productobd['stock']) {
                    throw new StockInsuficienteException("Cantidad solicitada excede el stock disponible para el producto: " . $idProducto);
                }

                $precio = (float) $productobd['precio'];
                $subTotal = $cantidad * $precio;
                $total += $subTotal;
                $itemsvalidados[] = [
                    'idProducto' => $idProducto,
                    'cantidad' => $cantidad,
                    'precio' => $precio,
                    'subTotal' => $subTotal
                ];
            }

            $sql = "INSERT INTO ventas (idUsuario, fecha, total, estadoPago, estadoEnvio)
            values (:idUsuario, NOW(), :total, :estadoPago, :estadoEnvio)";

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([
                'idUsuario' => $idUsuario,
                'total' => $total,
                'estadoPago' => 'p',
                'estadoEnvio' => 'p'
            ]);

            $idventa = $this->conn->lastInsertId();

            $sqlItem = "INSERT INTO items
            (idVenta, idProducto, cantidad, precio, subtotal)
            VALUES
            (:idVenta, :idProducto, :cantidad, :precio, :subtotal)";

            $stmtItem = $this->conn->prepare($sqlItem);

            foreach ($itemsvalidados as $item) {
                $stmtItem->execute([
                    'idVenta' => $idventa,
                    'idProducto' => $item['idProducto'],
                    'cantidad' => $item['cantidad'],
                    'precio' => $item['precio'],
                    'subtotal' => $item['subTotal']
                ]);
            }

            $sqlStock = "UPDATE productos SET stock = stock - :cantidad WHERE idProducto = :idProducto";
            $stmtStock = $this->conn->prepare($sqlStock);
            foreach ($itemsvalidados as $item) {
                $stmtStock->execute([
                    'cantidad' => $item['cantidad'],
                    'idProducto' => $item['idProducto']
                ]);
            }

            $this->conn->commit();
        } catch (Throwable $e) {
            $this->conn->rollBack();
            throw $e;
        }
        return [
            "ok" => true,
            "idVenta" => $idventa,
            "total" => $total
        ];
    }
}
