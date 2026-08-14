<!-- =====================================================================
     CABECERA Y BREADCRUMB (NAVEGACIÓN)
     ===================================================================== -->
<div class="card border shadow-none position-relative overflow-hidden mb-4">
  <div class="card-body px-4 py-3">
    <div class="row align-items-center">
      <div class="col-12 col-md-8 text-center text-md-start">
        <h4 class="fw-semibold mb-2 mb-md-8">Mis Tareas</h4>
        <nav aria-label="breadcrumb" class="d-flex justify-content-center justify-content-md-start">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">
              <a class="text-muted text-decoration-none" href="<?= base_url() ?>">Inicio</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">Lista de Tareas</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
</div>

<!-- =====================================================================
     FORMULARIO DE NUEVA TAREA
     ===================================================================== -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body p-4">
                <h5 class="card-title fw-semibold mb-3">Nueva Tarea</h5>
                <form action="<?= site_url('todos/store') ?>" method="POST" autocomplete="off">
                    <?= csrf_field() ?>
                    <div class="input-group">
                        <input type="text" name="todo_title" id="todo_title" class="form-control py-2 ps-3" placeholder="¿Qué tienes pendiente?..." value="<?= old('todo_title') ?>">
                        <button type="submit" class="btn btn-primary d-flex align-items-center gap-1 px-4">
                            <i class="ti ti-plus fs-5"></i>
                            <span>Añadir</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- =====================================================================
     LISTADO DE TAREAS
     ===================================================================== -->
<div class="row">
    <!-- Lista de Tareas -->
    <div class="col-12">
        <div class="card">
            <div class="card-body p-4">
                <h5 class="card-title fw-semibold mb-4 d-flex align-items-center gap-2">
                    <i class="ti ti-list-check text-primary fs-5"></i>
                    <span>Tareas Pendientes</span>
                    <span class="badge bg-primary-subtle text-primary ms-auto rounded-circle font-size-12 px-2 py-1"><?= count($pendingTodos) ?></span>
                </h5>

                <?php if (empty($pendingTodos)): ?>
                    <div class="text-center py-5">
                        <i class="ti ti-confetti text-muted display-6 mb-3 d-block"></i>
                        <span class="text-muted">¡Buen trabajo! No tienes tareas pendientes.</span>
                    </div>
                <?php else: ?>
                    <div class="todo-list-container">
                        <?php foreach ($pendingTodos as $todo): ?>
                            <div class="todo-item d-flex align-items-center justify-content-between p-3 mb-2 rounded border">
                                <div class="d-flex align-items-center gap-3 flex-grow-1">
                                    <form action="<?= site_url('todos/toggle/' . $todo['todo_id']) ?>" method="POST" class="m-0">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn-todo-toggle border-0 bg-transparent p-0 text-muted" title="Marcar como completada">
                                            <i class="ti ti-square fs-6"></i>
                                        </button>
                                    </form>
                                    <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center">
                                        <span class="todo-text text-body"><?= esc($todo['todo_title']) ?></span>
                                        <small class="text-muted ms-sm-2 todo-date-small mt-1 mt-sm-0"><?= date('d/m/y', strtotime($todo['created_at'])) ?></small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
