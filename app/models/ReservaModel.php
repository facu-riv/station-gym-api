<?php

require_once __DIR__ . '/../config.php';

class ReservaModel {
    private PDO $db;

    public function __construct() {
        $this->db = DB::getConnection();
    }

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("
            SELECT id, actividad_id, nombre_cliente, email_cliente, fecha_reserva
            FROM reservas
            WHERE id = ?
        ");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function getByActividad(int $actividadId): array {
        $stmt = $this->db->prepare("
            SELECT id, actividad_id, nombre_cliente, email_cliente, fecha_reserva
            FROM reservas
            WHERE actividad_id = ?
            ORDER BY fecha_reserva ASC
        ");
        $stmt->execute([$actividadId]);
        return $stmt->fetchAll();
    }

    public function create(array $data): array {
        $stmt = $this->db->prepare("
            INSERT INTO reservas (actividad_id, nombre_cliente, email_cliente, fecha_reserva)
            VALUES (:actividad_id, :nombre_cliente, :email_cliente, :fecha_reserva)
        ");
        $stmt->execute([
            ':actividad_id'   => $data['actividad_id'],
            ':nombre_cliente' => $data['nombre_cliente'],
            ':email_cliente'  => $data['email_cliente'] ?? null,
            ':fecha_reserva'  => $data['fecha_reserva'],
        ]);

        $id = (int)$this->db->lastInsertId();
        return $this->getById($id);
    }

    public function update(int $id, array $data): ?array {
        $stmt = $this->db->prepare("
            UPDATE reservas
            SET nombre_cliente = :nombre_cliente,
                email_cliente  = :email_cliente,
                fecha_reserva  = :fecha_reserva
            WHERE id = :id
        ");
        $stmt->execute([
            ':nombre_cliente' => $data['nombre_cliente'],
            ':email_cliente'  => $data['email_cliente'] ?? null,
            ':fecha_reserva'  => $data['fecha_reserva'],
            ':id'             => $id,
        ]);

        if ($stmt->rowCount() === 0) {
            return null;
        }
        return $this->getById($id);
    }
}