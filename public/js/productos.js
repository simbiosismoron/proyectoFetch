let carrito = [];

async function obtenerProductos () {
    try {
        const respuesta = await fetch("../api/productos.php");
        const productos = await respuesta.json();

        console.log(productos);
        mostrarProductos(productos);
    } catch (error) {
        console.error("Error al obtener los productos:", error);
    }
}

function mostrarProductos(productos) {
    const contenedor = document.getElementById("contenedorProductos");

    contenedor.innerHTML = "";

    productos.forEach(producto => {

        const columna = document.createElement("div");

        columna.classList.add(
            "col-12",
            "col-sm-6",
            "col-lg-4",
            "col-xl-3"
        );

        const productoElemento = document.createElement("div");

        productoElemento.classList.add("producto", "h-100");

        productoElemento.style.setProperty(
            "--imagen-producto",
            `url("img/productos/${producto.foto}")`
        );

        productoElemento.innerHTML = `
            <h3 class="text-white">${producto.nombre}</h3>

            <p class="text-white fs-5">${producto.descripcion}</p>

            <p class="text-white fs-5">Precio: $${producto.precio}</p>

            <button class="btn btn-primary btn-agregar" data-id="${producto.idProducto}">
                Agregar al carrito
            </button>
        `;

        columna.appendChild(productoElemento);

        contenedor.appendChild(columna);


        const botonesAgregar = productoElemento.querySelectorAll(".btn-agregar");
        botonesAgregar.forEach(boton => {
            boton.addEventListener("click", () => {
                const idProducto = Number(boton.dataset.id);
                const productoSeleccionado = productos.find(p => p.idProducto === idProducto);
                carrito.push(productoSeleccionado);
                console.log(carrito);
            });

        });
    }); 
};


obtenerProductos();