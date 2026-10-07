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

        const resultado = await respuesta.json();

        console.log("Resultado del pedido:", resultado);

        if (resultado.ok) {
            carrito = [];
            guardarJSON();
            mostrarCarrito();
            alert("Pedido realizado con éxito."); 
        } else {
            alert("Error al realizar el pedido: " + resultado.error);
        }
    } catch (error) {

        console.error("Error al realizar el pedido:", error);

    }
}

const botonPedido = document.getElementById("realizarPedido");

botonPedido.addEventListener("click", () => {

    realizarPedido();

});