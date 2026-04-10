<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/User.php';

class UserController
{

    private $model;

    public function __construct()
    {
        $database = new Database();
        $db = $database->connect();
        $this->model = new User($db);
    }

    public function showLogin()
    {
        require __DIR__ . '/../views/login.php';
    }

    public function showRegistro()
    {
        require __DIR__ . '/../views/register.php';
    }

    public function login()
    {
        header('Content-Type: application/json');
        $username = strtolower(trim($_POST['username'] ?? ''));
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {
            echo json_encode(['response' => '01', 'message' => 'Debe completar todos los campos']);
            return;
        }

        $user = $this->model->login($username);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['id'] = $user['id'];
            $_SESSION['user'] = $user['username'];
            $_SESSION['rol'] = $user['rol'];

            echo json_encode(['response' => '00', 'rol' => $user['rol'], 'message' => 'Login exitoso']);
        } else {
            echo json_encode(['response' => '01', 'message' => 'Error de autentificacion']);
        }
    }

    public function registro()
    {
        header('Content-Type: application/json');
        $username = strtolower(trim($_POST['username'] ?? ''));
        $passwordRaw = $_POST['password'] ?? '';

        if ($username === '' || $passwordRaw === '') {
            echo json_encode(['response' => '01', 'message' => 'Debe completar todos los campos']);
            return;
        }

        if (!preg_match('/^[a-zA-Z0-9_]{3,50}$/', $username)) {
            echo json_encode(['response' => '01', 'message' => 'Usuario invalido (3-50, letras, numeros y _)']);
            return;
        }

        if (strlen($passwordRaw) < 5) {
            echo json_encode(['response' => '01', 'message' => 'La contrasena debe tener al menos 5 caracteres']);
            return;
        }

        if ($this->model->existsByUsername($username)) {
            echo json_encode(['response' => '01', 'message' => 'El nombre de usuario ya existe']);
            return;
        }

        $password = password_hash($passwordRaw, PASSWORD_DEFAULT);

        $result = $this->model->create($username, $password);

        if ($result) {
            echo json_encode(['response' => '00', 'message' => 'Registro exitoso']);
        } else {
            echo json_encode(['response' => '01', 'message' => 'Error al registrar']);
        }
    }

    public function logout()
    {
        session_unset();
        session_destroy();

        if (($_POST['option'] ?? '') === 'logout') {
            header('Content-Type: application/json');
            echo json_encode(['response' => '00', 'message' => 'Sesion cerrada']);
            return;
        }

        header('Location: index.php?page=login');
        exit;
    }
}
