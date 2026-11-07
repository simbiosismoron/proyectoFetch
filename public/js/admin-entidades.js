// Gestión de entidades maestras del catálogo (solo interfaz; autorización en PHP).
function configurarABM({ nombre, ruta, claveId, campos, refrescarSelect }) {
    const form = document.getElementById("form" + nombre);
    const tabla = document.getElementById("tabla" + nombre);
    const mensaje = document.getElementById("mensaje" + nombre);
    const guardar = document.getElementById("guardar" + nombre);
    const cancelar = document.getElementById("cancelar" + nombre);
    let editando = null;

    function limpiar() {
        editando = null;
        form.reset();
        guardar.textContent = "Crear";
        cancelar.classList.add("d-none");
    }

    async function solicitar(metodo, cuerpo) {
        const respuesta = await fetch(ruta, {
            method: metodo,
            headers: { "Content-Type": "application/json" },
            ...(cuerpo ? { body: JSON.stringify(cuerpo) } : {})
        });
        const datos = await respuesta.json();
        if (!respuesta.ok) throw new Error(datos.error || "Error en la operación");
        return datos;
    }

    async function cargar() {
        const registros = await solicitar("GET");
        tabla.replaceChildren();
        for (const registro of registros) {
            const fila = document.createElement("tr");
            for (const campo of campos) {
                const celda = document.createElement("td");
                celda.textContent = registro[campo] ?? "";
                fila.appendChild(celda);
            }
            const acciones = document.createElement("td");
            const editar = document.createElement("button");
            editar.type = "button";
            editar.className = "btn btn-sm btn-outline-primary me-2";
            editar.textContent = "Editar";
            editar.addEventListener("click", () => {
                editando = Number(registro[claveId]);
                for (const campo of campos) form.elements.namedItem(campo).value = registro[campo];
                guardar.textContent = "Guardar cambios";
                cancelar.classList.remove("d-none");
                form.scrollIntoView({ behavior: "smooth" });
            });
            const eliminar = document.createElement("button");
            eliminar.type = "button";
            eliminar.className = "btn btn-sm btn-outline-danger";
            eliminar.textContent = "Eliminar";
            eliminar.addEventListener("click", async () => {
                if (!confirm("¿Eliminar este registro? Solo se permite si no tiene productos asociados.")) return;
                try {
                    await solicitar("DELETE", { [claveId]: Number(registro[claveId]) });
                    limpiar();
                    await cargar();
                    await refrescarSelect();
                    mensaje.textContent = "Registro eliminado";
                } catch (error) { mensaje.textContent = error.message; }
            });
            acciones.append(editar, eliminar);
            fila.appendChild(acciones);
            tabla.appendChild(fila);
        }
    }

    cancelar.addEventListener("click", limpiar);
    form.addEventListener("submit", async (evento) => {
        evento.preventDefault();
        const datos = Object.fromEntries(new FormData(form).entries());
        if (editando !== null) datos[claveId] = editando;
        try {
            const resultado = await solicitar(editando === null ? "POST" : "PUT", datos);
            limpiar();
            await cargar();
            await refrescarSelect();
            mensaje.textContent = resultado.mensaje;
        } catch (error) { mensaje.textContent = error.message; }
    });
    cargar().catch(error => { mensaje.textContent = error.message; });
}

async function actualizarOpciones(selectId, ruta, claveId) {
    const select = document.getElementById(selectId);
    const anterior = select.value;
    const respuesta = await fetch(ruta);
    if (!respuesta.ok) throw new Error("No se pudieron actualizar las opciones");
    const registros = await respuesta.json();
    select.replaceChildren(new Option("Seleccionar...", ""));
    for (const registro of registros) select.add(new Option(registro.nombre, registro[claveId]));
    if ([...select.options].some(opcion => opcion.value === anterior)) select.value = anterior;
}

configurarABM({
    nombre: "Categorias",
    ruta: "/api/admin/categorias.php",
    claveId: "idCategoria",
    campos: ["nombre"],
    refrescarSelect: () => actualizarOpciones("idCategoria", "/api/categorias.php", "idCategoria")
});
configurarABM({
    nombre: "Proveedores",
    ruta: "/api/admin/proveedores.php",
    claveId: "idProveedor",
    campos: ["nombre", "cuit", "razonSocial"],
    refrescarSelect: () => actualizarOpciones("idProveedor", "/api/admin/proveedores.php", "idProveedor")
});
