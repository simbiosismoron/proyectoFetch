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

        const resultado = await respuesta.text();

        console.log("Status:", respuesta.status);
        console.log("Respuesta PHP:", resultado);

    } catch (error) {

        console.error("Error al realizar el pedido:", error);

    }
}

const botonPedido = document.getElementById("realizarPedido");

botonPedido.addEventListener("click", () => {

    realizarPedido();

});