const formRegistro = document.getElementById("formRegistro");

formRegistro.addEventListener("submit", (e) => {

    e.preventDefault();

    const nombre = document.getElementById("nombre").value;
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    const usuario = {
        nombre,
        email,
        password
    }

    registrarUsuario(usuario);

    console.log("Formulario enviado");
});

async function registrarUsuario(usuario) {
    try {
        const respuesta = await fetch ("../api/registro.php", {
            method: "POST",
            headers: {
                "Content-type" : "application/json"
            },
            body: JSON.stringify(usuario)
        });
        
        const resultado = await respuesta.json();
        const mensajeregistro = document.getElementById("mensajeRegistro");
        if (resultado.ok) {

            mensajeregistro.textContent = "Usuario registrado correctamente";
            mensajeregistro.className = "mt-3 text-success";

    } else {

        mensajeregistro.textContent = resultado.error || "No se pudo completar el registro";
        mensajeregistro.className = "mt-3 text-danger";

    }
    } catch (error) {
        console.error("Error al registrar el usuario", error)
    }
}