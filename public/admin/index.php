<?php

session_start ();

require_once __DIR__ . "/../../app/middleware/admin.php";

exigirAdmin(false);

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Administración | Pampa</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body>

    <header class="bg-dark text-white p-4">
        <div class="container">
            <h1>Panel administrativo - Pampa</h1>
        </div>
    </header>

    <main class="container my-5">

        <h2>Bienvenido al panel de administración</h2>

        <p>
            Desde acá podrás gestionar los productos,
            controlar el stock y consultar estadísticas de ventas.
        </p>


        <section class="card shadow-sm mt-4">
            <div class="card-body">

                <h3 class="card-title mb-4">Agregar producto</h3>

                <form id="formCrearProducto">

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input
                                type="text"
                                id="nombre"
                                name="nombre"
                                class="form-control"
                                required
                            >
                        </div>

                        <div class="col-md-6">
                            <label for="codigo" class="form-label">Código</label>
                            <input
                                type="text"
                                id="codigo"
                                name="codigo"
                                class="form-control"
                                required
                            >
                        </div>

                        <div class="col-md-6">
                            <label for="idCategoria" class="form-label">Categoría</label>
                            <select id="idCategoria"
                            name="idCategoria"
                            class="form-control"
                            required
                            >
                            <option value="">Seleccionar categoría</option>

                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="idProveedor" class="form-label">Proveedor</label>
                            <select
                                id="idProveedor"
                                name="idProveedor"
                                class="form-select"
                                required
                            >
                                <option value="">Seleccioná un proveedor</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label for="descripcion" class="form-label">
                                Descripción
                            </label>

                            <textarea
                                id="descripcion"
                                name="descripcion"
                                class="form-control"
                                rows="3"
                                required
                            ></textarea>
                        </div>

                        <div class="col-md-4">
                            <label for="costo" class="form-label">Costo</label>
                            <input
                                type="number"
                                id="costo"
                                name="costo"
                                class="form-control"
                                min="0"
                                step="0.01"
                                required
                            >
                        </div>

                        <div class="col-md-4">
                            <label for="precio" class="form-label">Precio</label>
                            <input
                                type="number"
                                id="precio"
                                name="precio"
                                class="form-control"
                                min="0.01"
                                step="0.01"
                                required
                            >
                        </div>

                        <div class="col-md-4">
                            <label for="stock" class="form-label">Stock</label>
                            <input
                                type="number"
                                id="stock"
                                name="stock"
                                class="form-control"
                                min="0"
                                step="1"
                                required
                            >
                        </div>

                        <div class="col-12">
                            <label for="foto" class="form-label">
                                Nombre del archivo de imagen (opcional)
                            </label>

                            <input
                                type="text"
                                id="foto"
                                name="foto"
                                class="form-control"
                                placeholder="producto.jpg"
                            >
                        </div>

                    </div>

                    <button type="submit" class="btn btn-primary mt-4">
                        Guardar producto
                    </button>

                    <div id="mensajeCrearProducto" class="mt-3" role="status"></div>

                </form>

            </div>
        </section>

        <section class="card shadow-sm mt-4">
            <div class="card-body">

                <h3 class="card-title mb-4">Administrar productos</h3>

                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">

                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Costo</th>
                                <th>Precio</th>
                                <th>Stock</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>

                        <tbody id="tablaProductos">
                        </tbody>

                    </table>
                </div>

                <p id="mensajeProductos" class="mt-3" role="status"></p>

            </div>
        </section>


        <a href="/" class="btn btn-primary mt-3">
            Volver a la tienda
        </a>

    </main>






    <script src="/js/admin.js"></script>
</body>

</html>