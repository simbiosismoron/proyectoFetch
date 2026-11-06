const carritoGuardado = localStorage.getItem("carrito");

let carrito = carritoGuardado
    ? JSON.parse(carritoGuardado)
    : [];


let todosLosProductos = [];


async function obtenerProductos() {
    try {
        const respuesta = await fetch("/../api/productos.php");

        if (!respuesta.ok) {
            throw new Error("No se pudieron obtener los productos");
        }

        const productos = await respuesta.json();

        todosLosProductos = productos;

        filtrarProductos();


    } catch (error) {
        console.error("Error al obtener los productos:", error);
    }
}




const buscadorProductos = document.getElementById("buscadorProductos");
const filtroCategoria = document.getElementById("filtroCategoria");

buscadorProductos.addEventListener("input", filtrarProductos);
filtroCategoria.addEventListener("change", filtrarProductos);

async function cargarCategoriasCatalogo() {
    try {
        const respuesta = await fetch("/api/categorias.php");

        if (!respuesta.ok) {
            throw new Error("No se pudieron obtener las categorías");
        }

        const categorias = await respuesta.json();

        categorias.forEach(categoria => {
            const opcion = document.createElement("option");
            opcion.value = categoria.idCategoria;
            opcion.textContent = categoria.nombre;
            filtroCategoria.appendChild(opcion);
        });
    } catch (error) {
        console.error("Error al cargar categorías:", error);
    }
}

cargarCategoriasCatalogo();

function filtrarProductos() {
    const busqueda = buscadorProductos.value.trim().toLowerCase();
    const categoriaSeleccionada = filtroCategoria.value;

    const productosFiltrados = todosLosProductos.filter(producto => {
        const coincideNombre = producto.nombre.toLowerCase().includes(busqueda);
        const coincideCategoria = categoriaSeleccionada === "" ||
            String(producto.idCategoria) === categoriaSeleccionada;

        return coincideNombre && coincideCategoria;
    });

    mostrarProductos(productosFiltrados);
}



// Escapa datos externos antes de interpolarlos en plantillas HTML.
function escaparHTML(valor) {
    return String(valor ?? "").replace(/[&<>"']/g, caracter => ({
        "&": "&amp;",
        "<": "&lt;",
        ">": "&gt;",
        '"': "&quot;",
        "'": "&#39;"
    })[caracter]);
}

function mostrarProductos(productos) {
    const contenedor = document.getElementById("contenedorProductos");
    contenedor.innerHTML = "";

    if (productos.length === 0) {
        contenedor.innerHTML = '<p class="catalogo-sin-resultados">No se encontraron productos.</p>';
        return;
    }

    // La cabecera se muestra en escritorio; en móvil cada producto se presenta como ficha.
    const cabecera = document.createElement("div");
    cabecera.className = "inventario-cabecera";
    cabecera.setAttribute("aria-hidden", "true");
    cabecera.innerHTML = "<span>Producto</span><span>Precio</span><span>Disponibilidad</span><span>Acción</span>";
    contenedor.appendChild(cabecera);

    productos.forEach(producto => {
        const disponible = Number(producto.stock) > 0;
        const fila = document.createElement("details");
        fila.className = "inventario-item";
        const precio = Number(producto.precio);
        const precioFormateado = Number.isFinite(precio)
            ? precio.toLocaleString("es-AR", { minimumFractionDigits: 2, maximumFractionDigits: 2 })
            : escaparHTML(producto.precio);

        fila.innerHTML = `
            <summary class="inventario-fila">
                <span class="inventario-nombre">
                    <strong>${escaparHTML(producto.nombre)}</strong>
                    <small>Ver detalles <span class="inventario-chevron" aria-hidden="true">⌄</span></small>
                </span>
                <span class="inventario-precio" data-etiqueta="Precio">$ ${precioFormateado}</span>
                <span class="inventario-stock" data-etiqueta="Disponibilidad">
                    <span class="${disponible ? "stock-disponible" : "stock-agotado"}">
                        ${disponible ? "Disponible" : "Sin stock"}
                    </span>
                </span>
                <span class="inventario-accion">
                    <button type="button" class="btn btn-pampa btn-sm btn-agregar"
                        data-id="${escaparHTML(producto.idProducto)}"
                        ${disponible ? "" : "disabled"}>
                        ${disponible ? "Agregar +" : "Agotado"}
                    </button>
                </span>
            </summary>
            <div class="inventario-detalles">
                <strong>Descripción</strong>
                <p>${escaparHTML(producto.descripcion || "Sin descripción disponible.")}</p>
                <small>Stock disponible: ${escaparHTML(producto.stock)}</small>
            </div>
        `;

        // El botón de compra no debe desplegar ni cerrar los detalles de la fila.
        const botonAgregar = fila.querySelector(".btn-agregar");
        botonAgregar.addEventListener("click", evento => {
            evento.preventDefault();
            evento.stopPropagation();
            if (!disponible) return;

            const idProducto = Number(botonAgregar.dataset.id);
            const productoEnCarrito = carrito.find(p => Number(p.idProducto) === idProducto);

            if (productoEnCarrito) {
                aumentarCantidad(idProducto);
                return;
            }

            const stock = Number(producto.stock);
            if (!Number.isInteger(stock) || stock < 1) {
                alert("Stock insuficiente o incremento inválido");
                return;
            }

            carrito.push({ ...producto, cantidad: 1 });
            guardarJSON();
            mostrarCarrito();
        });

        contenedor.appendChild(fila);
    });
}

function guardarJSON() {

    const carritoJSON = JSON.stringify(carrito);

    localStorage.setItem("carrito", carritoJSON);

}


function aumentarCantidad(idProducto) {

    const producto = carrito.find(
        p => Number(p.idProducto) === Number(idProducto)
    );

    if (!producto) {
        return;
    }

    const cantidadActual = Number(producto.cantidad);
    const stock = Number(producto.stock);

    if (cantidadActual + 1 > stock) {
        alert("Cantidad máxima de stock alcanzada");
        return;
    }

    const nuevaCantidad = cantidadActual + 1;

    producto.cantidad = nuevaCantidad;

    guardarJSON();
    mostrarCarrito();

}


function disminuirCantidad(idProducto) {

    const producto = carrito.find(
        p => Number(p.idProducto) === Number(idProducto)
    );

    if (!producto) {
        return;
    }

    const cantidadActual = Number(producto.cantidad);

    if (cantidadActual > 1) {

        producto.cantidad = cantidadActual - 1;

    } else {

        carrito = carrito.filter(
            p => Number(p.idProducto) !== Number(idProducto)
        );

    }

    guardarJSON();
    mostrarCarrito();

}

function calcularTotal() {

    return carrito.reduce((total, producto) => {

        return total + Number(producto.precio) * Number(producto.cantidad);

    }, 0);

}

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

const columnaProductos = document.getElementById("columnaProductosCarrito");

const botonVaciar = document.getElementById("vaciarCarrito");

function mostrarCarrito() {

    const contenedorCarrito = document.getElementById("carrito");

    contenedorCarrito.innerHTML = "";

    const carritoVacio = carrito.length === 0;
    const filaCarrito = document.getElementById("filaCarrito");
    const columnaResumen = document.getElementById("columnaResumenCarrito");

    filaCarrito.classList.toggle("justify-content-center", carritoVacio);
    columnaResumen.classList.toggle("d-none", carritoVacio);
    columnaProductos.classList.toggle("mx-auto", carritoVacio);
    botonVaciar.classList.toggle("d-none", carritoVacio);

    mostrarResumen();

    if (carrito.length === 0) {

        contenedorCarrito.innerHTML = `
            <div class="col-12 d-flex align-items-center justify-content-center">
                <div class="col-12 text-center py-5">
                    <h3>Tu carrito está vacío</h3>

                    <p class="text-secondary">
                        Todavía no agregaste productos.
                    </p>

                    <button id="btnExplorar" class="btn btn-primary">
                        Explorar productos
                    </button>
                </div>
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
                            ${escaparHTML(producto.nombre)}
                        </h5>

                        <p class="text-secondary small mb-2">
                            ${escaparHTML(producto.descripcion)}
                        </p>

                        <p class="text-secondary small mb-1">
                            Disponible: ${escaparHTML(producto.stock)} ${escaparHTML(producto.unidadMedida)}
                        </p>

                        <p class="mb-0">
                            $${escaparHTML(producto.precio)} / ${escaparHTML(producto.unidadMedida)}
                        </p>

                    </div>

                    <!-- Cantidad y subtotal -->
                    <div class="d-flex flex-column align-items-md-end gap-3">

                        <div class="d-flex align-items-center gap-2">

                            <button
                                class="btn btn-outline-secondary btn-restar"
                                data-id="${escaparHTML(producto.idProducto)}">
                                −
                            </button>

                            <span class="text-nowrap">
                                ${escaparHTML(producto.cantidad)} ${escaparHTML(producto.unidadMedida)}
                            </span>

                            <button
                                class="btn btn-outline-secondary btn-aumentar"
                                data-id="${escaparHTML(producto.idProducto)}">
                                +
                            </button>

                        </div>

                        <strong class="fs-5">
                            $${subtotal.toFixed(2)}
                        </strong>

                        <button class="btn btn-outline-danger btn-eliminar" data-id="${escaparHTML(producto.idProducto)}">
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

        const respuesta = await fetch("/../api/sesion.php");

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

        const respuesta = await fetch("/../api/logout.php", {
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

    // Cada visita al carrito comienza sin mensajes de un checkout anterior.
    document.getElementById("mensajeCheckout").textContent = "";

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