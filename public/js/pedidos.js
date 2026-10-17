function mostrarMensajeCheckout(texto, tipo = "danger", link = null) {
    const contenedor = document.getElementById("mensajeCheckout");

    contenedor.textContent = "";

    const alerta = document.createElement("div");
    alerta.className = `alert alert-${tipo}`;
    alerta.setAttribute("role", "alert");

    alerta.textContent = texto;

    if (link) {
        const enlace = document.createElement("a");

        enlace.href = link.url;
        enlace.textContent = link.texto;
        enlace.className = "alert-link ms-2";

        alerta.appendChild(enlace);
    }

    contenedor.appendChild(alerta);
}


async function realizarPedido() {
    
    const mensajeCheckout = document.getElementById("mensajeCheckout");
    mensajeCheckout.textContent = "";

    const boton = document.getElementById("btnConfirmarPedido");

    if (boton.disabled) {
        return;
    }

boton.disabled = true;
boton.textContent = "Procesando pedido...";

    const pedido = {
        productos: carrito
    };

    try {

        const respuesta = await fetch("../api/pedidos.php", {
            method: "POST",

            headers: {
                "Content-Type": "application/json"
            },

            body: JSON.stringify(pedido)
        });

        if (!respuesta.ok) {
            if (respuesta.status === 401) {
                mostrarMensajeCheckout(
            "Debés iniciar sesión.",
            "warning",
            { url: "login.html", texto: "Iniciar sesión" }
        );
                return;
            }
        const resultadoerror = await respuesta.json();
        mostrarMensajeCheckout(resultadoerror.error || "No se pudo procesar el pedido");
        return;
        }

        const resultado = await respuesta.json();

        console.log("Resultado del pedido:", resultado);

        if (resultado.ok) {

            carrito = [];
            guardarJSON();
            mostrarCarrito();

            await obtenerProductos();

            mostrarMensajeCheckout("Pedido realizado con éxito.", "success");

        } else {
            mostrarMensajeCheckout(resultado.error || "No se pudo realizar el pedido");
        }
    } catch (error) {
        console.error("Error al realizar el pedido:", error);
        mostrarMensajeCheckout("Error al realizar el pedido. Por favor, inténtalo de nuevo.");

    } finally {
        boton.disabled = false;
        boton.textContent = "Confirmar pedido";
    }
}

const botonPedido = document.getElementById("btnConfirmarPedido");

botonPedido.addEventListener("click", () => {

    realizarPedido();

});