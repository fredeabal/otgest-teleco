<style>
    .file-name-truncate { max-width: 120px; }
    @media (min-width: 768px) { .file-name-truncate { max-width: 250px; } }
    @media (min-width: 1200px) { .file-name-truncate { max-width: 300px; } }
</style>

<!-- =====================================================================
     CABECERA Y BREADCRUMB
     ===================================================================== -->
<div class="card border shadow-none position-relative overflow-hidden mb-4">
    <div class="card-body px-4 py-3">
        <div class="row align-items-center">
            <div class="col-12 col-md-8 text-center text-md-start">
                <h4 class="fw-semibold mb-2 mb-md-8">Mis Archivos Compartidos</h4>
                <nav aria-label="breadcrumb" class="d-flex justify-content-center justify-content-md-start">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a class="text-muted text-decoration-none" href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item text-muted" aria-current="page">Mis Archivos</li>
                    </ol>
                </nav>
            </div>
            <div class="col-12 col-md-4 d-flex justify-content-center justify-content-md-end align-items-center mt-3 mt-md-0">
                <a href="<?= base_url('files/upload') ?>" class="btn btn-primary border-0 d-flex align-items-center gap-1">
                    <i class="ti ti-upload"></i>
                    <span>Compartir Archivo</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- =====================================================================
     BUSCADOR Y TABLA DE ARCHIVOS
     ===================================================================== -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- Barra de Herramientas superior: Buscador -->
                <div class="d-flex flex-wrap justify-content-end align-items-center mb-4 gap-3">
                    <form action="<?= base_url('files') ?>" method="GET" class="d-flex align-items-center gap-2 w-100 w-md-auto search-form-responsive ms-auto">
                        <div class="position-relative w-100 search-box-container">
                            <input type="text" class="form-control" name="q" placeholder="Buscar por nombre..." value="<?= esc($search ?? '') ?>">
                            <i class="ti ti-search search-icon text-muted"></i>
                        </div>
                    </form>
                </div>

            <!-- Tabla Premium -->
            <div class="table-responsive">
                <table class="table align-middle text-nowrap mb-0" id="files-table">
                    <thead>
                        <tr>
                            <th scope="col">Archivo</th>
                            <th scope="col" class="text-center d-none d-lg-table-cell">Creado</th>
                            <th scope="col" class="text-center d-none d-sm-table-cell">Tamaño</th>
                            <th scope="col" class="text-center d-none d-md-table-cell">Descargas</th>
                            <th scope="col" class="text-center d-none d-lg-table-cell">Expiración</th>
                            <th scope="col" class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($files)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="ti ti-folder-off fs-10 d-block mb-2 text-muted"></i>
                                    <span class="fw-semibold text-muted">No se encontraron archivos compartidos.</span>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($files as $file): ?>
                                <?php 
                                    // Formatear tamaño de archivo en la vista
                                    $size = $file->file_size;
                                    $units = ['B', 'KB', 'MB', 'GB'];
                                    $pow = floor(($size ? log($size) : 0) / log(1024));
                                    $pow = min($pow, count($units) - 1);
                                    $size /= pow(1024, $pow);
                                    $sizeFormatted = round($size, 2) . ' ' . $units[$pow];

                                    // Determinar si ha caducado
                                    $expired = false;
                                    if (!empty($file->expires_at) && strtotime($file->expires_at) < time()) {
                                        $expired = true;
                                    }
                                    if (!empty($file->download_limit) && $file->download_count >= $file->download_limit) {
                                        $expired = true;
                                    }
                                ?>
                                <tr class="cursor-pointer" onclick="window.location='<?= base_url('files/edit/' . $file->id) ?>'">
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="p-2 <?= !empty($file->password) ? 'bg-light-warning text-warning' : 'bg-light-primary text-primary' ?> rounded d-none d-sm-flex align-items-center justify-content-center network-icon-circle">
                                                <i class="ti <?= !empty($file->password) ? 'ti-lock' : 'ti-file-text' ?> fs-6"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-semibold text-truncate file-name-truncate">
                                                    <?= esc($file->filename) ?>
                                                </h6>
                                                <small class="text-muted fw-bold"><?= esc(strtoupper(pathinfo($file->filename, PATHINFO_EXTENSION) ?: 'ARCHIVO')) ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center d-none d-lg-table-cell">
                                        <h6 class="fs-3 fw-semibold mb-0"><?= date('d/m/Y', strtotime($file->created_at)) ?></h6>
                                        <span class="fw-normal text-muted text-login-time"><?= date('H:i', strtotime($file->created_at)) ?></span>
                                    </td>
                                    <td class="text-center d-none d-sm-table-cell"><?= $sizeFormatted ?></td>
                                    <td class="text-center d-none d-md-table-cell">
                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                            <span class="fw-semibold"><?= esc($file->download_count) ?></span>
                                            <span class="text-muted">/</span>
                                            <span class="text-muted"><?= !empty($file->download_limit) ? esc($file->download_limit) : '∞' ?></span>
                                        </div>
                                    </td>
                                    <td class="text-center d-none d-lg-table-cell">
                                        <?php 
                                            $expiresAt = !empty($file->expires_at) ? strtotime($file->expires_at) : null;
                                            
                                            if ($expired): 
                                        ?>
                                            <?php if ($expiresAt): ?>
                                                <h6 class="fs-3 fw-semibold mb-0 text-danger"><?= date('d/m/Y', $expiresAt) ?></h6>
                                                <span class="fw-normal text-danger text-login-time"><?= date('H:i', $expiresAt) ?></span>
                                            <?php else: ?>
                                                <h6 class="fs-3 fw-semibold mb-0 text-danger">Caducado</h6>
                                            <?php endif; ?>
                                        <?php elseif ($expiresAt): ?>
                                            <h6 class="fs-3 fw-semibold mb-0"><?= date('d/m/Y', $expiresAt) ?></h6>
                                            <span class="fw-normal text-muted text-login-time"><?= date('H:i', $expiresAt) ?></span>
                                        <?php else: ?>
                                            <h6 class="fs-3 fw-semibold mb-0 text-muted">Nunca</h6>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end" onclick="event.stopPropagation();">
                                        <div class="dropdown">
                                            <button class="btn btn-sm bg-transparent border-0 text-muted shadow-none p-1" type="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-popper-config='{"strategy":"fixed"}'>
                                                <i class="ti ti-dots-vertical fs-5"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a href="<?= base_url('files/download/' . $file->id) ?>" class="dropdown-item d-flex align-items-center gap-2">
                                                        <i class="ti ti-download"></i> Descargar
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<?= base_url('files/edit/' . $file->id) ?>" class="dropdown-item d-flex align-items-center gap-2">
                                                        <i class="ti ti-pencil"></i> Editar
                                                    </a>
                                                </li>
                                                <li>
                                                    <button type="button" class="dropdown-item d-flex align-items-center gap-2" onclick="copyShareLink('<?= base_url('s/' . $file->slug) ?>')">
                                                        <i class="ti ti-link"></i> Copiar Enlace
                                                    </button>
                                                </li>
                                                <li>
                                                    <button type="button" class="dropdown-item d-flex align-items-center gap-2 btn-send-email" 
                                                            data-id="<?= $file->id ?>" 
                                                            data-filename="<?= esc($file->filename) ?>">
                                                        <i class="ti ti-mail"></i> Enviar por Correo
                                                    </button>
                                                </li>
                                                <li>
                                                    <form action="<?= base_url('files/delete/' . $file->id) ?>" method="POST" class="d-inline" data-confirm="Esta acción borrará físicamente el archivo del servidor y caducará el enlace de compartición.">
                                                        <?= csrf_field() ?>
                                                        <button type="submit" class="dropdown-item d-flex align-items-center gap-2 text-danger w-100 border-0 bg-transparent text-start">
                                                            <i class="ti ti-trash"></i> Eliminar
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="d-flex justify-content-center mt-4">
                <?= $pager->links('files', 'default_full') ?>
            </div>
        </div>
    </div>
</div>
<!-- =====================================================================
     MODAL PARA ENVIAR CORREO
     ===================================================================== -->
<div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="emailModalLabel">Enviar Enlace por Correo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="emailForm" action="" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Archivo seleccionado</label>
                        <input type="text" class="form-control bg-light" id="selected-file-name" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="recipient_email" class="form-label">Correo electrónico del destinatario</label>
                        <input type="email" class="form-control" name="recipient_email" id="recipient_email" placeholder="ejemplo@correo.com">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Enviar Correo</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. Lógica para configurar y abrir el modal de envío de email
    const emailModal = new bootstrap.Modal(document.getElementById('emailModal'));
    const emailForm = document.getElementById('emailForm');
    const selectedFileField = document.getElementById('selected-file-name');
    
    document.querySelectorAll('.btn-send-email').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const filename = this.getAttribute('data-filename');
            
            selectedFileField.value = filename;
            emailForm.action = `<?= base_url('files/send-email') ?>/${id}`;
            emailModal.show();
        });
    });
});

// 3. Copiar enlace al portapapeles
function copyShareLink(url) {
    navigator.clipboard.writeText(url).then(() => {
        if (window.systemAlert) {
            window.systemAlert.fire({
                icon: 'success',
                title: '¡Enlace copiado!',
                html: '<div class="text-center">El enlace de compartición se ha guardado en tu portapapeles.</div>',
                iconColor: '#10B981'
            });
        }
    });
}
</script>
