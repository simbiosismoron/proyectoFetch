const formLogin = document.getElementById('formLogin');

formLogin.addEventListener("submit", (e) => {
    e.preventDefault();

    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    const credenciales = {
        email,
        password
    };

    realizarLogin(credenciales);
})

async function realizarLogin(credenciales) {
    try {
        const respuesta = await fetch("../api/login.php", {
            method: "POST",
            headers: {
                "Content-type" : "application/json"
            },
            body: JSON.stringify(credenciales)
        });

        const resultado = await respuesta.json();

        const mensajeLogin = document.getElementById('mensajeLogin');
        if (resultado.ok) {
                window.location.href = "index.php";
        } else {
            mensajeLogin.textContent = "Error al iniciar sesión";
            mensajeLogin.classList.remove("text-success");
            mensajeLogin.classList.add("text-danger");
        }
    } catch (error) {
        console.error("Error al realizar el login:", error);
        const mensajeLogin = document.getElementById('mensajeLogin');
        mensajeLogin.textContent = "Error al realizar el login";
        mensajeLogin.classList.remove("text-success");
        mensajeLogin.classList.add("text-danger");
    }
}

async function cerrarSesion() {
    try {
        const respuesta = await fetch("../api/logout.php", {
            method: "POST"
        });

        const resultado = await respuesta.json();

        if (respuesta.ok && resultado.ok) {
            window.location.href = "login.html";
        } else {
            console.error("No se pudo cerrar la sesión");
        }

    } catch (error) {
        console.error("Error al cerrar sesión:", error);
    }
}