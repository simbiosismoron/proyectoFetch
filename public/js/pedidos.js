async function realizarPedido() {

    const pedido = {
        idUsuario: 1,
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
            const errorText = await respuesta.text();
            alert(errorText || "Error del servidor");
            return;
        }

        const resultado = await respuesta.json();

        console.log("Resultado del pedido:", resultado);

        if (resultado.ok) {

            carrito = [];
            guardarJSON();
            mostrarCarrito();

            console.log("Pedido realizado con éxito.");

            await obtenerProductos();

            console.log("Catálogo actualizado."); 

        } else {
            alert(resultado.error || "No se pudo realizar el pedido");
        }
    } catch (error) {

        console.error("Error al realizar el pedido:", error);

    }
}

const botonPedido = document.getElementById("realizarPedido");

botonPedido.addEventListener("click", () => {

    realizarPedido();

});