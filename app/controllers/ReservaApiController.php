<?php

require_once __DIR__ . '/../models/ReservaModel.php';
require_once __DIR__ . '/../models/ActividadModel.php';

class ReservaApiController {
    private ReservaModel $reservaModel;
    private ActividadModel $actividadModel;

    public function __construct() {
        $this->reservaModel   = new ReservaModel();
        $this->actividadModel = new ActividadModel();
    }

    public function getOne(int $id): void {
        $reserva = $this->reservaModel->getById($id);
        if (!$reserva) {
            send_json(['error' => 'Reserva no encontrada'], 404);
        }
        send_json($reserva, 200);
    }

    public function create(): void {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!is_array($input)) {
            send_json(['error' => 'JSON inválido'], 400);
        }

        if (empty($input['actividad_id']) ||
            empty($input['nombre_cliente']) ||
            empty($input['fecha_reserva'])) {
            send_json(['error' => 'actividad_id, nombre_cliente y fecha_reserva son obligatorios'], 400);
        }

        $actividadId = (int)$input['actividad_id'];

        if (!$this->actividadModel->exists($actividadId)) {
            send_json(['error' => 'actividad_id inexistente'], 400);
        }

        $reserva = $this->reservaModel->create([
            'actividad_id'   => $actividadId,
            'nombre_cliente' => trim($input['nombre_cliente']),
            'email_cliente'  => $input['email_cliente'] ?? null,
            'fecha_reserva'  => $input['fecha_reserva'],
        ]);

        send_json($reserva, 201);
    }

    public function update(int $id): void {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!is_array($input)) {
            send_json(['error' => 'JSON inválido'], 400);
        }

        if (empty($input['nombre_cliente']) ||
            empty($input['fecha_reserva'])) {
            send_json(['error' => 'nombre_cliente y fecha_reserva son obligatorios'], 400);
        }

        if (!$this->reservaModel->getById($id)) {
            send_json(['error' => 'Reserva no encontrada'], 404);
        }

        $reserva = $this->reservaModel->update($id, [
            'nombre_cliente' => trim($input['nombre_cliente']),
            'email_cliente'  => $input['email_cliente'] ?? null,
            'fecha_reserva'  => $input['fecha_reserva'],
        ]);

        if (!$reserva) {
            send_json(['error' => 'No se pudo actualizar la reserva'], 400);
        }

        send_json($reserva, 200);
    }
}