<!-- =====================================================================
     CABECERA Y BREADCRUMB (NAVEGACIÓN)
     ===================================================================== -->
<div class="card border shadow-none position-relative overflow-hidden mb-4">
  <div class="card-body px-4 py-3">
    <div class="row align-items-center">
      <div class="col-9">
        <h4 class="fw-semibold mb-8">Panel de Administración</h4>
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
     TARJETAS DE ESTADÍSTICAS (MÉTRICAS GLOBALES)
     ===================================================================== -->
<div class="row">
    <!-- Card Usuarios Registrados -->
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card border shadow-none h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="card-subtitle text-muted mb-0">Usuarios Registrados</h6>
                    <div class="bg-primary-subtle text-primary rounded p-2">
                        <i class="ti ti-users fs-6"></i>
                    </div>
                </div>
                <h3 class="card-title mb-0 fw-semibold"><?= esc($totalUsers ?? 0) ?></h3>
                <p class="card-text text-muted fs-2 mt-2">Usuarios totales en la plataforma.</p>
            </div>
        </div>
    </div>

    <!-- Card Archivos Compartidos -->
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card border shadow-none h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="card-subtitle text-muted mb-0">Archivos Compartidos</h6>
                    <div class="bg-success-subtle text-success rounded p-2">
                        <i class="ti ti-file-upload fs-6"></i>
                    </div>
                </div>
                <h3 class="card-title mb-0 fw-semibold"><?= esc($totalFiles ?? 0) ?></h3>
                <p class="card-text text-muted fs-2 mt-2">Archivos totales subidos a la plataforma.</p>
            </div>
        </div>
    </div>

    <!-- Card Descargas Totales -->
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card border shadow-none h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="card-subtitle text-muted mb-0">Descargas Totales</h6>
                    <div class="bg-info-subtle text-info rounded p-2">
                        <i class="ti ti-download fs-6"></i>
                    </div>
                </div>
                <h3 class="card-title mb-0 fw-semibold"><?= esc($totalDownloads ?? 0) ?></h3>
                <p class="card-text text-muted fs-2 mt-2">Descargas acumuladas de enlaces de compartición.</p>
            </div>
        </div>
    </div>
</div>


