<?php
require_once __DIR__ . '/../models/TareaModel.php';

class TareasController {
    private TareaModel $model;

    public function __construct() {
        $this->model = new TareaModel();
    }

    public function index(): void {
        $pageTitle    = 'Lista de Tareas';
        $pageSubtitle = 'Organiza y gestiona tus tareas del día';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $accion = $_POST['accion'] ?? '';

            if ($accion === 'crear' && !empty(trim($_POST['titulo'] ?? ''))) {
                $this->model->crear($_POST['titulo']);
            } elseif ($accion === 'toggle' && isset($_POST['id'])) {
                $this->model->toggleCompletar((int) $_POST['id']);
            } elseif ($accion === 'eliminar' && isset($_POST['id'])) {
                $this->model->eliminar((int) $_POST['id']);
            }

            header('Location: index.php' . (isset($_GET['filtro']) ? '?filtro=' . $_GET['filtro'] : ''));
            exit;
        }

        $filtro = $_GET['filtro'] ?? null;
        $tareas = $this->model->todas($filtro);
        $conteo = $this->model->conteo();

        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/tareas/index.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }
}
