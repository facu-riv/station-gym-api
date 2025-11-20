<?php

require_once __DIR__ . '/../models/ActividadModel.php';
require_once __DIR__ . '/../models/ReservaModel.php';

class ActividadApiController {
    private ActividadModel $actividadModel;
    private ReservaModel $reservaModel;

    public function __construct() {
        $this->actividadModel = new ActividadModel();
        $this->reservaModel   = new ReservaModel();
    }

    public function list(): void {
        $sort  = $_GET['sort']  ?? 'id';
        $order = $_GET['order'] ?? 'asc';

        $actividades = $this->actividadModel->getAll($sort, $order);
        send_json($actividades, 200);
    }

    public function getOne(int $id): void {
        $actividad = $this->actividadModel->getById($id);
        if (!$actividad) {
            send_json(['error' => 'Actividad no encontrada'], 404);
        }
        send_json($actividad, 200);
    }

    public function getReservas(int $actividadId): void {
        if (!$this->actividadModel->exists($actividadId)) {
            send_json(['error' => 'Actividad no encontrada'], 404);
        }

        $reservas = $this->reservaModel->getByActividad($actividadId);
        send_json($reservas, 200);
    }
}