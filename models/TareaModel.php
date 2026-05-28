<?php
class TareaModel {
    private PDO $db;

    public function __construct() {
        $dbPath   = __DIR__ . '/../db/tareas.sqlite';
        $this->db = new PDO('sqlite:' . $dbPath);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->crearTabla();
    }

    private function crearTabla(): void {
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS tareas (
                id        INTEGER PRIMARY KEY AUTOINCREMENT,
                titulo    TEXT    NOT NULL,
                estado    TEXT    NOT NULL DEFAULT 'pendiente',
                creada_en TEXT    NOT NULL DEFAULT (datetime('now','localtime'))
            )
        ");
    }

    public function porEstado(): array {
        $filas = $this->db->query("SELECT * FROM tareas ORDER BY id DESC")->fetchAll();
        $cols  = ['pendiente' => [], 'en_progreso' => [], 'completada' => []];
        foreach ($filas as $f) {
            $cols[$f['estado']][] = $f;
        }
        return $cols;
    }

    public function crear(string $titulo): void {
        $stmt = $this->db->prepare("INSERT INTO tareas (titulo) VALUES (?)");
        $stmt->execute([trim($titulo)]);
    }

    public function actualizarEstado(int $id, string $estado): void {
        $validos = ['pendiente', 'en_progreso', 'completada'];
        if (!in_array($estado, $validos, true)) return;
        $stmt = $this->db->prepare("UPDATE tareas SET estado = ? WHERE id = ?");
        $stmt->execute([$estado, $id]);
    }

    public function eliminar(int $id): void {
        $stmt = $this->db->prepare("DELETE FROM tareas WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function conteo(): array {
        $rows = $this->db->query("
            SELECT estado, COUNT(*) as n FROM tareas GROUP BY estado
        ")->fetchAll();
        $c = ['pendiente' => 0, 'en_progreso' => 0, 'completada' => 0];
        foreach ($rows as $r) $c[$r['estado']] = $r['n'];
        $c['total'] = array_sum($c);
        return $c;
    }
}
