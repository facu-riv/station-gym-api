<?php

require_once __DIR__ . '/../config.php';

class ActividadModel {
    private PDO $db;

    public function __construct() {
        $this->db = DB::getConnection();
    }

    public function getAll(string $sort = 'id', string $order = 'asc'): array {
        $allowedSort  = ['id', 'nombre'];
        $allowedOrder = ['asc', 'desc'];

        if (!in_array($sort, $allowedSort, true)) {
            $sort = 'id';
        }

        $order = strtolower($order);
        if (!in_array($order, $allowedOrder, true)) {
            $order = 'asc';
        }

        $sql = "
            SELECT a.*,
                   c.id     AS categoria_id,
                   c.nombre AS categoria
            FROM actividades a
            JOIN categorias c ON a.categoria_id = c.id
            ORDER BY $sort $order
        ";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array {
        $sql = "
            SELECT a.*,
                   c.id     AS categoria_id,
                   c.nombre AS categoria
            FROM actividades a
            JOIN categorias c ON a.categoria_id = c.id
            WHERE a.id = ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function exists(int $id): bool {
        $stmt = $this->db->prepare("SELECT 1 FROM actividades WHERE id = ?");
        $stmt->execute([$id]);
        return (bool) $stmt->fetchColumn();
    }
}