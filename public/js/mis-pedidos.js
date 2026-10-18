async function cargarMisPedidos() {
    const contenedor = document.getElementById("contenedorMisPedidos");
    const mensaje = document.getElementById("mensajeMisPedidos");

    contenedor.innerHTML = "";
    mensaje.textContent = "Cargando pedidos...";

    try {
        const respuesta = await fetch("../api/mis-pedidos.php");

        const datos = await respuesta.json();

        if (!respuesta.ok) {
            throw new Error(datos.error || "No se pudieron cargar los pedidos");
        }

        mensaje.textContent = "";

        console.log("Pedidos recibidos:", datos.pedidos);

        mostrarMisPedidos(datos.pedidos);

    } catch (error) {
        mensaje.textContent = error.message;
    }
}

function mostrarMisPedidos(pedidos) {
    const contenedor = document.getElementById("contenedorMisPedidos");

    contenedor.innerHTML = "";

    if (pedidos.length === 0) {
        contenedor.textContent = "Todavía no realizaste ningún pedido.";
        return;
    }

    pedidos.forEach(pedido => {
        const columna = document.createElement("div");
        columna.classList.add("col-12");

        const card = document.createElement("div");
        card.classList.add("border", "rounded", "p-4", "bg-white");

        const titulo = document.createElement("h4");
        titulo.textContent = `Pedido #${pedido.idVenta}`;

        const fecha = document.createElement("p");
        fecha.textContent = `Fecha: ${pedido.fecha}`;

        const total = document.createElement("p");
        total.textContent = `Total: $${pedido.total}`;

        card.appendChild(titulo);
        card.appendChild(fecha);
        card.appendChild(total);

        columna.appendChild(card);
        contenedor.appendChild(columna);
    });
}