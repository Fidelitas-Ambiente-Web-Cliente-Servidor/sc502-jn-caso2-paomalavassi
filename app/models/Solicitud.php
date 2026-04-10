<?php
class Solicitud
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function existeActivaPorUsuarioYTaller($usuarioId, $tallerId)
    {
        $query = "SELECT id
                  FROM solicitudes
                  WHERE usuario_id = ?
                    AND taller_id = ?
                    AND estado IN ('pendiente', 'aprobada')
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ii', $usuarioId, $tallerId);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }

    public function crear($tallerId, $usuarioId)
    {
        $query = "INSERT INTO solicitudes (taller_id, usuario_id, estado)
                  VALUES (?, ?, 'pendiente')";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ii', $tallerId, $usuarioId);

        return $stmt->execute();
    }

    public function getPendientesDetalle()
    {
        $query = "SELECT s.id,
                         s.taller_id,
                         s.usuario_id,
                         s.fecha_solicitud,
                         s.estado,
                         t.nombre AS taller,
                         u.username AS usuario
                  FROM solicitudes s
                  INNER JOIN talleres t ON t.id = s.taller_id
                  INNER JOIN usuarios u ON u.id = s.usuario_id
                  WHERE s.estado = 'pendiente'
                  ORDER BY s.fecha_solicitud ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        $solicitudes = [];
        while ($row = $result->fetch_assoc()) {
            $solicitudes[] = $row;
        }

        return $solicitudes;
    }

    public function findById($id)
    {
        $query = "SELECT * FROM solicitudes WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    public function aprobar($id)
    {
        $query = "UPDATE solicitudes
                  SET estado = 'aprobada'
                  WHERE id = ?
                    AND estado = 'pendiente'";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }

    public function rechazar($id)
    {
        $query = "UPDATE solicitudes
                  SET estado = 'rechazada'
                  WHERE id = ?
                    AND estado = 'pendiente'";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }

    public function getEstadoSolicitud($usuarioId, $tallerId)
    {
        $query = "SELECT estado FROM solicitudes 
                  WHERE usuario_id = ? AND taller_id = ?
                  LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ii', $usuarioId, $tallerId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row ? $row['estado'] : null;
    }

}