const carritoGuardado = localStorage.getItem("carrito");

let carrito = carritoGuardado
    ? JSON.parse(carritoGuardado)
    : [];

async function obtenerProductos() {
    try {

        const respuesta = await fetch("../api/productos.php");

        const productos = await respuesta.json();

        mostrarProductos(productos);

    } catch (error) {

        console.error("Error al obtener los productos:", error);

    }
}

async function mostrarProductos(productos) {

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

        const botonAgregar =
            productoElemento.querySelector(".btn-agregar");


        if (botonAgregar) {

            botonAgregar.addEventListener("click", () => {

                const idProducto =
                    Number(botonAgregar.dataset.id);


                const productoSeleccionado =
                    productos.find(
                        p => Number(p.idProducto) === idProducto
                    );


                const productoEnCarrito =
                    carrito.find(
                        p => Number(p.idProducto) === idProducto
                    );

                    if (productoEnCarrito) {

    const incremento = Number(productoEnCarrito.incremento);
    const cantidadActual = Number(productoEnCarrito.cantidad);
    const stock = Number(productoEnCarrito.stock);

    const nuevaCantidad = Number(
        (cantidadActual + incremento).toFixed(3)
    );

    if (nuevaCantidad <= stock) {

        productoEnCarrito.cantidad = nuevaCantidad;

        guardarJSON();

    } else {

        alert("Cantidad máxima de stock alcanzada");

    }

} else {

    carrito.push({
        ...productoSeleccionado,
        cantidad: Number(productoSeleccionado.incremento)
    });

    guardarJSON();

}


                mostrarCarrito();

            });

        }

    });

}

function mostrarCarrito() {

    const contenedorCarrito =
        document.getElementById("carrito");


    contenedorCarrito.innerHTML = "";

    mostrarResumen();

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
        });

    return;
} else {
    contenedorCarrito.innerHTML = "";
}


    carrito.forEach(producto => {

        const productoCarrito =
            document.createElement("div");


        productoCarrito.classList.add(
            "col-12",
            "col-sm-6",
            "col-lg-4",
            "col-xl-3",
            "elementosCarrito"
        );

        const subtotal =
            Number(producto.precio) * producto.cantidad;


        productoCarrito.innerHTML = `
            <div class="producto h-100">

                <h3 class="text-dark">
                    ${producto.nombre}
                </h3>

                <p class="text-dark fs-5">
                    ${producto.descripcion}
                </p>

                <p class="text-dark fs-5">
                    Stock: ${producto.stock} ${producto.unidadMedida}
                </p>

                <p class="text-dark fs-5">
                    Precio: $${producto.precio} / ${producto.unidadMedida}
                </p>


                <button
                    class="btn btn-danger btn-restar"
                    data-id="${producto.idProducto}">
                    -
                </button>


                <span class="text-dark fs-5">
                    Cantidad: ${producto.cantidad} ${producto.unidadMedida}
                </span>


                <button
                    class="btn btn-success btn-aumentar"
                    data-id="${producto.idProducto}">
                    +
                </button>


                <p class="text-dark fs-5">
                    Subtotal: $${subtotal}
                </p>

            </div>
        `;


        contenedorCarrito.appendChild(productoCarrito);

    });

    const botonesSumar =
        contenedorCarrito.querySelectorAll(".btn-aumentar");


    botonesSumar.forEach(boton => {

        boton.addEventListener("click", () => {

            const idProducto =
                Number(boton.dataset.id);


            const productoEnCarrito =
                carrito.find(
                    p => Number(p.idProducto) === idProducto
                );


const incremento = Number(productoEnCarrito.incremento);
const cantidadActual = Number(productoEnCarrito.cantidad);
const stock = Number(productoEnCarrito.stock);

const nuevaCantidad = Number(
    (cantidadActual + incremento).toFixed(3)
);

if (nuevaCantidad <= stock) {

    productoEnCarrito.cantidad = nuevaCantidad;

    guardarJSON();

} else {

    alert("Cantidad máxima de stock alcanzada");

}


            mostrarCarrito();

        });

    });

    const botonesRestar =
        contenedorCarrito.querySelectorAll(".btn-restar");


    botonesRestar.forEach(boton => {

        boton.addEventListener("click", () => {

            const idProducto =
                Number(boton.dataset.id);


            const productoEnCarrito =
                carrito.find(
                    p => Number(p.idProducto) === idProducto
                );


            const incremento = Number(productoEnCarrito.incremento);
const cantidadActual = Number(productoEnCarrito.cantidad);

const nuevaCantidad = Number(
    (cantidadActual - incremento).toFixed(3)
);

            if (nuevaCantidad > 0) {

                productoEnCarrito.cantidad = nuevaCantidad;
                guardarJSON();
            } else {

                carrito = carrito.filter(
                    p => Number(p.idProducto) !== idProducto
                );
                guardarJSON();

            }

            mostrarCarrito();

        });

    });


    const total = carrito.reduce(
        (acumulador, producto) => {

            return acumulador +
                Number(producto.precio) *
                producto.cantidad;

        },
        0
    );


    const totalElemento =
        document.createElement("p");


    totalElemento.innerHTML =
        "<p id='total'>Total: $" + total + "</p>";


    contenedorCarrito.appendChild(totalElemento);

}
function guardarJSON() {
    const carritoJSON = JSON.stringify(carrito);
    localStorage.setItem(
        "carrito", carritoJSON
    );
}

const botonVaciar = document.getElementById("vaciarCarrito");

botonVaciar.addEventListener("click", () => {

    carrito = [];

    guardarJSON();

    mostrarCarrito();

});

async function verificarSesion() {
    try {
        const respuesta = await fetch("../api/sesion.php");

        const resultado = await respuesta.json();

        const contenedor = document.getElementById("estadoSesion");

        if (resultado.autenticado) {

    contenedor.innerHTML = `
        <span>Bienvenido, ${resultado.nombre}</span>
        <button id="btnLogout" class="btn btn-outline-danger ms-3">
            Cerrar sesión
        </button>
    `;

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

const vistaCatalogo = document.getElementById("vistaCatalogo");
const vistaCarrito = document.getElementById("vistaCarrito");
const aboutUs = document.querySelector(".about-us");

const btnVerCarrito = document.getElementById("btnVerCarrito");
const btnVolverCatalogo = document.getElementById("btnVolverCatalogo");

btnVerCarrito.addEventListener("click", () => {
    vistaCatalogo.classList.add("d-none");
    vistaCarrito.classList.remove("d-none")
    aboutUs.classList.add("d-none");
});

btnVolverCatalogo.addEventListener("click", () => {
    vistaCarrito.classList.add("d-none");
    vistaCatalogo.classList.remove("d-none");
    aboutUs.classList.remove("d-none");
});

function calcularTotal() {
    return carrito.reduce((total, producto) => {
        return total + Number(producto.precio) * producto.cantidad;
    }, 0);
}

function mostrarResumen() {
    const contenedor = document.getElementById("resumenCarrito");
    const resumenCarrito = document.getElementById("resumenPedido");
    if (carrito.length === 0) {
        resumenCarrito.classList.add("d-none");
        return;
    }

    resumenCarrito.classList.remove("d-none");

    const total = calcularTotal();

    contenedor.innerHTML = `
        <div class="d-flex justify-content-between mt-3">
            <span>Total:</span>
            <strong>$${total.toFixed(2)}</strong>
        </div>
    `;

}

console.log(carrito)

obtenerProductos();
mostrarCarrito();
verificarSesion();