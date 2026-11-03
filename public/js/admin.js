const formCrearProducto = document.getElementById("formCrearProducto");
const mensajeCrearProducto = document.getElementById("mensajeCrearProducto");
let idProductoEnEdicion = null;

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

    const esEdicion = idProductoEnEdicion !== null;

        const metodo = esEdicion ? "PUT" : "POST";

        if (esEdicion) {
            producto.idProducto = idProductoEnEdicion;
        }

    mensajeCrearProducto.textContent = "Guardando producto..."

    try {

        const respuesta = await fetch("/api/admin/productos.php", {
            method: metodo,
            headers: {
                "Content-Type": "Application/json"
            },
            body: JSON.stringify(producto)
        });
        
        const datos = await respuesta.json();

        if (!respuesta.ok) {
            throw new Error (datos.error || "No se pudo crear el producto")
        }

        restaurarFormularioProducto();

        await cargarProductosAdmin();

        mensajeCrearProducto.textContent = datos.mensaje

        formCrearProducto.reset();

        await cargarProductosAdmin();

    } catch(error){
        mensajeCrearProducto.textContent = error.message;
    }  
});


async function obtenerProductoPorId(idProducto) {
    const respuesta = await fetch(
        `/api/admin/productos.php?idProducto=${idProducto}`
    );

    const datos = await respuesta.json();

    if (!respuesta.ok) {
        throw new Error(datos.error || "No se pudo obtener el producto");
    }

    return datos;
}


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

            const botonEditar = document.createElement("button");

            botonEditar.type = "button";
            botonEditar.className = "btn btn-sm btn-outline-primary";
            botonEditar.textContent = "Editar";

           botonEditar.addEventListener("click", () => {
                editarProducto(producto.idProducto);
            });

            celdaAcciones.appendChild(botonEditar);
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

async function editarProducto(idProducto) {
    try {
        const producto = await obtenerProductoPorId(idProducto);

        document.getElementById("nombre").value = producto.nombre;
        document.getElementById("codigo").value = producto.codigo;
        document.getElementById("descripcion").value = producto.descripcion ?? "";
        document.getElementById("costo").value = producto.costo;
        document.getElementById("precio").value = producto.precio;
        document.getElementById("stock").value = producto.stock;
        document.getElementById("idCategoria").value = producto.idCategoria;
        document.getElementById("idProveedor").value = producto.idProveedor;
        document.getElementById("foto").value = producto.foto ?? "";

        idProductoEnEdicion = producto.idProducto;

        document.getElementById("btnGuardarProducto").textContent =
            "Guardar cambios";

        document.getElementById("btnCancelarEdicion").classList.remove("d-none");

        document.getElementById("formCrearProducto").scrollIntoView({
            behavior: "smooth"
        });

    } catch (error) {
        console.error(error);
        alert(error.message);
    }   
}

document.getElementById("btnCancelarEdicion").addEventListener("click", () => {
    restaurarFormularioProducto();
});

function restaurarFormularioProducto() {
    idProductoEnEdicion = null;

    document.getElementById("formCrearProducto").reset();

    document.getElementById("btnGuardarProducto").textContent =
        "Crear producto";

    document.getElementById("btnCancelarEdicion").classList.add("d-none");
}