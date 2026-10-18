<?php

require_once __DIR__ . "/../config/database.php";
$database = new Database();
$conn = $database->getConnection();

?>
<!DOCTYPE html>
<html lang="en">


<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <header class="d-flex justify-content-between bg-light align-items-center">
        <h1 class="display-4 text-primary">Pampa</h1>
        <nav>
            <ul class="nav nav-pills">
                <button id="btnVerMisPedidos" class="btn btn-outline-primary">
                    Mis pedidos
                </button>
                <button id="btnVerCarrito" class="btn btn-outline-primary ms-3">
                    Carrito
                </button>
                <li class="nav-item"><a class="nav-link text-decoration-none text-secondary fs-5" href="#">Inicio</a></li>
                <li class="nav-item"><a class="nav-link text-decoration-none text-secondary fs-5" href="#">Acerca de</a></li>
                <li class="nav-item"><a class="nav-link text-decoration-none text-secondary fs-5" href="#">Contacto</a></li>
            </ul>
        </nav>
    </header>

    <?php if ($conn): ?>

        <main class="container my-5 bg-light p-4 rounded">
            <p id="estadoSesion" class="text-dark fs-5"></p>
            <div class="about-us my-5">
                <h2 class="text-primary">Acerca de Nosotros</h2>
                <p class="text-dark fs-5">Bienvenido a Pampa. Somos una empresa dedicada a brindar soluciones innovadoras y de alta calidad a nuestros clientes.</p>
            </div>
            <div class="products my-5">

                <section id="vistaCatalogo">

                    <h2 class="text-primary">Productos</h2>

                    <p class="text-dark fs-5">
                        Explora nuestra gama de productos.
                    </p>

                    <div id="contenedorProductos" class="row g-5">
                    </div>

                </section>


                <section id="vistaCarrito" class="d-none">

                    <button id="btnVolverCatalogo" class="btn btn-secondary mb-4">
                        Volver al catálogo
                    </button>

                    <h2 class="mb-4">Mi carrito</h2>

                    <div class="row g-4">

                        <!-- Productos: 8 columnas -->
                        <div class="col-12 col-lg-8">

                            <h3>Productos</h3>

                            <div id="carrito" class="row g-3"></div>

                            <button id="vaciarCarrito"
                                class="btn btn-outline-danger mt-3">
                                Vaciar carrito
                            </button>

                        </div>

                        <!-- Resumen: 4 columnas -->
                        <div class="col-12 col-lg-4">

                            <div id="resumenPedido" class="border rounded p-4">

                                <h3>Resumen del pedido</h3>

                                <div id="resumenCarrito"></div>

                                <button id="btnConfirmarPedido"
                                    class="btn btn-primary w-100 mt-3">
                                    Confirmar pedido
                                </button>

                            </div>


                            <div id="mensajeCheckout" class="mt-3"></div>

                        </div>

                    </div>

                </section>

                <section id="vistaMisPedidos" class="d-none">

                    <button id="btnVolverDesdePedidos" class="btn btn-secondary mb-4">
                        Volver al catálogo
                    </button>

                    <h2 class="mb-4">Mis pedidos</h2>

                    <div id="mensajeMisPedidos"></div>

                    <div id="contenedorMisPedidos" class="row g-3">
                    </div>

                </section>



                <footer class="bg-light text-center py-4">
                    <p class="text-secondary mb-0">&copy; 2026 Pampa. Todos los derechos reservados.</p>
                </footer>













            <?php else: ?>
                <h2>Conexión a la base de datos fallida</h2>
                <p>Por favor, verifica la configuración de la base de datos.</p>
            <?php endif; ?>



            <script src="js/mis-pedidos.js"></script>
            <script src="js/productos.js"></script>
            <script src="js/pedidos.js"></script>
        </main>
</body>

</html>