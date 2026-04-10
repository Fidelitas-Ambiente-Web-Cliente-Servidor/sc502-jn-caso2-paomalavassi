<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Solicitud.php';
require_once __DIR__ . '/../models/Taller.php';

class AdminController
{
    private $db;
    private $solicitudModel;
    private $tallerModel;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
        $this->solicitudModel = new Solicitud($this->db);
        $this->tallerModel = new Taller($this->db);
    }

    public function solicitudes()
    {
        if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'admin') {
            header('Location: index.php?page=login');
            return;
        }
        require __DIR__ . '/../views/admin/solicitudes.php';
    }
    
    public function getSolicitudesJson()
    {
        header('Content-Type: application/json');
        if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'admin') {
            echo json_encode(['response' => '01', 'message' => 'No autorizado', 'solicitudes' => []]);
            return;
        }
        try {
            $solicitudes = $this->solicitudModel->getPendientesDetalle();
            echo json_encode(['response' => '00', 'solicitudes' => $solicitudes]);
        } catch (Exception $e) {
            echo json_encode(['response' => '01', 'message' => 'Error al cargar solicitudes', 'error' => $e->getMessage()]);
        }
    }
    
    // Aprobar solicitud
    public function aprobar()
    {
        header('Content-Type: application/json');
        if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'admin') {
            echo json_encode(['success' => false, 'error' => 'No autorizado']);
            return;
        }
        
        $solicitudId = $_POST['id_solicitud'] ?? 0;
        
        if (!$solicitudId) {
            echo json_encode(['response' => '01', 'message' => 'ID de solicitud inválido']);
            return;
        }
        
        try {
            $solicitud = $this->solicitudModel->findById($solicitudId);
            if (!$solicitud) {
                echo json_encode(['response' => '01', 'message' => 'Solicitud no encontrada']);
                return;
            }
            
            if ($this->solicitudModel->aprobar($solicitudId)) {
                $this->tallerModel->descontarCupo($solicitud['taller_id']);
                echo json_encode(['response' => '00', 'message' => 'Solicitud aprobada exitosamente']);
            } else {
                echo json_encode(['response' => '01', 'message' => 'Error al aprobar la solicitud']);
            }
        } catch (Exception $e) {
            if (isset($db) && $db->errno === 0) {
                $db->rollback();
            }
            echo json_encode(['response' => '01', 'message' => 'Error al aprobar la solicitud']);
        }
    }
    
    public function rechazar()
    {
        header('Content-Type: application/json');
        if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'admin') {
            echo json_encode(['success' => false, 'error' => 'No autorizado']);
            return;
        }
        
        $solicitudId = $_POST['id_solicitud'] ?? 0;
        
        if ($this->solicitudModel->rechazar($solicitudId)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al rechazar']);
        }
    }
}