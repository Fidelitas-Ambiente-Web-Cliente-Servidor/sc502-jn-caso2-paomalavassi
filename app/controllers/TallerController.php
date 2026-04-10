<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Taller.php';
require_once __DIR__ . '/../models/Solicitud.php';

class TallerController
{
    private $tallerModel;
    private $solicitudModel;

    public function __construct()
    {
        $database = new Database();
        $db = $database->connect();
        $this->tallerModel = new Taller($db);
        $this->solicitudModel = new Solicitud($db);
    }

    public function index()
    {
        if (!isset($_SESSION['id'])) {
            header('Location: index.php?page=login');
            return;
        }
        require __DIR__ . '/../views/taller/listado.php';
    }
    
    public function getTalleresJson()
    {
        header('Content-Type: application/json');
        if (!isset($_SESSION['id'])) {
            echo json_encode(['response' => '01', 'message' => 'No autorizado', 'talleres' => []]);
            return;
        }
        
        $talleres = $this->tallerModel->getAllDisponibles();
        $usuarioId = $_SESSION['id'];
        
        // Agregar estado de solicitud a cada taller
        foreach ($talleres as &$taller) {
            $taller['estado_solicitud'] = $this->solicitudModel->getEstadoSolicitud($usuarioId, $taller['id']);
        }
        unset($taller);
        
        echo json_encode([
            'response' => '00',
            'rol' => $_SESSION['rol'] ?? 'usuario',
            'talleres' => $talleres
        ]);
    }
    
    public function solicitar()
    {
        header('Content-Type: application/json');
        if (!isset($_SESSION['id'])) {
            echo json_encode(['response' => '01', 'message' => 'Debes iniciar sesion']);
            return;
        }

        if (($_SESSION['rol'] ?? 'usuario') !== 'usuario') {
            echo json_encode(['response' => '01', 'message' => 'Solo los usuarios pueden solicitar talleres']);
            return;
        }
        
        $tallerId = (int)($_POST['taller_id'] ?? 0);
        $usuarioId = $_SESSION['id'];

        if ($tallerId <= 0) {
            echo json_encode(['response' => '01', 'message' => 'Taller invalido']);
            return;
        }

        $taller = $this->tallerModel->getById($tallerId);
        if (!$taller) {
            echo json_encode(['response' => '01', 'message' => 'Taller no encontrado']);
            return;
        }

        if ((int)$taller['cupo_disponible'] <= 0) {
            echo json_encode(['response' => '01', 'message' => 'No hay cupos disponibles']);
            return;
        }

        if ($this->solicitudModel->existeActivaPorUsuarioYTaller($usuarioId, $tallerId)) {
            echo json_encode(['response' => '01', 'message' => 'Ya tienes una solicitud activa para este taller']);
            return;
        }

        if ($this->solicitudModel->crear($tallerId, $usuarioId)) {
            echo json_encode(['response' => '00', 'message' => 'Solicitud enviada correctamente']);
            return;
        }

        echo json_encode(['response' => '01', 'message' => 'No fue posible registrar la solicitud']);

    }
}