async function realizarPedido() {

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
                const mensaje = document.getElementById("mensajeCheckout");

                mensaje.innerHTML = `
                    <div class="alert alert-warning" role="alert">
                        Debés iniciar sesión para confirmar tu pedido.

                        <a href="login.html" class="alert-link">
                            Iniciar sesión
                        </a>
                    </div>
            `};
            
            return;

        }

        const resultado = await respuesta.json();

        console.log("Resultado del pedido:", resultado);

        if (resultado.ok) {

            carrito = [];
            guardarJSON();
            mostrarCarrito();

            await obtenerProductos();

            alert("Pedido realizado con éxito.");

        } else {
            alert(resultado.error || "No se pudo realizar el pedido");
        }
    } catch (error) {
        console.error("Error al realizar el pedido:", error);
        alert("Error al realizar el pedido. Por favor, inténtalo de nuevo.");

    } finally {
        boton.disabled = false;
        boton.textContent = "Confirmar pedido";
    }
}

const botonPedido = document.getElementById("btnConfirmarPedido");

botonPedido.addEventListener("click", () => {

    realizarPedido();

});