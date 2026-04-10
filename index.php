<?php
session_start();

require_once './app/controllers/UserController.php';
require_once './app/controllers/TallerController.php';
require_once './app/controllers/AdminController.php';

$page = $_GET['page'] ?? 'login';
$getOption = $_GET['option'] ?? '';
$postOption = $_POST['option'] ?? '';

// ========== RUTAS GET OBTENER DATOS ==========
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($getOption === 'talleres_json') {
        (new TallerController())->getTalleresJson();
        exit;
    }

    if ($getOption === 'solicitudes_json') {
        (new AdminController())->getSolicitudesJson();
        exit;
    }
}

// ========== RUTAS FORMULARIO POST ==========
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($postOption === 'login') {
        (new UserController())->login();
        exit;
    }

    if ($postOption === 'register') {
        (new UserController())->registro();
        exit;
    }

    if ($postOption === 'logout') {
        (new UserController())->logout();
        exit;
    }

    if ($postOption === 'solicitar') {
        (new TallerController())->solicitar();
        exit;
    }

    if ($postOption === 'aprobar') {
        (new AdminController())->aprobar();
        exit;
    }

    if ($postOption === 'rechazar') {
        (new AdminController())->rechazar();
        exit;
    }
}

// ========== RUTAS DE VISTAS ==========
switch ($page) {

    case "talleres":
        $taller = new TallerController();
        $taller->index();
        break;

    case "admin":
        $admin = new AdminController();
        $admin->solicitudes();
        break;

    case "logout":
        $auth = new UserController();
        $auth->logout();
        break;
    case "registro":
        $auth = new UserController();
        $auth->showRegistro();
        break;
    case "login":
    default:
        $auth = new UserController();
        $auth->showLogin();
        break;
}
