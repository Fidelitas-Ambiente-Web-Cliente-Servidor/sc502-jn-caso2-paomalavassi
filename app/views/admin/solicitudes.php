<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Solicitudes pendientes</title>
     <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet">
    <link rel="stylesheet" href="public/css/style.css?v=20260409b">
    <script src="public/js/jquery-4.0.0.min.js"></script>
    <script src="public/js/auth.js"></script>
    <script src="public/js/solicitud.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-white app-navbar mb-4">
        <div class="container">
            <div class="navbar-nav me-auto">
            <a class="nav-link" href="index.php?page=talleres">Talleres</a>
            <a class="nav-link" href="index.php?page=admin">Gestionar Solicitudes</a>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span>Admin: <?= htmlspecialchars($_SESSION['nombre'] ?? $_SESSION['user'] ?? 'Administrador') ?></span>
            <button id="btnLogout" class="btn-logout">Cerrar sesión</button>
            </div>
        </div>
    </nav>
    
    <main class="container pb-4">
        <h2 class="app-title mb-3">Solicitudes pendientes de aprobación</h2>
        
        <div>
            <table id="tabla-solicitudes" class="table table-bordered app-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Taller</th>
                        <th>Solicitante</th>
                        <th>Usuario</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="solicitudes-body">
                    <tr>
                        <td colspan="6" class="loader">Cargando solicitudes...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>

    <div id="mensaje" class="mensaje alert alert-danger container" role="alert"></div>

    
</body>
</html>