<div class="container-fluid">
    <!-- =====================================================================
         CABECERA Y BREADCRUMB
         ===================================================================== -->
    <div class="card border shadow-none position-relative overflow-hidden mb-4">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-12 text-center text-md-start">
                    <h4 class="fw-semibold mb-2 mb-md-8">Guía de Uso</h4>
                    <nav aria-label="breadcrumb" class="d-flex justify-content-center justify-content-md-start">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a class="text-muted text-decoration-none" href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                            <li class="breadcrumb-item text-muted" aria-current="page">Guía de Uso</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- =====================================================================
         CONTENIDO DE LA GUÍA (PASOS)
         ===================================================================== -->
    <div class="row">
        <!-- Paso 1: Compartir e Intercambiar Archivos -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="badge bg-primary text-white rounded-pill px-3 py-1 fs-2">Paso 1</span>
                        <i class="ti ti-cloud-upload text-primary fs-7"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Compartir un Archivo</h5>
                    
                    <ul class="list-unstyled mb-0 fs-3 text-muted">
                        <li class="d-flex align-items-start gap-2 mb-3">
                            <i class="ti ti-circle-check text-primary fs-5 mt-1"></i>
                            <span>Ve a <strong class="text-primary">mis archivos</strong> en el menú lateral.</span>
                        </li>
                        <li class="d-flex align-items-start gap-2 mb-3">
                            <i class="ti ti-circle-check text-primary fs-5 mt-1"></i>
                            <span>Busca el boton de <strong class="text-primary">subir archivo</strong>.</span>
                        </li>
                        <li class="d-flex align-items-start gap-2 mb-3">
                            <i class="ti ti-circle-check text-primary fs-5 mt-1"></i>
                            <span>Arrastra y suelta tu archivo en la zona de subida o haz clic en <strong class="text-primary">seleccionar archivo</strong>.</span>
                        </li>
                        <li class="d-flex align-items-start gap-2 mb-3">
                            <i class="ti ti-circle-check text-primary fs-5 mt-1"></i>
                            <span><strong>Configuración (Opcional):</strong>
                                <br>- Pon un enlace personalizado.
                                <br>- Protege la descarga con contraseña.
                                <br>- Define una fecha de expiración o un límite de descargas.
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Paso 2: Gestión de Enlaces y Correo -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="badge bg-primary text-white rounded-pill px-3 py-1 fs-2">Paso 2</span>
                        <i class="ti ti-mail text-primary fs-7"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Administrar y Enviar Enlaces</h5>
                    
                    <ul class="list-unstyled mb-0 fs-3 text-muted">
                        <li class="d-flex align-items-start gap-2 mb-3">
                            <i class="ti ti-circle-check text-primary fs-5 mt-1"></i>
                            <span>En la sección <strong class="text-primary">mis archivos</strong> encontrarás el listado completo de tus transferencias con estadísticas de descargas y fechas de expiración.</span>
                        </li>
                        <li class="d-flex align-items-start gap-2 mb-3">
                            <i class="ti ti-circle-check text-primary fs-5 mt-1"></i>
                            <span>Haz clic en el icono de copiar enlace <i class="ti ti-link text-primary"></i> para guardarlo en tu portapapeles o utiliza la opción de enviar por correo electrónico para notificar directamente al destinatario.</span>
                        </li>
                        <li class="d-flex align-items-start gap-2">
                            <i class="ti ti-circle-check text-primary fs-5 mt-1"></i>
                            <span>Usa el menú de opciones <i class="ti ti-dots-vertical text-muted"></i> de cada archivo si necesitas editar sus parámetros o eliminarlo de forma manual.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Paso 3: Autodestrucción y Limpieza -->
        <div class="col-lg-4 col-md-12 mb-4">
            <div class="card h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="badge bg-primary text-white rounded-pill px-3 py-1 fs-2">Paso 3</span>
                        <i class="ti ti-flame text-primary fs-7"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Autodestrucción y Seguridad</h5>
                    
                    <ul class="list-unstyled mb-0 fs-3 text-muted">
                        <li class="d-flex align-items-start gap-2 mb-3">
                            <i class="ti ti-circle-check text-primary fs-5 mt-1"></i>
                            <span><strong class="text-primary">¿Qué es la Autodestrucción?</strong> Si activas esta opción al subir o editar, el archivo se eliminará físicamente del servidor tan pronto como caduque o alcance el límite máximo de descargas.</span>
                        </li>
                        <li class="d-flex align-items-start gap-2 mb-3">
                            <i class="ti ti-circle-check text-primary fs-5 mt-1"></i>
                            <span><strong class="text-primary">Liberación de espacio:</strong> Al eliminar manualmente un enlace en la tabla principal, el sistema se encarga de borrar el archivo físico del disco de forma irreversible.</span>
                        </li>
                        <li class="d-flex align-items-start gap-2">
                            <i class="ti ti-circle-check text-primary fs-5 mt-1"></i>
                            <span><strong class="text-primary">Privacidad garantizada:</strong> Toda la información y archivos físicos se procesan y almacenan de forma local y privada en tu propio servidor.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
