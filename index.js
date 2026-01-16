function verPassword() {
    const password = document.getElementById("usu_pass");
    const icono = document.getElementById("icono");

    if (password.type === "password") {
        password.type = "text";
        icono.classList.remove("fa-eye");
        icono.classList.add("fa-eye-slash");
    } else {
        password.type = "password";
        icono.classList.remove("fa-eye-slash");
        icono.classList.add("fa-eye");
    }
}
