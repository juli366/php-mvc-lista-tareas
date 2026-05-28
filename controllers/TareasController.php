<?php
require_once __DIR__ . '/../models/TareaModel.php';

class TareasController {
    private TareaModel $model;

    public function __construct() {
        $this->model = new TareaModel();
    }

    public function index(): void {
        $pageTitle    = 'Lista de Tareas';
        $pageSubtitle = 'Tablero Kanban — arrastra las tarjetas entre columnas';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'crear') {
            if (!empty(trim($_POST['titulo'] ?? ''))) {
                $this->model->crear($_POST['titulo']);
            }
            header('Location: index.php');
            exit;
        }

        $columnas = $this->model->porEstado();
        $conteo   = $this->model->conteo();

        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/tareas/index.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    public function actualizarEstado(): void {
        header('Content-Type: application/json');
        $id     = intval($_POST['id']     ?? 0);
        $estado = trim($_POST['estado']   ?? '');
        if ($id && $estado) {
            $this->model->actualizarEstado($id, $estado);
            echo json_encode(['ok' => true]);
        } else {
            http_response_code(400);
            echo json_encode(['ok' => false]);
        }
        exit;
    }

    public function eliminar(): void {
        header('Content-Type: application/json');
        $id = intval($_POST['id'] ?? 0);
        if ($id) {
            $this->model->eliminar($id);
            echo json_encode(['ok' => true]);
        } else {
            http_response_code(400);
            echo json_encode(['ok' => false]);
        }
        exit;
    }
}
