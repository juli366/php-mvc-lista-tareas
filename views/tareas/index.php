<?php $filtroActivo = $_GET['filtro'] ?? 'todas'; ?>

<!-- Stats -->
<div class="row g-3 mb-4">
    <?php
    $stats = [
        ['label' => 'Total',       'val' => $conteo['total'],      'icon' => 'bi-list-ul',       'color' => 'primary'],
        ['label' => 'Pendientes',  'val' => $conteo['pendientes'], 'icon' => 'bi-hourglass-split','color' => 'warning'],
        ['label' => 'Completadas', 'val' => $conteo['completadas'],'icon' => 'bi-check-circle',  'color' => 'success'],
    ];
    foreach ($stats as $s):
    ?>
    <div class="col-4">
        <div class="stat-card p-3 text-center">
            <i class="bi <?= $s['icon'] ?> text-<?= $s['color'] ?> fs-4"></i>
            <div class="fw-bold fs-4 mt-1"><?= $s['val'] ?></div>
            <div class="text-muted small"><?= $s['label'] ?></div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7">

        <!-- Formulario nueva tarea -->
        <div class="card app-card mb-4">
            <div class="card-body">
                <form method="POST" action="index.php" class="d-flex gap-2">
                    <input type="hidden" name="accion" value="crear">
                    <input type="text" name="titulo" class="form-control form-control-lg rounded-pill"
                           placeholder="Escribe una nueva tarea..." maxlength="200" required
                           autofocus>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 text-nowrap">
                        <i class="bi bi-plus-lg me-1"></i>Agregar
                    </button>
                </form>
            </div>
        </div>

        <!-- Filtros -->
        <div class="d-flex gap-2 mb-3">
            <?php foreach (['todas' => 'Todas', 'pendientes' => 'Pendientes', 'completadas' => 'Completadas'] as $k => $v): ?>
            <a href="index.php?filtro=<?= $k ?>"
               class="btn btn-outline-secondary btn-sm rounded-pill filtro-btn <?= $filtroActivo === $k ? 'active' : '' ?>">
                <?= $v ?>
            </a>
            <?php endforeach; ?>
        </div>

        <!-- Lista de tareas -->
        <div class="card app-card">
            <div class="card-body p-0">

                <?php if (empty($tareas)): ?>
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-inbox" style="font-size:3.5rem; opacity:.2;"></i>
                    <p class="mt-3 mb-0">
                        <?= $filtroActivo === 'completadas' ? 'No hay tareas completadas aún.' : 'No hay tareas. ¡Agrega una!' ?>
                    </p>
                </div>
                <?php else: ?>
                <ul class="list-unstyled mb-0">
                    <?php foreach ($tareas as $i => $tarea): ?>
                    <li class="tarea-item <?= $tarea['completada'] ? 'completada' : '' ?>
                                d-flex align-items-center gap-3 px-4 py-3
                                <?= $i > 0 ? 'border-top' : '' ?>">

                        <!-- Toggle completar -->
                        <form method="POST" action="index.php" class="mb-0">
                            <input type="hidden" name="accion" value="toggle">
                            <input type="hidden" name="id" value="<?= $tarea['id'] ?>">
                            <?php if (isset($_GET['filtro'])): ?>
                            <input type="hidden" name="filtro" value="<?= htmlspecialchars($_GET['filtro']) ?>">
                            <?php endif; ?>
                            <button type="submit" class="btn-check-circle <?= $tarea['completada'] ? 'checked' : '' ?>"
                                    title="<?= $tarea['completada'] ? 'Marcar pendiente' : 'Marcar completada' ?>">
                                <?php if ($tarea['completada']): ?>
                                    <i class="bi bi-check-lg" style="font-size:.8rem;"></i>
                                <?php endif; ?>
                            </button>
                        </form>

                        <!-- Título -->
                        <div class="flex-grow-1">
                            <span class="tarea-titulo <?= $tarea['completada'] ? 'completada' : 'fw-semibold' ?>">
                                <?= htmlspecialchars($tarea['titulo']) ?>
                            </span>
                            <div class="text-muted small mt-1">
                                <i class="bi bi-clock me-1"></i><?= $tarea['creada_en'] ?>
                            </div>
                        </div>

                        <!-- Eliminar -->
                        <form method="POST" action="index.php" class="mb-0">
                            <input type="hidden" name="accion" value="eliminar">
                            <input type="hidden" name="id" value="<?= $tarea['id'] ?>">
                            <button type="submit" class="btn btn-link text-danger p-0"
                                    title="Eliminar"
                                    onclick="return confirm('¿Eliminar esta tarea?')">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </form>

                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>

            </div>
        </div>

        <!-- Barra de progreso -->
        <?php if ($conteo['total'] > 0): ?>
        <?php $pct = round(($conteo['completadas'] / $conteo['total']) * 100); ?>
        <div class="mt-3 px-1">
            <div class="d-flex justify-content-between small text-muted mb-1">
                <span>Progreso</span>
                <span><?= $pct ?>% completado</span>
            </div>
            <div class="progress" style="height:8px; border-radius:10px;">
                <div class="progress-bar bg-success" style="width:<?= $pct ?>%; border-radius:10px;
                     transition: width .5s ease;"></div>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>
