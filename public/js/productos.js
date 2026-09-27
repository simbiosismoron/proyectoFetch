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
        mostrarJSON();

    } else {

        alert("Cantidad máxima de stock alcanzada");

    }

} else {

    carrito.push({
        ...productoSeleccionado,
        cantidad: Number(productoSeleccionado.incremento)
    });

    guardarJSON();
    mostrarJSON();

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
    mostrarJSON();

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
                mostrarJSON();
            } else {

                carrito = carrito.filter(
                    p => Number(p.idProducto) !== idProducto
                );
                guardarJSON();
                mostrarJSON();

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

function mostrarJSON() {
    console.log(carrito);
}

const botonVaciar = document.getElementById("vaciarCarrito");

botonVaciar.addEventListener("click", () => {

    carrito = [];

    guardarJSON();

    mostrarCarrito();

});

obtenerProductos();
mostrarCarrito();