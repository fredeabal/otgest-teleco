        <div class="col-md-8 col-lg-6 col-xxl-3 auth-card">
            <div class="card mb-0">
                <div class="card-body">
                    <a href="<?= base_url() ?>" class="text-nowrap logo-img text-center d-block mb-5 w-100">
                  <img src="<?= base_url('assets/images/logos/dark-logo.svg') ?>" class="dark-logo" alt="Logo-Dark" />
                  <img src="<?= base_url('assets/images/logos/light-logo.svg') ?>" class="light-logo" alt="Logo-light" />
                </a>
                    

                    <h3 class="fw-bold mb-2 text-danger text-center"><?= esc($title) ?></h3>
                    <p class="text-muted mb-4 fs-3 text-center"><?= esc($message) ?></p>

                        <a href="<?= base_url('/') ?>" class="btn btn-primary w-100 py-8 mb-4 rounded-2">Ir a la página de inicio</a>

                </div>
            </div>
            
            <div class="text-center mt-3 fs-2 text-muted">
                FileCrew - Comparte archivos de manera segura.
            </div>
        </div>

