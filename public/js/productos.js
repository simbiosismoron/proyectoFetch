let carrito = [];

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
                Precio: $${producto.precio}
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

                    if (
                        productoEnCarrito.cantidad <
                        Number(productoEnCarrito.stock)
                    ) {

                        productoEnCarrito.cantidad++;

                    } else {

                        alert(
                            "Cantidad máxima de stock alcanzada"
                        );

                    }

                } else {

                    carrito.push({
                        ...productoSeleccionado,
                        cantidad: 1
                    });

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
                    Stock: ${producto.stock}
                </p>

                <p class="text-dark fs-5">
                    Precio unitario: $${producto.precio}
                </p>


                <button
                    class="btn btn-danger btn-restar"
                    data-id="${producto.idProducto}">
                    -
                </button>


                <span class="text-dark fs-5">
                    Cantidad: ${producto.cantidad}
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


            if (
                productoEnCarrito.cantidad <
                Number(productoEnCarrito.stock)
            ) {

                productoEnCarrito.cantidad++;

            } else {

                alert(
                    "Cantidad máxima de stock alcanzada"
                );

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


            if (productoEnCarrito.cantidad > 1) {

                productoEnCarrito.cantidad--;

            } else {


                carrito = carrito.filter(
                    p => Number(p.idProducto) !== idProducto
                );

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
        "Total: $" + total;


    contenedorCarrito.appendChild(totalElemento);

}

obtenerProductos();