$(function () {
    const formLogin = $("#formLogin");
    const btnLogout = $("#btnLogout");
    const urlBase = "index.php";

    if (formLogin.length) {
        formLogin.on("submit", function (event) {
            event.preventDefault();
            const username = $("#username").val().trim();
            const password = $("#password").val();
            const mensaje = $("#mensaje");

            if (username === "" || password === "") {
                mensaje.text("Debe completar todos los campos").removeClass("ok").addClass("error").show();
                return;
            }

            $.ajax({
                url: urlBase,
                method: "POST",
                dataType: "json",
                data: {
                    username: username,
                    password: password,
                    option: "login"
                },
                success: function (data) {
                    if (data.response === "00") {
                        window.location = data.rol === "admin" ? "index.php?page=admin" : "index.php?page=talleres";
                    } else {
                        mensaje.text(data.message).removeClass("ok").addClass("error").show();
                    }
                },
                error: function () {
                    mensaje.text("Error de conexion").removeClass("ok").addClass("error").show();
                }
            });
        });
    }

    if (btnLogout.length) {
        btnLogout.on("click", function () {
            $.ajax({
                url: urlBase,
                method: "POST",
                dataType: "json",
                data: { option: "logout" },
                success: function () {
                    window.location = "index.php?page=login";
                },
                error: function () {
                    window.location = "index.php?page=login";
                }
            });
        });
    }

});
