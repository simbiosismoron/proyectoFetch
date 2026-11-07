
<?php

session_start();

require_once __DIR__ . "/../../app/middleware/admin.php";

exigirAdmin(false);

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Administración | Pampa</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link rel="stylesheet" href="/admin/styles/panel.css">
    <link rel="stylesheet" href="/styles/theme-dark.css">
</head>

<body>

    <header class="admin-header">
        <div class="container py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                <div class="d-flex align-items-center gap-3">

                    <a href="/" class="brand">
                        pampa<span class="brand-dot">.</span>
                    </a>

                    <span class="admin-badge">
                        Administración
                    </span>

                </div>

                <a href="/" class="btn btn-header">
                    Volver al catálogo
                </a>

            </div>

        </div>
    </header>

    <main class="container py-4 py-lg-5">

        <section class="page-intro mb-5">

            <p class="section-eyebrow">
                PANEL DE CONTROL
            </p>

            <h1 class="page-title">
                Gestión de productos
            </h1>

            <p class="page-description">
                Administrá el catálogo, actualizá precios,
                controlá el stock y gestioná la disponibilidad
                de los productos de Pampa.
            </p>

        </section>

        <section class="admin-card mb-4">

            <div class="card-heading">
                <div>
                    <p class="section-eyebrow mb-2">
                        ESTADÍSTICAS
                    </p>

                    <h2 class="card-title mb-2">
                        Resumen de ventas
                    </h2>

                    <p class="card-description mb-0">
                        Indicadores generales de los pedidos registrados.
                    </p>
                </div>
            </div>

            <div class="card-content">

                <div class="row g-3">

                    <div class="col-12 col-md-6 col-xl-3">
                        <div class="border rounded-3 p-4 h-100">
                            <p class="text-muted mb-2">Pedidos registrados</p>
                            <h3 id="cantidadPedidos" class="fw-bold mb-0">—</h3>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-xl-3">
                        <div class="border rounded-3 p-4 h-100">
                            <p class="text-muted mb-2">Importe de pedidos</p>
                            <h3 id="importePedidos" class="fw-bold mb-0">—</h3>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-xl-3">
                        <div class="border rounded-3 p-4 h-100">
                            <p class="text-muted mb-2">Cantidad vendida</p>
                            <h3 id="unidadesVendidas" class="fw-bold mb-0">—</h3>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-xl-3">
                        <div class="border rounded-3 p-4 h-100">
                            <p class="text-muted mb-2">Ticket promedio</p>
                            <h3 id="ticketPromedio" class="fw-bold mb-0">—</h3>
                        </div>
                    </div>

                </div>

                <p id="mensajeEstadisticas"
                class="text-muted mt-3 mb-0"
                role="status"></p>

            </div>

        </section>

        <section class="admin-card mb-4" id="gestionCategorias">
            <div class="card-heading"><h2 class="card-title">Administrar categorías</h2><p class="card-description mb-0">Crear, modificar y eliminar registros sin productos asociados.</p></div>
            <div class="card-content">
                <form id="formCategorias" class="row g-3 mb-4">
                    <div class="col-md-9"><label class="form-label" for="Categoriasnombre">Nombre</label><input class="form-control" id="Categoriasnombre" name="nombre" maxlength="255" required></div>
                    <div class="col-12"><button class="btn btn-pampa" type="submit" id="guardarCategorias">Crear</button> <button class="btn btn-outline-secondary d-none" type="button" id="cancelarCategorias">Cancelar edición</button></div>
                </form>
                <p id="mensajeCategorias" role="status"></p>
                <div class="table-responsive"><table class="table admin-table"><thead><tr><th>Nombre</th><th>Acciones</th></tr></thead><tbody id="tablaCategorias"></tbody></table></div>
            </div>
        </section>
        <section class="admin-card mb-4" id="gestionProveedores">
            <div class="card-heading"><h2 class="card-title">Administrar proveedores</h2><p class="card-description mb-0">Crear, modificar y eliminar registros sin productos asociados.</p></div>
            <div class="card-content">
                <form id="formProveedores" class="row g-3 mb-4">
                    <div class="col-md-4"><label class="form-label" for="Proveedoresnombre">Nombre</label><input class="form-control" id="Proveedoresnombre" name="nombre" maxlength="255" required></div>
                    <div class="col-md-4"><label class="form-label" for="Proveedorescuit">CUIT (11 dígitos)</label><input class="form-control" id="Proveedorescuit" name="cuit" inputmode="numeric" pattern="[0-9]{11}" maxlength="11" required></div>
                    <div class="col-md-4"><label class="form-label" for="ProveedoresrazonSocial">Razón social</label><input class="form-control" id="ProveedoresrazonSocial" name="razonSocial" maxlength="255" required></div>
                    <div class="col-12"><button class="btn btn-pampa" type="submit" id="guardarProveedores">Crear</button> <button class="btn btn-outline-secondary d-none" type="button" id="cancelarProveedores">Cancelar edición</button></div>
                </form>
                <p id="mensajeProveedores" role="status"></p>
                <div class="table-responsive"><table class="table admin-table"><thead><tr><th>Nombre</th><th>CUIT (11 dígitos)</th><th>Razón social</th><th>Acciones</th></tr></thead><tbody id="tablaProveedores"></tbody></table></div>
            </div>
        </section>
        <section class="admin-card mb-4">

            <div class="card-heading">

                <div>
                    <p class="section-eyebrow mb-2">
                        CATÁLOGO
                    </p>

                    <h2 class="card-title mb-2">
                        Agregar producto
                    </h2>

                    <p class="card-description mb-0">
                        Completá los datos para incorporar
                        un producto al catálogo.
                    </p>
                </div>

            </div>

            <div class="card-content">

                <form id="formCrearProducto">

                    <div class="row g-4">

                        <div class="col-md-6">
                            <label for="nombre" class="form-label">
                                Nombre
                            </label>

                            <input
                                type="text"
                                id="nombre"
                                name="nombre"
                                class="form-control"
                                placeholder="Nombre del producto"
                                required
                            >
                        </div>

                        <div class="col-md-6">
                            <label for="codigo" class="form-label">
                                Código
                            </label>

                            <input
                                type="text"
                                id="codigo"
                                name="codigo"
                                class="form-control"
                                placeholder="Código único"
                                required
                            >
                        </div>

                        <div class="col-md-6">
                            <label for="idCategoria" class="form-label">
                                Categoría
                            </label>

                            <select
                                id="idCategoria"
                                name="idCategoria"
                                class="form-select"
                                required
                            >
                                <option value="">
                                    Seleccionar categoría
                                </option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="idProveedor" class="form-label">
                                Proveedor
                            </label>

                            <select
                                id="idProveedor"
                                name="idProveedor"
                                class="form-select"
                                required
                            >
                                <option value="">
                                    Seleccioná un proveedor
                                </option>
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
                                placeholder="Describí las características del producto"
                                required
                            ></textarea>
                        </div>

                        <div class="col-md-4">
                            <label for="costo" class="form-label">
                                Costo
                            </label>

                            <div class="input-group">
                                <span class="input-group-text">$</span>

                                <input
                                    type="number"
                                    id="costo"
                                    name="costo"
                                    class="form-control"
                                    min="0"
                                    step="0.01"
                                    placeholder="0.00"
                                    required
                                >
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="precio" class="form-label">
                                Precio de venta
                            </label>

                            <div class="input-group">
                                <span class="input-group-text">$</span>

                                <input
                                    type="number"
                                    id="precio"
                                    name="precio"
                                    class="form-control"
                                    min="0.01"
                                    step="0.01"
                                    placeholder="0.00"
                                    required
                                >
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="stock" class="form-label">
                                Stock disponible
                            </label>

                            <input
                                type="number"
                                id="stock"
                                name="stock"
                                class="form-control"
                                min="0"
                                step="1"
                                placeholder="0"
                                required
                            >
                        </div>

                        <div class="col-12">
                            <label for="foto" class="form-label">
                                Imagen del producto
                            </label>

                            <input
                                type="text"
                                id="foto"
                                name="foto"
                                class="form-control"
                                placeholder="producto.jpg"
                            >

                            <p class="field-help mb-0 mt-2">
                                Ingresá el nombre del archivo de imagen.
                                Este campo es opcional.
                            </p>
                        </div>

                    </div>

                    <div class="form-actions mt-4">

                        <button
                            id="btnGuardarProducto"
                            type="submit"
                            class="btn btn-pampa"
                        >
                            Guardar producto
                        </button>

                        <button
                            type="button"
                            id="btnCancelarEdicion"
                            class="btn btn-outline-secondary d-none"
                        >
                            Cancelar edición
                        </button>

                    </div>

                    <div
                        id="mensajeCrearProducto"
                        class="mt-3"
                        role="status"
                    ></div>

                </form>

            </div>

        </section>

        <section class="admin-card">

            <div class="card-heading">

                <div>
                    <p class="section-eyebrow mb-2">
                        INVENTARIO
                    </p>

                    <h2 class="card-title mb-2">
                        Administrar productos
                    </h2>

                    <p class="card-description mb-0">
                        Consultá, editá, desactivá o reactivá
                        los productos registrados.
                    </p>
                </div>

            </div>

            <div class="card-content">

                <div class="table-responsive">

                    <table class="table admin-table align-middle">

                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Categoría</th>
                                <th>Proveedor</th>
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

                <p
                    id="mensajeProductos"
                    class="mt-3 mb-0"
                    role="status"
                ></p>

            </div>

        </section>

    </main>

    <footer class="admin-footer">

        <div class="container py-4 d-flex flex-wrap justify-content-between align-items-center gap-3">

            <span class="footer-brand">
                pampa<span class="brand-dot">.</span>
            </span>

            <p class="mb-0">
                &copy; 2026 Pampa · Panel administrativo
            </p>

        </div>

    </footer>

    <script src="/js/admin.js"></script>
    <script src="/js/admin-entidades.js"></script>

</body>
</html>
