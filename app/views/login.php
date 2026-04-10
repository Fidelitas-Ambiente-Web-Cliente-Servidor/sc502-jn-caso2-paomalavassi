<!DOCTYPE html>
<html>

<head>

    <title>Login</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet">
    <link rel="stylesheet" href="public/css/style.css?v=20260409c">
    <script src="public/js/jquery-4.0.0.min.js"></script>
    <script src="public/js/auth.js"></script>
</head>

<body class="auth-page">

    <main class="auth-shell container-fluid">
        <div class="row min-vh-100 g-0">
            <section class="col-lg-7 auth-hero d-none d-lg-flex">
                <div>
                    <h1>Portal de Talleres</h1>
                    <p>Gestiona tus solicitudes y revisa cupos disponibles en tiempo real.</p>
                </div>
            </section>

            <section class="col-12 col-lg-5 d-flex align-items-center">
                <div class="auth-form-wrap w-100">
                    <div class="app-card auth-card p-4 p-md-5">
                        <h2 class="app-title mb-3">Ingreso al Sistema</h2>

                        <form id="formLogin">
                            <input
                                class="form-control mb-2"
                                name="username"
                                id="username"
                                placeholder="Usuario">

                            <input
                                type="password"
                                class="form-control mb-2"
                                name="password"
                                id="password"
                                placeholder="Contraseña">

                            <div class="d-flex flex-wrap gap-2">
                                <button type="submit" class="btn btn-primary">
                                    Ingresar
                                </button>
                                <a href="index.php?page=registro" class="btn btn-outline-primary">Registrarse</a>
                            </div>
                        </form>

                        <div id="mensaje" class="mensaje alert alert-danger" role="alert"></div>
                    </div>
                </div>
            </section>
        </div>
    </main>



</body>

</html>