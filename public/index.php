
<?php

require_once __DIR__ . "/../config/database.php";

$database = new Database();
$conn = $database->getConnection();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>
<!DOCTYPE html>
<html lang="es">



<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pampa | Distribuidora</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="/styles/index.css">
</head>

<body>

    <header class="site-header xdd">
        <div class="container py-3">

            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">

                <a href="/" class="brand">
                    pampa<span class="brand-dot">.</span>
                </a>

                <nav class="header-actions" aria-label="Navegación principal">

                    <a href="/" class="nav-link-custom me-2">
                        Inicio
                    </a>

                    <?php if (($_SESSION["rol"] ?? null) === "admin"): ?>
                        <a href="/admin/index.php" class="nav-link-custom me-2">
                            Administración
                        </a>
                    <?php endif; ?>

                    <button
                        type="button"
                        id="btnVerMisPedidos"
                        class="btn btn-pampa-outline">
                        Mis pedidos
                    </button>

                    <button
                        type="button"
                        id="btnVerCarrito"
                        class="btn btn-pampa">
                        Mi carrito
                    </button>

                </nav>

            </div>

        </div>
    </header>

    <?php if ($conn): ?>

        <main class="container py-4 py-lg-5">

            <div id="estadoSesion" class="mb-4"></div>

            <section class="hero mb-5" aria-labelledby="tituloPrincipal">

                <div class="hero-eyebrow">
                    Distribuidora Pampa
                </div>

                <h1 id="tituloPrincipal" class="hero-title">
                    Insumos y equipamiento para tu producción
                </h1>

                <p class="hero-description">
                    Encontrá condimentos, tripas, integrales, maquinaria
                    y repuestos para tu negocio. Calidad y soluciones
                    para acompañar tu producción.
                </p>

            </section>

            <section id="vistaCatalogo">

                <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">

                    <div>
                        <h2 class="section-title mb-2">
                            Nuestro catálogo
                        </h2>

                        <p class="section-description mb-0">
                            Explorá nuestros productos y encontrá lo que necesitás.
                        </p>
                    </div>

                </div>

                <div class="content-panel mb-4">

                    <label for="buscadorProductos" class="search-label">
                        Buscar productos
                    </label>

                    <input
                        type="search"
                        id="buscadorProductos"
                        class="form-control search-input"
                        placeholder="Buscar por nombre..."
                        autocomplete="off">

                    <label for="filtroCategoria" class="search-label mt-3">
                        Filtrar por categoría
                    </label>
                    <select id="filtroCategoria" class="form-select">
                        <option value="">Todas las categorías</option>
                    </select>

                </div>

                <div id="contenedorProductos" class="row g-4"></div>

            </section>

            <section id="vistaCarrito" class="d-none mx-auto" style="max-width: 1100px;">

                <button
                    type="button"
                    id="btnVolverCatalogo"
                    class="btn btn-pampa-outline back-button mb-4">
                    ← Volver al catálogo
                </button>

                <h2 class="section-title mb-2">
                    Mi carrito
                </h2>

                <p class="section-description mb-4">
                    Revisá los productos seleccionados antes de confirmar tu pedido.
                </p>

                <div id="filaCarrito" class="row g-4">

                    <div id="columnaProductosCarrito" class="col-12 col-lg-8">

                        <div class="content-panel h-100">

                            <h3 class="fs-5 fw-bold mb-4">
                                Productos seleccionados
                            </h3>

                            <div id="carrito" class="row g-3"></div>

                            <button
                                type="button"
                                id="vaciarCarrito"
                                class="btn btn-outline-danger mt-4">
                                Vaciar carrito
                            </button>

                        </div>

                    </div>

                    <div id="columnaResumenCarrito" class="col-12 col-lg-4">

                        <div id="resumenPedido"class="order-summary">

                            <h3>Resumen del pedido</h3>

                            <div id="resumenCarrito"></div>

                            <button
                                type="button"
                                id="btnConfirmarPedido"
                                class="btn btn-pampa w-100 mt-4">
                                Confirmar pedido
                            </button>

                        </div>

                        <div id="mensajeCheckout" class="mt-3"></div>

                    </div>

                </div>

            </section>

            <section id="vistaMisPedidos" class="d-none">

                <button
                    type="button"
                    id="btnVolverDesdePedidos"
                    class="btn btn-pampa-outline back-button mb-4">
                    ← Volver al catálogo
                </button>

                <h2 class="section-title mb-2">
                    Mis pedidos
                </h2>

                <p class="section-description mb-4">
                    Consultá el historial de tus compras.
                </p>

                <div class="content-panel">

                    <div id="mensajeMisPedidos"></div>

                    <div id="contenedorMisPedidos" class="row g-3"></div>

                </div>

            </section>

        </main>

    <?php else: ?>

        <main class="container py-5">

            <div class="alert alert-danger">
                <h2 class="h5 fw-bold">
                    Error de conexión
                </h2>

                <p class="mb-0">
                    No fue posible conectar con la base de datos.
                    Verificá la configuración del servidor.
                </p>
            </div>

        </main>

    <?php endif; ?>

    <footer class="site-footer py-4">

        <div class="container d-flex flex-wrap justify-content-between align-items-center gap-3">

            <span class="footer-brand">
                pampa.
            </span>

            <p class="text-secondary small mb-0">
                &copy; 2026 Pampa. Todos los derechos reservados.
            </p>

        </div>

    </footer>

    <script src="/js/mis-pedidos.js"></script>
    <script src="/js/productos.js"></script>
    <script src="/js/pedidos.js"></script>

</body>
</html>
