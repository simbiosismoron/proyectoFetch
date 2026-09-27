<?php

require_once __DIR__ . "/../config/database.php";
$database = new Database();
$conn = $database->getConnection();

?>
<!DOCTYPE html>
<html lang="en">


<head>
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
                <li class="nav-item"><a class="nav-link text-decoration-none text-secondary fs-5" href="#">Inicio</a></li>
                <li class="nav-item"><a class="nav-link text-decoration-none text-secondary fs-5" href="#">Acerca de</a></li>
                <li class="nav-item"><a class="nav-link text-decoration-none text-secondary fs-5" href="#">Contacto</a></li>
            </ul>
        </nav>
    </header>

    <?php if ($conn): ?>

        <main class="container my-5 bg-light p-4 rounded">
            <div class="about-us my-5">
                <h2 class="text-primary">Acerca de Nosotros</h2>
                <p class="text-dark fs-5">Bienvenido a Pampa. Somos una empresa dedicada a brindar soluciones innovadoras y de alta calidad a nuestros clientes.</p>
            </div>
            <div class="products my-5">
                <h2 class="text-primary">Productos</h2>
                <p class="text-dark fs-5">Explora nuestra gama de productos innovadores diseñados para satisfacer tus necesidades.</p>
                <div class="container">
                    <div id="contenedorProductos" class="row g-4"></div>
                </div>
                <div class="carrito">
                    <h2 class="text-primary">Carrito</h2>
                    <div id="carrito" class="row g-4"></div>
                </div>
            </div>
        </main>






        <footer class="bg-light text-center py-4">
            <p class="text-secondary mb-0">&copy; 2026 Pampa. Todos los derechos reservados.</p>
        </footer>













    <?php else: ?>
        <h2>Conexión a la base de datos fallida</h2>
        <p>Por favor, verifica la configuración de la base de datos.</p>
    <?php endif; ?>

    <script src="js/productos.js">
    </script>
</body>

</html>