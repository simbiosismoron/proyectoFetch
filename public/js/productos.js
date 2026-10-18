// ==========================================
// 1. ESTADO DEL CARRITO
// ==========================================

const carritoGuardado = localStorage.getItem("carrito");

let carrito = carritoGuardado
    ? JSON.parse(carritoGuardado)
    : [];


// ==========================================
// 2. OBTENER PRODUCTOS DESDE LA API
// ==========================================

async function obtenerProductos() {
    try {
        const respuesta = await fetch("../api/productos.php");

        if (!respuesta.ok) {
            throw new Error("No se pudieron obtener los productos");
        }

        const productos = await respuesta.json();

        mostrarProductos(productos);

    } catch (error) {
        console.error("Error al obtener los productos:", error);
    }
}


// ==========================================
// 3. MOSTRAR PRODUCTOS EN EL CATÁLOGO
// ==========================================

function mostrarProductos(productos) {

    const contenedor = document.getElementById("contenedorProductos");

    contenedor.innerHTML = "";

    productos.forEach(producto => {

        const botonProducto = Number(producto.stock) > 0
            ? `
                <button
                    class="btn btn-primary btn-agregar"
                    data-id="${producto.idProducto}">
                    Agregar al carrito
                </button>
            `
            : `
                <button
                    class="btn btn-secondary"
                    disabled>
                    Sin stock
                </button>
            `;

        const columna = document.createElement("div");

        columna.classList.add(
            "col-12",
            "col-sm-6",
            "col-lg-4",
            "col-xl-3"
        );

        const productoElemento = document.createElement("div");

        productoElemento.classList.add(
            "producto",
            "h-100"
        );

        productoElemento.style.setProperty(
            "--imagen-producto",
            `url("img/productos/${producto.foto}")`
        );

        productoElemento.innerHTML = `
            <h3 class="text-white">
                ${producto.nombre}
            </h3>

            <p class="text-white fs-5">
                ${producto.descripcion}
            </p>

            <p class="text-white fs-5">
                Precio: $${producto.precio} / ${producto.unidadMedida}
            </p>

            ${botonProducto}
        `;

        columna.appendChild(productoElemento);
        contenedor.appendChild(columna);

        // Evento del botón Agregar al carrito
        const botonAgregar = productoElemento.querySelector(".btn-agregar");

        if (botonAgregar) {

            botonAgregar.addEventListener("click", () => {

                const idProducto = Number(botonAgregar.dataset.id);

                const productoSeleccionado = productos.find(
                    p => Number(p.idProducto) === idProducto
                );

                const productoEnCarrito = carrito.find(
                    p => Number(p.idProducto) === idProducto
                );

                if (productoEnCarrito) {

                    aumentarCantidad(idProducto);

                } else {

                    const cantidadInicial = Number(productoSeleccionado.incremento);
                    const stock = Number(productoSeleccionado.stock);

                    if (cantidadInicial <= 0 || cantidadInicial > stock) {
                        alert("Stock insuficiente o incremento inválido");
                        return;
                    }

                    carrito.push({
                        ...productoSeleccionado,
                        cantidad: cantidadInicial
                    });

                    guardarJSON();
                    mostrarCarrito();
                }

            });

        }

    });

}


// ==========================================
// 4. GUARDAR CARRITO EN LOCALSTORAGE
// ==========================================

function guardarJSON() {

    const carritoJSON = JSON.stringify(carrito);

    localStorage.setItem("carrito", carritoJSON);

}


// ==========================================
// 5. AUMENTAR CANTIDAD
// ==========================================

function aumentarCantidad(idProducto) {

    const producto = carrito.find(
        p => Number(p.idProducto) === Number(idProducto)
    );

    if (!producto) {
        return;
    }

    const incremento = Number(producto.incremento);
    const cantidadActual = Number(producto.cantidad);
    const stock = Number(producto.stock);

    if (incremento <= 0 || !Number.isFinite(incremento)) {
        alert("Incremento inválido");
        return;
    }

    const nuevaCantidad = Number(
        (cantidadActual + incremento).toFixed(3)
    );

    if (nuevaCantidad > stock) {
        alert("Cantidad máxima de stock alcanzada");
        return;
    }

    producto.cantidad = nuevaCantidad;

    guardarJSON();
    mostrarCarrito();

}


// ==========================================
// 6. DISMINUIR CANTIDAD
// ==========================================

function disminuirCantidad(idProducto) {

    const producto = carrito.find(
        p => Number(p.idProducto) === Number(idProducto)
    );

    if (!producto) {
        return;
    }

    const incremento = Number(producto.incremento);
    const cantidadActual = Number(producto.cantidad);

    if (incremento <= 0 || !Number.isFinite(incremento)) {
        return;
    }

    const nuevaCantidad = Number(
        (cantidadActual - incremento).toFixed(3)
    );

    if (nuevaCantidad > 0) {

        producto.cantidad = nuevaCantidad;

    } else {

        carrito = carrito.filter(
            p => Number(p.idProducto) !== Number(idProducto)
        );

    }

    guardarJSON();
    mostrarCarrito();

}


// ==========================================
// 7. CALCULAR TOTAL
// ==========================================

function calcularTotal() {

    return carrito.reduce((total, producto) => {

        return total + Number(producto.precio) * Number(producto.cantidad);

    }, 0);

}


// ==========================================
// 8. MOSTRAR RESUMEN DEL PEDIDO
// ==========================================

function mostrarResumen() {

    const contenedor = document.getElementById("resumenCarrito");
    const resumenPedido = document.getElementById("resumenPedido");

    if (carrito.length === 0) {

        resumenPedido.classList.add("d-none");
        return;

    }

    resumenPedido.classList.remove("d-none");

    const total = calcularTotal();

    contenedor.innerHTML = `
        <div class="d-flex justify-content-between mt-3">
            <span>Total:</span>
            <strong>$${total.toFixed(2)}</strong>
        </div>
    `;

}


// ==========================================
// 9. MOSTRAR CARRITO
// ==========================================

function mostrarCarrito() {

    const contenedorCarrito = document.getElementById("carrito");

    contenedorCarrito.innerHTML = "";

    mostrarResumen();

    // Carrito vacío
    if (carrito.length === 0) {

        contenedorCarrito.innerHTML = `
            <div class="col-12 text-center py-5">
                <h3>Tu carrito está vacío</h3>

                <p class="text-secondary">
                    Todavía no agregaste productos.
                </p>

                <button id="btnExplorar" class="btn btn-primary">
                    Explorar productos
                </button>
            </div>
        `;

        document.getElementById("btnExplorar")
            .addEventListener("click", () => {

                vistaCarrito.classList.add("d-none");
                vistaCatalogo.classList.remove("d-none");

                if (aboutUs) {
                    aboutUs.classList.remove("d-none");
                }

            });

        return;
    }

    // Mostrar cada producto del carrito
    carrito.forEach(producto => {

        const productoCarrito = document.createElement("div");

        productoCarrito.classList.add(
            "col-12",
            "elementosCarrito"
        );

        const subtotal = Number(producto.precio) * Number(producto.cantidad);

        productoCarrito.innerHTML = `
            <div class="border rounded-3 bg-white p-3 h-100">

                <div class="d-flex flex-column flex-md-row justify-content-between gap-3">

                    <!-- Información del producto -->
                    <div class="flex-grow-1">

                        <h5 class="fw-semibold mb-2">
                            ${producto.nombre}
                        </h5>

                        <p class="text-secondary small mb-2">
                            ${producto.descripcion}
                        </p>

                        <p class="text-secondary small mb-1">
                            Disponible: ${producto.stock} ${producto.unidadMedida}
                        </p>

                        <p class="mb-0">
                            $${producto.precio} / ${producto.unidadMedida}
                        </p>

                    </div>

                    <!-- Cantidad y subtotal -->
                    <div class="d-flex flex-column align-items-md-end gap-3">

                        <div class="d-flex align-items-center gap-2">

                            <button
                                class="btn btn-outline-secondary btn-restar"
                                data-id="${producto.idProducto}">
                                −
                            </button>

                            <span class="text-nowrap">
                                ${producto.cantidad} ${producto.unidadMedida}
                            </span>

                            <button
                                class="btn btn-outline-secondary btn-aumentar"
                                data-id="${producto.idProducto}">
                                +
                            </button>

                        </div>

                        <strong class="fs-5">
                            $${subtotal.toFixed(2)}
                        </strong>

                        <button class="btn btn-outline-danger btn-eliminar" data-id="${producto.idProducto}">
                            Eliminar
                        </button>

                    </div>

                </div>

            </div>
        `;

        contenedorCarrito.appendChild(productoCarrito);

    });

    // Eventos para aumentar cantidades
    const botonesSumar = contenedorCarrito.querySelectorAll(".btn-aumentar");

    botonesSumar.forEach(boton => {

        boton.addEventListener("click", () => {

            const idProducto = Number(boton.dataset.id);

            aumentarCantidad(idProducto);

        });

    });

    // Eventos para disminuir cantidades
    const botonesRestar = contenedorCarrito.querySelectorAll(".btn-restar");

    botonesRestar.forEach(boton => {

        boton.addEventListener("click", () => {

            const idProducto = Number(boton.dataset.id);

            disminuirCantidad(idProducto);

        });

    });

        const botonesEliminar = contenedorCarrito.querySelectorAll(".btn-eliminar");

        botonesEliminar.forEach(boton => {

        boton.addEventListener("click", () => {

            const idProducto = Number(boton.dataset.id);

            carrito = carrito.filter(
                producto => Number(producto.idProducto) !== idProducto
            );

            guardarJSON();
            mostrarCarrito();

        });

    });

}


// ==========================================
// 10. VACIAR CARRITO
// ==========================================

const botonVaciar = document.getElementById("vaciarCarrito");

botonVaciar.addEventListener("click", () => {

    carrito = [];

    guardarJSON();
    mostrarCarrito();

});


// ==========================================
// 11. VERIFICAR SESIÓN
// ==========================================

async function verificarSesion() {

    try {

        const respuesta = await fetch("../api/sesion.php");

        const resultado = await respuesta.json();

        const contenedor = document.getElementById("estadoSesion");

        if (resultado.autenticado) {

            contenedor.innerHTML = `
                <span id="nombreUsuario"></span>

                <button id="btnLogout" class="btn btn-outline-danger ms-3">
                    Cerrar sesión
                </button>
            `;

            document.getElementById("nombreUsuario").textContent =
                `Bienvenido, ${resultado.nombre}`;

            document.getElementById("btnLogout")
                .addEventListener("click", cerrarSesion);

        } else {

            contenedor.innerHTML = `
                <a href="login.html" class="btn btn-primary">
                    Iniciar sesión
                </a>

                <a href="registro.html" class="btn btn-outline-secondary ms-2">
                    Registrarse
                </a>
            `;

        }

    } catch (error) {

        console.error("Error al verificar la sesión:", error);

    }

}


// ==========================================
// 12. CERRAR SESIÓN
// ==========================================

async function cerrarSesion() {

    try {

        const respuesta = await fetch("../api/logout.php", {
            method: "POST"
        });

        const resultado = await respuesta.json();

        if (respuesta.ok && resultado.ok) {

            await verificarSesion();

        } else {

            console.error("No se pudo cerrar sesión");

        }

    } catch (error) {

        console.error("Error al cerrar sesión:", error);

    }

}


// ==========================================
// 13. NAVEGACIÓN ENTRE VISTAS
// ==========================================

const vistaCatalogo = document.getElementById("vistaCatalogo");
const vistaCarrito = document.getElementById("vistaCarrito");
const aboutUs = document.querySelector(".about-us");

const btnVerCarrito = document.getElementById("btnVerCarrito");
const btnVolverCatalogo = document.getElementById("btnVolverCatalogo");

btnVerCarrito.addEventListener("click", () => {

    vistaCatalogo.classList.add("d-none");
    vistaMisPedidos.classList.add("d-none");
    vistaCarrito.classList.remove("d-none");

    if (aboutUs) {
        aboutUs.classList.add("d-none");
    }

});


const btnVerMisPedidos = document.getElementById("btnVerMisPedidos");
const vistaMisPedidos = document.getElementById("vistaMisPedidos");

btnVerMisPedidos.addEventListener("click", () => {

    vistaCatalogo.classList.add("d-none");
    vistaCarrito.classList.add("d-none");

    vistaMisPedidos.classList.remove("d-none");

    if (aboutUs) {
        aboutUs.classList.add("d-none");
    }

        cargarMisPedidos();
});

const btnVolverDesdePedidos = document.getElementById("btnVolverDesdePedidos");

btnVolverDesdePedidos.addEventListener("click", () => {

    vistaMisPedidos.classList.add("d-none");
    vistaCatalogo.classList.remove("d-none");

    if (aboutUs) {
        aboutUs.classList.remove("d-none");
    }

});


btnVolverCatalogo.addEventListener("click", () => {

    vistaCarrito.classList.add("d-none");
    vistaCatalogo.classList.remove("d-none");

    if (aboutUs) {
        aboutUs.classList.remove("d-none");
    }

});


// ==========================================
// 14. INICIALIZACIÓN
// ==========================================

obtenerProductos();
mostrarCarrito();
verificarSesion();