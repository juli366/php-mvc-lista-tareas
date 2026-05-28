<?php
$cols = [
    'pendiente'   => ['label' => 'Pendiente',    'icon' => 'bi-circle',         'color' => 'secondary', 'bg' => '#f8f9fa'],
    'en_progreso' => ['label' => 'En Progreso',  'icon' => 'bi-arrow-repeat',   'color' => 'warning',   'bg' => '#fffbf0'],
    'completada'  => ['label' => 'Completada',   'icon' => 'bi-check-circle',   'color' => 'success',   'bg' => '#f0fff4'],
];
?>

<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-3">
        <div class="stat-card p-3 text-center">
            <i class="bi bi-kanban text-primary fs-4"></i>
            <div class="fw-bold fs-4 mt-1" id="stat-total"><?= $conteo['total'] ?></div>
            <div class="text-muted small">Total</div>
        </div>
    </div>
    <div class="col-3">
        <div class="stat-card p-3 text-center">
            <i class="bi bi-circle text-secondary fs-4"></i>
            <div class="fw-bold fs-4 mt-1" id="stat-pendiente"><?= $conteo['pendiente'] ?></div>
            <div class="text-muted small">Pendiente</div>
        </div>
    </div>
    <div class="col-3">
        <div class="stat-card p-3 text-center">
            <i class="bi bi-arrow-repeat text-warning fs-4"></i>
            <div class="fw-bold fs-4 mt-1" id="stat-en_progreso"><?= $conteo['en_progreso'] ?></div>
            <div class="text-muted small">En Progreso</div>
        </div>
    </div>
    <div class="col-3">
        <div class="stat-card p-3 text-center">
            <i class="bi bi-check-circle text-success fs-4"></i>
            <div class="fw-bold fs-4 mt-1" id="stat-completada"><?= $conteo['completada'] ?></div>
            <div class="text-muted small">Completada</div>
        </div>
    </div>
</div>

<!-- Formulario nueva tarea -->
<div class="card app-card mb-4">
    <div class="card-body py-3">
        <form method="POST" action="index.php" class="d-flex gap-2">
            <input type="hidden" name="accion" value="crear">
            <input type="text" name="titulo" class="form-control rounded-pill"
                   placeholder="Nueva tarea..." maxlength="200" required autofocus>
            <button type="submit" class="btn btn-primary rounded-pill px-4 text-nowrap">
                <i class="bi bi-plus-lg me-1"></i>Agregar
            </button>
        </form>
    </div>
</div>

<!-- Tablero Kanban -->
<div class="row g-3" id="kanban">
    <?php foreach ($cols as $estado => $col): ?>
    <div class="col-md-4">
        <div class="card app-card h-100" style="border-top: 4px solid var(--bs-<?= $col['color'] ?>);">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span>
                    <i class="bi <?= $col['icon'] ?> text-<?= $col['color'] ?> me-2"></i>
                    <strong><?= $col['label'] ?></strong>
                </span>
                <span class="badge bg-<?= $col['color'] ?> rounded-pill">
                    <?= count($columnas[$estado]) ?>
                </span>
            </div>
            <div class="card-body p-2">
                <div class="kanban-col d-flex flex-column gap-2"
                     data-estado="<?= $estado ?>"
                     style="min-height: 200px;">

                    <?php foreach ($columnas[$estado] as $tarea): ?>
                    <div class="kanban-card" data-id="<?= $tarea['id'] ?>">
                        <div class="d-flex justify-content-between align-items-start gap-2">
                            <span class="fw-semibold small"><?= htmlspecialchars($tarea['titulo']) ?></span>
                            <button class="btn btn-link text-danger p-0 btn-eliminar"
                                    data-id="<?= $tarea['id'] ?>" title="Eliminar"
                                    style="font-size:.85rem; flex-shrink:0; line-height:1;">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                        <div class="text-muted mt-2" style="font-size:.7rem;">
                            <i class="bi bi-clock me-1"></i><?= $tarea['creada_en'] ?>
                        </div>
                    </div>
                    <?php endforeach; ?>

                    <?php if (empty($columnas[$estado])): ?>
                    <div class="kanban-empty text-center text-muted py-4" style="font-size:.85rem;">
                        <i class="bi bi-inbox" style="font-size:1.5rem; opacity:.3;"></i>
                        <div class="mt-1">Sin tareas</div>
                    </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<style>
.kanban-card {
    background: #fff;
    border-radius: 10px;
    padding: 12px 14px;
    box-shadow: 0 2px 8px rgba(0,0,0,.07);
    cursor: grab;
    border-left: 3px solid #dee2e6;
    transition: box-shadow .2s, transform .15s;
    user-select: none;
}
.kanban-card:hover {
    box-shadow: 0 4px 16px rgba(0,0,0,.13);
    transform: translateY(-2px);
}
.kanban-card.sortable-ghost {
    opacity: .35;
    transform: rotate(2deg);
}
.kanban-card.sortable-drag {
    cursor: grabbing;
    box-shadow: 0 8px 30px rgba(0,0,0,.2);
}
.kanban-col.drag-over {
    background: rgba(13,52,96,.05);
    border-radius: 10px;
    outline: 2px dashed #0f3460;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
document.querySelectorAll('.kanban-col').forEach(col => {
    Sortable.create(col, {
        group:     'tareas',
        animation: 150,
        ghostClass:  'sortable-ghost',
        dragClass:   'sortable-drag',
        onAdd(evt) {
            const id     = evt.item.dataset.id;
            const estado = evt.to.dataset.estado;

            actualizarVacios();
            actualizarContadores();

            fetch('index.php?controller=tareas&action=actualizarEstado', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id=${id}&estado=${estado}`
            });
        },
        onStart() {
            document.querySelectorAll('.kanban-col').forEach(c => c.classList.add('drag-over'));
        },
        onEnd() {
            document.querySelectorAll('.kanban-col').forEach(c => c.classList.remove('drag-over'));
        }
    });
});

document.querySelectorAll('.btn-eliminar').forEach(btn => {
    btn.addEventListener('click', () => {
        if (!confirm('¿Eliminar esta tarea?')) return;
        const id   = btn.dataset.id;
        const card = btn.closest('.kanban-card');
        card.style.transition = 'opacity .3s, transform .3s';
        card.style.opacity    = '0';
        card.style.transform  = 'scale(.8)';
        setTimeout(() => {
            card.remove();
            actualizarVacios();
            actualizarContadores();
        }, 300);
        fetch('index.php?controller=tareas&action=eliminar', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${id}`
        });
    });
});

function actualizarVacios() {
    document.querySelectorAll('.kanban-col').forEach(col => {
        const cards = col.querySelectorAll('.kanban-card');
        let empty   = col.querySelector('.kanban-empty');
        if (cards.length === 0) {
            if (!empty) {
                empty = document.createElement('div');
                empty.className = 'kanban-empty text-center text-muted py-4';
                empty.style.fontSize = '.85rem';
                empty.innerHTML = '<i class="bi bi-inbox" style="font-size:1.5rem;opacity:.3;"></i><div class="mt-1">Sin tareas</div>';
                col.appendChild(empty);
            }
        } else if (empty) {
            empty.remove();
        }
    });
}

function actualizarContadores() {
    let total = 0;
    document.querySelectorAll('.kanban-col').forEach(col => {
        const estado = col.dataset.estado;
        const count  = col.querySelectorAll('.kanban-card').length;
        total += count;

        // Badge dentro de la columna Kanban
        const badge = col.closest('.card').querySelector('.badge');
        if (badge) badge.textContent = count;

        // Stat card superior
        const stat = document.getElementById('stat-' + estado);
        if (stat) stat.textContent = count;
    });

    // Total superior
    document.getElementById('stat-total').textContent = total;
}
</script>
