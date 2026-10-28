const formCrearProducto = document.getElementById("formCrearProducto");
const mensajeCrearProducto = document.getElementById("mensajeCrearProducto");

async function cargarCategorias() {
    const selectCategorias = document.getElementById("idCategoria");

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

            selectCategorias.appendChild(opcion);
        });

    } catch (error) {
        console.error(error.message);
    }
}

cargarCategorias();


async function cargarProveedores() {
    const selectProveedores = document.getElementById("idProveedor")

    try {
        const respuesta = await fetch("/api/admin/proveedores.php")

        if (!respuesta.ok) {
            throw new Error("No se pudieron obtener los proveedores")
        }

        const proveedores = await respuesta.json();

        proveedores.forEach(proveedor => {
            const opcion = document.createElement("option")

            opcion.value = proveedor.idProveedor;
            opcion.textContent = proveedor.nombre;

            selectProveedores.appendChild(opcion);
        })
    } catch (error) {
        console.error(error.message);
    }
}

cargarProveedores();


formCrearProducto.addEventListener("submit", async function (event) {
    event.preventDefault();

    const datosFormulario = new FormData(formCrearProducto);

    const producto = {
        nombre: datosFormulario.get("nombre"),
        codigo: datosFormulario.get("codigo"),
        idCategoria: Number(datosFormulario.get("idCategoria")),
        idProveedor: Number(datosFormulario.get("idProveedor")),
        descripcion: datosFormulario.get("descripcion"),
        costo: Number(datosFormulario.get("costo")),
        precio: Number(datosFormulario.get("precio")),
        stock: Number(datosFormulario.get("stock")),
        foto: datosFormulario.get("foto") || null
    };


    mensajeCrearProducto.textContent = "Guardando producto..."

    try {

        const respuesta = await fetch("/api/admin/productos.php", {
            method: "POST",
            headers: {
                "Content-Type": "Application/json"
            },
            body: JSON.stringify(producto)
        });
        
        const datos = await respuesta.json();

        if (!respuesta.ok) {
            throw new Error (datos.error || "No se pudo crear el producto")
        }

        mensajeCrearProducto.textContent = datos.mensaje

        formCrearProducto.reset();

        await cargarProductosAdmin();

    } catch(error){
        mensajeCrearProducto.textContent = error.message;
    }  
});


async function cargarProductosAdmin() {
    const tablaProductos = document.getElementById("tablaProductos");
    const mensajeProductos = document.getElementById("mensajeProductos");

    try {
        const respuesta = await fetch("/api/admin/productos.php");

        if (!respuesta.ok) {
            throw new Error("No se pudieron obtener los productos");
        }

        const productos = await respuesta.json();

        tablaProductos.replaceChildren();

        productos.forEach(producto => {
            const fila = document.createElement("tr");

            const campos = [
                "idProducto",
                "codigo",
                "nombre",
                "costo",
                "precio",
                "stock"
            ];

            campos.forEach(campo => {
                const celda = document.createElement("td");

                celda.textContent = producto[campo];

                fila.appendChild(celda);
            });

            const celdaAcciones = document.createElement("td");

            celdaAcciones.textContent = "Próximamente";

            fila.appendChild(celdaAcciones);

            tablaProductos.appendChild(fila);
        });

        mensajeProductos.textContent =
            productos.length === 0 ? "No hay productos registrados" : "";

    } catch (error) {
        mensajeProductos.textContent = error.message;
    }
}

cargarProductosAdmin();
