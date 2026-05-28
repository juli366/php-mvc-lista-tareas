<?php
class TareaModel {
    private PDO $db;

    public function __construct() {
        $dbPath = __DIR__ . '/../db/tareas.sqlite';
        $this->db = new PDO('sqlite:' . $dbPath);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->crearTabla();
    }

    private function crearTabla(): void {
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS tareas (
                id          INTEGER PRIMARY KEY AUTOINCREMENT,
                titulo      TEXT NOT NULL,
                completada  INTEGER NOT NULL DEFAULT 0,
                creada_en   TEXT NOT NULL DEFAULT (datetime('now','localtime'))
            )
        ");
    }

    public function todas(?string $filtro = null): array {
        $sql = "SELECT * FROM tareas";
        if ($filtro === 'pendientes')  $sql .= " WHERE completada = 0";
        if ($filtro === 'completadas') $sql .= " WHERE completada = 1";
        $sql .= " ORDER BY completada ASC, id DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function crear(string $titulo): void {
        $stmt = $this->db->prepare("INSERT INTO tareas (titulo) VALUES (?)");
        $stmt->execute([trim($titulo)]);
    }

    public function toggleCompletar(int $id): void {
        $stmt = $this->db->prepare("UPDATE tareas SET completada = NOT completada WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function eliminar(int $id): void {
        $stmt = $this->db->prepare("DELETE FROM tareas WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function conteo(): array {
        $row = $this->db->query("
            SELECT
                COUNT(*) AS total,
                SUM(completada) AS completadas,
                SUM(1 - completada) AS pendientes
            FROM tareas
        ")->fetch();
        return $row ?: ['total' => 0, 'completadas' => 0, 'pendientes' => 0];
    }
}
