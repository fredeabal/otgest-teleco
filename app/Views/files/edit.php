<!-- =====================================================================
     CABECERA Y BREADCRUMB
     ===================================================================== -->
<div class="card border shadow-none position-relative overflow-hidden mb-4">
    <div class="card-body px-4 py-3">
        <div class="row align-items-center">
            <div class="col-12 text-center text-md-start">
                <h4 class="fw-semibold mb-2 mb-md-8">Editar Opciones del Archivo</h4>
                <nav aria-label="breadcrumb" class="d-flex justify-content-center justify-content-md-start">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a class="text-muted text-decoration-none" href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a class="text-muted text-decoration-none" href="<?= base_url('files') ?>">Mis Archivos</a></li>
                        <li class="breadcrumb-item text-muted" aria-current="page">Editar</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

    <!-- =====================================================================
         FORMULARIO DE EDICIÓN
         ===================================================================== -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-4">
                    <form action="<?= base_url('files/update/' . $share->id) ?>" method="POST" enctype="multipart/form-data" id="edit-form">
                        <?= csrf_field() ?>

                        <!-- Zona Drag & Drop para Reemplazar -->
                        <div class="mb-4">
                            <h5 class="fw-semibold mb-3">Reemplazar Archivo Físico (Opcional)</h5>
                            <div class="upload-dropzone" id="dropzone">
                                <div class="upload-icon-wrapper mb-3">
                                    <i class="ti ti-cloud-upload fs-7"></i>
                                </div>
                                <h5 class="fw-semibold mb-2">Arrastra tu nuevo archivo aquí</h5>
                                <p class="text-muted mb-3 fs-3">O si lo prefieres...</p>
                                
                                <!-- Input oculto segun Regla de subida de archivos -->
                                <input type="file" name="uploaded_file" id="uploaded_file" class="d-none">
                                <label for="uploaded_file" class="btn btn-primary cursor-pointer">
                                    Seleccionar nuevo archivo
                                </label>
                            </div>
                            
                            <!-- Bloque de información del archivo NUEVO seleccionado -->
                            <div class="mt-3 d-none" id="file-info-block-new">
                                <div class="p-3 bg-light-primary rounded d-flex align-items-center justify-content-between border border-primary">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="p-2 bg-primary text-white rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="ti ti-file-upload fs-5"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-semibold text-truncate text-primary" id="info-filename" style="max-width: 300px;">nombre_archivo.zip</h6>
                                            <small class="text-muted" id="info-filesize">0 KB</small>
                                            <span class="badge bg-primary ms-2">Reemplazará al actual</span>
                                        </div>
                                    </div>
                                    <button type="button" class="btn-close" id="btn-clear-file" aria-label="Limpiar"></button>
                                </div>
                            </div>
                        </div>

                        <!-- Bloque de información del archivo existente -->
                        <div class="mb-4">
                            <h5 class="fw-semibold mb-3">Archivo Seleccionado</h5>
                            <div class="p-3 bg-light-primary rounded d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="p-2 bg-primary text-white rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="ti ti-file-description fs-5"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-semibold text-truncate" style="max-width: 300px;"><?= esc($share->filename) ?></h6>
                                        <small class="text-muted">
                                            <?php
                                                $bytes = $share->file_size;
                                                $units = ['B', 'KB', 'MB', 'GB'];
                                                $bytes = max($bytes, 0);
                                                $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
                                                $pow = min($pow, count($units) - 1);
                                                $bytes /= pow(1024, $pow);
                                                echo round($bytes, 2) . ' ' . $units[$pow];
                                            ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Opciones de Compartición -->
                        <div class="row mb-4">
                            <!-- Límite de descargas -->
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="download_limit" class="form-label fw-semibold">Límite máximo de descargas (Opcional)</label>
                                <input type="number" class="form-control" name="download_limit" id="download_limit" min="1" placeholder="Dejar vacío para ilimitado" value="<?= esc($share->download_limit ?? '') ?>">
                                <?php if (!empty($share->download_count)): ?>
                                    <small class="text-muted d-block mt-1">Descargas actuales: <?= $share->download_count ?></small>
                                <?php endif; ?>
                            </div>

                            <!-- Fecha de expiración -->
                            <div class="col-md-6">
                                <label for="expires_at" class="form-label fw-semibold">Fecha de Caducidad (Opcional)</label>
                                <div class="input-group datepicker">
                                    <input type="text" class="form-control" name="expires_at" id="expires_at" placeholder="Seleccionar fecha y hora" data-input value="<?= !empty($share->expires_at) ? date('Y-m-d H:i', strtotime($share->expires_at)) : '' ?>">
                                    <button class="btn bg-transparent border text-muted" type="button" data-toggle>
                                        <i class="ti ti-calendar"></i>
                                    </button>
                                </div>
                                <?php if (!empty($share->expires_at)): ?>
                                    <small class="text-muted d-block mt-1">
                                        Vence el: <?= date('d/m/Y H:i', strtotime($share->expires_at)) ?>
                                    </small>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Autodestrucción -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="auto_destroy" name="auto_destroy" value="1" <?= $share->auto_destroy ? 'checked' : '' ?> onchange="document.getElementById('auto_destroy_warning').classList.toggle('d-none', !this.checked)">
                                    <label class="form-check-label fw-semibold" for="auto_destroy">Autodestrucción</label>
                                </div>
                                <div id="auto_destroy_warning" class="alert alert-primary mt-4 border border-primary <?= $share->auto_destroy ? '' : 'd-none' ?> p-2 small" role="alert">
                                    El archivo se borrará físicamente del servidor al caducar o alcanzar su límite.
                                </div>
                            </div>
                        </div>

                        <!-- Botón de Envío -->
                        <div class="d-flex justify-content-center mt-4">
                            <a href="<?= base_url('files') ?>" class="btn btn-danger px-4 me-2">
                                <i class="ti ti-x me-1"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="ti ti-device-floppy me-1"></i>Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const dropzone = document.getElementById('dropzone');
    const fileInput = document.getElementById('uploaded_file');
    const fileInfoBlockNew = document.getElementById('file-info-block-new');
    const infoFilename = document.getElementById('info-filename');
    const infoFilesize = document.getElementById('info-filesize');
    const btnClearFile = document.getElementById('btn-clear-file');

    if (fileInput) {
        // 1. Mostrar detalles del archivo al seleccionarse
        fileInput.addEventListener('change', function() {
            if (fileInput.files.length > 0) {
                const file = fileInput.files[0];
                infoFilename.textContent = file.name;
                infoFilesize.textContent = formatBytes(file.size);
                fileInfoBlockNew.classList.remove('d-none');
            } else {
                fileInfoBlockNew.classList.add('d-none');
            }
        });

        // 2. Limpiar el archivo seleccionado
        btnClearFile.addEventListener('click', function() {
            fileInput.value = '';
            fileInfoBlockNew.classList.add('d-none');
        });

        // 3. Drag and Drop events
        if (dropzone) {
            ['dragenter', 'dragover'].forEach(eventName => {
                dropzone.addEventListener(eventName, function(e) {
                    e.preventDefault();
                    dropzone.classList.add('dragover');
                }, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, function(e) {
                    e.preventDefault();
                    dropzone.classList.remove('dragover');
                }, false);
            });

            dropzone.addEventListener('drop', function(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                if (files.length > 0) {
                    fileInput.files = files;
                    // Disparar evento change manualmente
                    fileInput.dispatchEvent(new Event('change'));
                }
            }, false);
        }
    }

    // Helper para formatear bytes en JS
    function formatBytes(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

});
</script>
