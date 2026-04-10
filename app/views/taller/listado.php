<!DOCTYPE html>
<html>

<head>

    <title>Listado Talleres</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet">
    <link rel="stylesheet" href="public/css/style.css?v=20260409b">
    <script src="public/js/jquery-4.0.0.min.js"></script>
    <script src="public/js/auth.js"></script>
    <script src="public/js/taller.js"></script>
</head>

<body>

    <nav class="navbar navbar-expand-lg bg-white app-navbar mb-4">
        <div class="container">
            <div class="navbar-nav me-auto">
                <a class="nav-link" href="index.php?page=talleres">Talleres</a>
            <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                <a href="index.php?page=admin">Gestionar Solicitudes</a>
            <?php endif; ?>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span> Usuario: <?= htmlspecialchars($_SESSION['nombre'] ?? $_SESSION['user'] ?? 'Usuario') ?></span>
            <button id="btnLogout" class="btn btn-primary">Cerrar sesión</button>
            <div>
        </div>
    </nav>
    <main class="container pb-4">
        <h3>Talleres</h3>

        <table class="table table-bordered app-table">
            <thead>
                <tr>
                    <th>Taller</th>
                    <th>Descripcion</th>
                    <th>Cupo maximo</th>
                    <th>Cupo disponible</th>
                    <th>Accion</th>
                </tr>
            </thead>
            <tbody id="talleres-body">
                <tr>
                    <td colspan="5">Cargando talleres...</td>
                </tr>
            </tbody>
        </table>
        <div id="mensaje" class="mensaje alert alert-danger" role="alert"></div>
    </main>



</body>

</html>