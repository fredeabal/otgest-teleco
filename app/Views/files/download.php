        <div class="col-md-8 col-lg-6 col-xxl-3 auth-card">
            <div class="card mb-0">
                <div class="card-body">
                    <a href="<?= base_url() ?>" class="text-nowrap logo-img text-center d-block mb-5 w-100">
                  <img src="<?= base_url('assets/images/logos/dark-logo.svg') ?>" class="dark-logo" alt="Logo-Dark" />
                  <img src="<?= base_url('assets/images/logos/light-logo.svg') ?>" class="light-logo" alt="Logo-light" />
                </a>
                    

                    <?php if ($requiresPassword): ?>
                        <!-- =====================================================================
                             FORMULARIO DE DESBLOQUEO POR CONTRASEÑA
                             ===================================================================== -->
                        <h3 class="fw-bold mb-2 text-center text-primary">Archivo Protegido</h3>
                        <p class="text-muted mb-4 fs-3 text-center">Introduce la contraseña configurada para ver y descargar este archivo.</p>

                        <form action="<?= base_url('s/' . $share->slug . '/verify') ?>" method="POST" id="verify-password-form">
                            <?= csrf_field() ?>
                            <div class="mb-4">
                                <div class="input-group">
                                    <input type="password" class="form-control text-center" name="password" id="password" placeholder="Contraseña del archivo">
                                    <button class="btn bg-transparent border text-muted" type="button" id="toggle-password">
                                        <i class="ti ti-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-8 mb-4 rounded-2 d-flex align-items-center justify-content-center gap-2">
                                <i class="ti ti-lock-open fs-5"></i> Desbloquear Archivo
                            </button>
                        </form>

                    <?php else: ?>
                        <!-- =====================================================================
                             DESCARGA DE ARCHIVO DISPONIBLE
                             ===================================================================== -->
                        <h3 class="fw-bold mb-1 text-truncate text-center text-primary" style="max-width: 100%;"><?= esc($share->filename) ?></h3>
                        <p class="text-muted mb-4 text-center">Tamaño: <span class="fw-semibold text-dark"><?= esc($fileSizeFormatted) ?></span></p>

                         <div class="p-3 bg-light-primary rounded mb-4 text-start">
                            <div class="row fs-2">
                                <div class="col-6 mb-3">
                                    <span class="fw-semibold d-block text-dark">Tipo de archivo:</span>
                                    <span class="text-muted"><?= esc(strtoupper(pathinfo($share->filename, PATHINFO_EXTENSION) ?: 'ARCHIVO')) ?></span>
                                </div>
                                <div class="col-6 mb-3">
                                    <span class="fw-semibold d-block text-dark">Límite de descargas:</span>
                                    <span class="text-muted"><?= !empty($share->download_limit) ? esc($share->download_limit) . ' descargas' : 'Ilimitado' ?></span>
                                </div>
                                <div class="col-6">
                                    <span class="fw-semibold d-block text-dark">Descargas actuales:</span>
                                    <span class="text-muted"><?= esc($share->download_count) ?> descargas</span>
                                </div>
                                <div class="col-6">
                                    <span class="fw-semibold d-block text-dark">Caducidad:</span>
                                    <span class="text-muted"><?= !empty($share->expires_at) ? date('d/m/Y H:i', strtotime($share->expires_at)) : 'Permanente' ?></span>
                                </div>
                                <?php if (!empty($fileHash)): ?>
                                    <div class="col-12 mt-4 pt-3 border-top">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <span class="fw-semibold text-uppercase text-muted" style="letter-spacing: 0.5px;">Firma Digital SHA-256</span>
                                            <button class="btn btn-sm btn-light-primary text-primary d-flex align-items-center gap-1 rounded-pill px-3 shadow-none" 
                                                    onclick="navigator.clipboard.writeText('<?= esc($fileHash) ?>'); window.systemAlert && window.systemAlert.fire({ icon: 'success', title: '¡Completado!', html: '<div class=\'text-center\'>Hash copiado al portapapeles</div>', iconColor: '#10B981', timer: 2000 });" 
                                                    title="Copiar Hash">
                                                <i class="ti ti-copy"></i>
                                                <span>Copiar</span>
                                            </button>
                                        </div>
                                        <div class="bg-body border rounded p-3 text-break font-monospace text-muted fs-3 shadow-sm">
                                            <?= esc($fileHash) ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <a href="<?= base_url('s/' . $share->slug . '/download') ?>" class="btn btn-primary w-100 py-8 mb-4 rounded-2 mt-4 d-flex align-items-center justify-content-center gap-2">
                            <i class="ti ti-download fs-5"></i> Descargar Archivo
                        </a>
                    <?php endif; ?>

                </div>
            </div>
            
            <div class="text-center mt-3 fs-2 text-muted">
                Compartido de forma segura a través de <strong>FileCrew</strong>
            </div>
        </div>


<script>
document.addEventListener("DOMContentLoaded", function() {
    // Lógica para mostrar/ocultar contraseña si el formulario está presente
    const togglePassword = document.getElementById('toggle-password');
    const passwordInput = document.getElementById('password');
    
    if (togglePassword && passwordInput) {
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
    }
});
</script>
