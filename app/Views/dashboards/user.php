<!-- =====================================================================
     CABECERA Y BREADCRUMB (NAVEGACIÓN)
     ===================================================================== -->
<div class="card border shadow-none position-relative overflow-hidden mb-4">
  <div class="card-body px-4 py-3">
    <div class="row align-items-center">
      <div class="col-9">
        <h4 class="fw-semibold mb-8">Panel de Usuario</h4>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <a class="text-muted text-decoration-none" href="<?= base_url() ?>">Inicio</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">Dashboard</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
</div>

<!-- =====================================================================
     TARJETAS DE ESTADÍSTICAS (MÉTRICAS DEL USUARIO)
     ===================================================================== -->
<div class="row">
    <!-- Card Mis Archivos -->
    <div class="col-md-4 mb-4">
        <div class="card border shadow-none h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="card-subtitle text-muted mb-0">Mis Archivos</h6>
                    <div class="bg-primary-subtle text-primary rounded p-2">
                        <i class="ti ti-files fs-6"></i>
                    </div>
                </div>
                <h3 class="card-title mb-0 fw-semibold"><?= esc($filesCount ?? 0) ?></h3>
                <p class="card-text text-muted fs-2 mt-2">Archivos subidos por tu usuario.</p>
            </div>
        </div>
    </div>

    <!-- Card Espacio Utilizado -->
    <div class="col-md-4 mb-4">
        <div class="card border shadow-none h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="card-subtitle text-muted mb-0">Espacio Utilizado</h6>
                    <div class="bg-success-subtle text-success rounded p-2">
                        <i class="ti ti-chart-pie fs-6"></i>
                    </div>
                </div>
                <h3 class="card-title mb-0 fw-semibold"><?= esc($spaceUsed ?? '0 MB') ?></h3>
                <p class="card-text text-muted fs-2 mt-2">De un máximo disponible de 10 GB.</p>
            </div>
        </div>
    </div>

    <!-- Card Descargas de mis Enlaces -->
    <div class="col-md-4 mb-4">
        <div class="card border shadow-none h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="card-subtitle text-muted mb-0">Descargas Recibidas</h6>
                    <div class="bg-info-subtle text-info rounded p-2">
                        <i class="ti ti-download fs-6"></i>
                    </div>
                </div>
                <h3 class="card-title mb-0 fw-semibold"><?= esc($downloadsCount ?? 0) ?></h3>
                <p class="card-text text-muted fs-2 mt-2">Descargas totales de tus archivos compartidos.</p>
            </div>
        </div>
    </div>
</div>
