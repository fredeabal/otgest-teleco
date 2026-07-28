<!-- =====================================================================
     CABECERA Y BREADCRUMB
     ===================================================================== -->
<div class="card border shadow-none position-relative overflow-hidden mb-4">
    <div class="card-body px-4 py-3">
        <div class="row align-items-center">
            <div class="col-12 text-center text-md-start">
                <h4 class="fw-semibold mb-2 mb-md-8">Compartir Archivo</h4>
                <nav aria-label="breadcrumb" class="d-flex justify-content-center justify-content-md-start">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a class="text-muted text-decoration-none" href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a class="text-muted text-decoration-none" href="<?= base_url('files') ?>">Mis Archivos</a></li>
                        <li class="breadcrumb-item text-muted" aria-current="page">Subir</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

    <!-- =====================================================================
         FORMULARIO DE SUBIDA
         ===================================================================== -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-4">
                    <form action="<?= base_url('files/store') ?>" method="POST" enctype="multipart/form-data" id="upload-form">
                        <?= csrf_field() ?>

                        <!-- Zona Drag & Drop -->
                        <div class="mb-4">
                            <div class="upload-dropzone" id="dropzone">
                                <div class="upload-icon-wrapper mb-3">
                                    <i class="ti ti-cloud-upload fs-7"></i>
                                </div>
                                <h5 class="fw-semibold mb-2">Arrastra tu archivo aquí</h5>
                                <p class="text-muted mb-3 fs-3">O si lo prefieres...</p>
                                
                                <!-- Input oculto segun Regla de subida de archivos -->
                                <input type="file" name="uploaded_file" id="uploaded_file" class="d-none">
                                <label for="uploaded_file" class="btn btn-primary cursor-pointer">
                                    Seleccionar archivo
                                </label>
                            </div>
                            
                            <!-- Bloque de información del archivo seleccionado -->
                            <div class="mt-3 d-none" id="file-info-block">
                                <div class="p-3 bg-light-primary rounded d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="p-2 bg-primary text-white rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="ti ti-file-description fs-5"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-semibold text-truncate" id="info-filename" style="max-width: 300px;">nombre_archivo.zip</h6>
                                            <small class="text-muted" id="info-filesize">0 KB</small>
                                        </div>
                                    </div>
                                    <button type="button" class="btn-close" id="btn-clear-file" aria-label="Limpiar"></button>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Ajustes de Seguridad y Expiración -->
                        <h5 class="fw-semibold mb-3">Opciones de Compartición</h5>
                        
                        <div class="row mb-3">
                            <!-- Visibilidad Pública/Privada -->
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="form-label fw-semibold">Privacidad</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input switch-custom-size" type="checkbox" name="is_public" id="is_public" value="1" checked>
                                    <label class="form-check-label ms-2 cursor-pointer" for="is_public" id="privacy-label">Enlace Público (cualquiera con el link accede)</label>
                                </div>
                            </div>

                            <!-- Contraseña -->
                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold">Contraseña de acceso (Opcional)</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="password" id="password" placeholder="Establecer contraseña">
                                    <button class="btn bg-transparent border text-muted" type="button" id="toggle-password">
                                        <i class="ti ti-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <!-- Límite de descargas -->
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="download_limit" class="form-label fw-semibold">Límite máximo de descargas (Opcional)</label>
                                <input type="number" class="form-control" name="download_limit" id="download_limit" min="1" placeholder="Dejar vacío para ilimitado">
                            </div>

                            <!-- Caducidad en fecha y hora -->
                            <div class="col-md-6">
                                <label for="expires_at" class="form-label fw-semibold">Fecha de Caducidad (Opcional)</label>
                                <input type="datetime-local" class="form-control" name="expires_at" id="expires_at">
                            </div>
                        </div>

                        <!-- Botón de Envío -->
                        <div class="d-flex justify-content-center mt-4">
                            <a href="<?= base_url('files') ?>" class="btn btn-danger px-4 me-2">
                                <i class="ti ti-x me-1"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="ti ti-upload me-1"></i>Subir y Generar Enlace
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
    const fileInfoBlock = document.getElementById('file-info-block');
    const infoFilename = document.getElementById('info-filename');
    const infoFilesize = document.getElementById('info-filesize');
    const btnClearFile = document.getElementById('btn-clear-file');
    const form = document.getElementById('upload-form');

    // 1. Mostrar detalles del archivo al seleccionarse
    fileInput.addEventListener('change', function() {
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            infoFilename.textContent = file.name;
            infoFilesize.textContent = formatBytes(file.size);
            fileInfoBlock.classList.remove('d-none');
        } else {
            fileInfoBlock.classList.add('d-none');
        }
    });

    // 2. Limpiar el archivo seleccionado
    btnClearFile.addEventListener('click', function() {
        fileInput.value = '';
        fileInfoBlock.classList.add('d-none');
    });

    // 3. Drag and Drop events
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

    // 4. Mostrar/Ocultar contraseña
    const togglePassword = document.getElementById('toggle-password');
    const passwordInput = document.getElementById('password');
    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        const icon = this.querySelector('i');
        if (type === 'text') {
            icon.classList.remove('ti-eye');
            icon.classList.add('ti-eye-off');
        } else {
            icon.classList.remove('ti-eye-off');
            icon.classList.add('ti-eye');
        }
    });

    // 5. Actualizar la etiqueta del switch de privacidad
    const isPublicSwitch = document.getElementById('is_public');
    const privacyLabel = document.getElementById('privacy-label');
    isPublicSwitch.addEventListener('change', function() {
        if (this.checked) {
            privacyLabel.textContent = 'Enlace Público (cualquiera con el link accede)';
        } else {
            privacyLabel.textContent = 'Enlace Privado (requiere iniciar sesión en FileCrew)';
        }
    });

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
