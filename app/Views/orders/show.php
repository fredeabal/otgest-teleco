<!-- =====================================================================
     CABECERA Y BREADCRUMB (NAVEGACIÓN)
     ===================================================================== -->
<div class="card border shadow-none position-relative overflow-hidden mb-4">
  <div class="card-body px-4 py-3">
    <div class="row align-items-center">
      <div class="col-12 col-md-7 mb-3 mb-md-0">
        <h4 class="fw-semibold mb-8">Detalle de la Orden</h4>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <a class="text-muted text-decoration-none" href="<?= site_url() ?>">Inicio</a>
            </li>
            <li class="breadcrumb-item">
              <a class="text-muted text-decoration-none" href="<?= site_url('orders') ?>">Órdenes</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">Detalle</li>
          </ol>
        </nav>
      </div>
      <div class="col-12 col-md-5 d-flex justify-content-end align-items-center gap-2">
          <?php if (!empty($prevOrder)): ?>
              <a href="<?= site_url('orders/show/'.$prevOrder['ot_id']) ?>" class="btn btn-primary d-flex align-items-center justify-content-center px-2" title="Anterior (OT <?= esc($prevOrder['ot_numero']) ?>)">
                  <i class="ti ti-chevron-left fs-5"></i>
              </a>
          <?php endif; ?>
          
          <?php if (!empty($nextOrder)): ?>
              <a href="<?= site_url('orders/show/'.$nextOrder['ot_id']) ?>" class="btn btn-primary d-flex align-items-center justify-content-center px-2" title="Siguiente (OT <?= esc($nextOrder['ot_numero']) ?>)">
                  <i class="ti ti-chevron-right fs-5"></i>
              </a>
          <?php endif; ?>

      </div>
    </div>
  </div>
</div>

<style>
.operator-logo-show { height: 35px; object-fit: contain; }
@media (min-width: 768px) { .operator-logo-show { height: 50px; } }
</style>

<div class="row">
    <!-- Detalles Principales (Formulario Desactivado) -->
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="fw-semibold text-white mb-4 d-flex align-items-center justify-content-between pb-3 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <?php 
                            $opKey = strtolower($order['ot_operadora'] ?? '');
                            $logoFile = '';
                            if (in_array($opKey, ['digi', 'finetwork', 'jazztel', 'lowi', 'masmovil', 'movistar', 'o2', 'orange', 'pepephone', 'vodafone', 'yoigo', 'r-cable', 'virgin'])) {
                                $logoFile = $opKey . '.png';
                            }
                        ?>
                        <?php if ($logoFile): ?>
                            <img src="<?= base_url('assets/images/operators/' . $logoFile) ?>" alt="<?= esc($order['ot_operadora']) ?>" class="operator-logo-show" title="<?= esc($order['ot_operadora']) ?>">
                        <?php elseif (!empty($order['ot_operadora'])): ?>
                            <span class="fs-5 text-muted">(<?= htmlspecialchars($order['ot_operadora']) ?>)</span>
                        <?php endif; ?>

                        <span>OT <?= htmlspecialchars($order['ot_numero']) ?></span>
                    </div>

                    <div style="font-size: 0.75rem; letter-spacing: 0.5px;">
                        <?php if($order['ot_estado'] == 1): ?>
                            <span class="text-success fw-normal d-flex align-items-center gap-1"><i class="ti ti-check"></i> <span class="d-none d-sm-inline text-uppercase">Finalizada</span></span>
                        <?php elseif($order['ot_estado'] == 2): ?>
                            <span class="text-danger fw-normal d-flex align-items-center gap-1"><i class="ti ti-alert-circle"></i> <span class="d-none d-sm-inline text-uppercase">Escalada</span></span>
                        <?php elseif($order['ot_estado'] == 3): ?>
                            <span class="text-danger fw-normal d-flex align-items-center gap-1"><i class="ti ti-alert-triangle"></i> <span class="d-none d-sm-inline text-uppercase">Incidencia</span></span>
                        <?php elseif($order['ot_estado'] == 4): ?>
                            <span class="text-danger fw-normal d-flex align-items-center gap-1"><i class="ti ti-ban"></i> <span class="d-none d-sm-inline text-uppercase">Anulada</span></span>
                        <?php else: ?>
                            <span class="text-warning fw-normal d-flex align-items-center gap-1"><i class="ti ti-clock"></i> <span class="d-none d-sm-inline text-uppercase">Pendiente</span></span>
                        <?php endif; ?>
                    </div>
                </h4>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted font-size-13">Cliente</label>
                        <input type="text" class="form-control" disabled value="<?= htmlspecialchars($order['ot_cliente']) ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted font-size-13">Dirección</label>
                        <div class="input-group">
                            <input type="text" class="form-control" disabled value="<?= htmlspecialchars($order['ot_direccion']) ?>">
                            <a href="https://www.google.es/maps?q=<?= urlencode($order['ot_direccion']) ?>" target="_blank" class="btn bg-primary-subtle text-primary d-flex align-items-center justify-content-center" title="Ver en Maps">
                                <i class="ti ti-map-pin"></i>
                            </a>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-muted font-size-13">Trabajo realizado</label>
                        <input type="text" class="form-control" disabled value="<?= htmlspecialchars($order['ot_tipo']) ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-muted font-size-13">Fecha</label>
                        <input type="text" class="form-control" disabled value="<?= date('d/m/Y', strtotime($order['ot_fecha'])) ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-muted font-size-13">Técnico</label>
                        <input type="text" class="form-control" disabled value="<?= htmlspecialchars($tecnico_nombre ?? $order['ot_usr']) ?>">
                    </div>

                    <div class="col-12 mb-4">
                        <label class="form-label text-muted font-size-13">Comentarios</label>
                        <textarea class="form-control" disabled rows="5"><?= htmlspecialchars(str_replace("<br />", "\n", $order['ot_txt'])) ?></textarea>
                    </div>
                </div>

                <?php if (auth()->user()->can('orders.view_all') || $order['ot_usr'] == auth()->user()->id): ?>
                <div class="d-flex justify-content-center mt-4 gap-2 border-top pt-4">
                    <a href="<?= site_url('orders/edit/'.$order['ot_id']) ?>" class="btn btn-primary d-flex align-items-center justify-content-center gap-2 px-3">
                        <i class="ti ti-pencil fs-5"></i>
                        <span class="d-none d-md-inline">Editar</span>
                    </a>
                    <button type="button" class="btn btn-outline-primary d-flex align-items-center justify-content-center gap-2 px-3" data-bs-toggle="modal" data-bs-target="#uploadImagesModal" title="Subir archivo">
                        <i class="ti ti-photo fs-5"></i>
                        <span class="d-none d-md-inline">Subir archivo</span>
                    </button>
                    <button type="button" class="btn btn-outline-primary d-flex align-items-center justify-content-center gap-2 px-3" data-bs-toggle="modal" data-bs-target="#sendEmailModal" title="Enviar por correo">
                        <i class="ti ti-mail fs-5"></i>
                        <span class="d-none d-md-inline">Enviar correo</span>
                    </button>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- =====================================================================
     GALERÍA DE IMÁGENES
     ===================================================================== -->
<?php if (!empty($images)): ?>
<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title fw-semibold mb-4 border-bottom border-secondary-subtle pb-2">Evidencias Fotográficas</h5>
        
        <div class="row g-3">
            <?php foreach($images as $img): ?>
                <div class="col-6 col-md-4 col-lg-3 position-relative">
                    <a href="<?= base_url('ots/'.$img['img_nombre']) ?>" target="_blank" class="d-block overflow-hidden rounded border border-secondary-subtle" style="height: 150px;">
                        <img src="<?= base_url('ots/'.$img['img_nombre']) ?>" alt="Evidencia" class="img-fluid w-100 h-100 object-fit-cover hover-zoom">
                    </a>
                    <?php if (auth()->user()->can('orders.view_all') || $order['ot_usr'] == auth()->user()->id): ?>
                        <button onclick="eliminarImagen(<?= $img['img_id'] ?>, <?= $order['ot_id'] ?>)" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 p-1 rounded-circle shadow" style="width: 28px; height: 28px; display: flex; align-items: center; justify-content: center;" title="Eliminar">
                            <i class="ti ti-x font-size-14"></i>
                        </button>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Modal Enviar por Correo -->
<div class="modal fade" id="sendEmailModal" tabindex="-1" aria-labelledby="sendEmailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h5 class="modal-title d-flex align-items-center gap-2 fw-semibold text-primary" id="sendEmailModalLabel">
            <i class="ti ti-mail fs-5"></i> Enviar Orden de Trabajo
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?= site_url('orders/email/' . $order['ot_id']) ?>" method="POST">
        <?= csrf_field() ?>
        <div class="modal-body">
            <div class="mb-3">
                <label for="recipient_email" class="form-label">Correo electrónico del destinatario</label>
                <input type="email" class="form-control" id="recipient_email" name="recipient_email" required placeholder="ejemplo@dominio.com">
            </div>
            <p class="text-muted small mb-0">Se enviará un correo con los detalles de la orden Nº <?= htmlspecialchars($order['ot_numero']) ?>.</p>
        </div>
        <div class="modal-footer border-0 justify-content-center">
            <button type="button" class="btn btn-outline-primary px-4" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary px-4 d-flex align-items-center gap-1"><i class="ti ti-send"></i> Enviar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Upload Images -->
<div class="modal fade" id="uploadImagesModal" tabindex="-1" aria-labelledby="uploadImagesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title" id="uploadImagesModalLabel">Adjuntar Imágenes</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= site_url('images/store') ?>" method="post" enctype="multipart/form-data" class="dropzone" id="imagesDropzone">
                <?= csrf_field() ?>
                <input type="hidden" name="ot_id" value="<?= $order['ot_id'] ?>">
                <div class="dz-message needsclick text-center p-4">
                    <i class="ti ti-upload fs-1 text-primary mb-3"></i>
                    <h6 class="mb-2">Arrastra los archivos aquí o haz clic para subir</h6>
                    <span class="text-muted fs-3">Solo archivos de imagen permitidos</span>
                </div>
                <div class="fallback">
                    <input name="imagen" type="file" multiple accept="image/*" />
                </div>
            </form>
            <div class="modal-footer border-0 pt-0 justify-content-center mt-3">
                <button type="button" class="btn btn-outline-primary px-4" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" id="submitDropzone" class="btn btn-primary px-4 d-flex align-items-center gap-1"><i class="ti ti-cloud-upload"></i> Subir Archivos</button>
            </div>
        </div>
    </div>
</div>

<style>
.object-fit-cover { object-fit: cover; }
.hover-zoom { transition: transform 0.3s ease; }
.hover-zoom:hover { transform: scale(1.05); }
.border-dashed { border-style: dashed !important; border-width: 2px !important; border-color: rgba(var(--bs-primary-rgb), 0.3) !important; }
</style>

<script>
function eliminarImagen(imgId, otId) {
    Swal.fire({
        title: '¿Eliminar imagen?',
        text: "Esta acción no se puede deshacer.",
        icon: 'warning',
        showCancelButton: true,
        customClass: {
            confirmButton: 'btn btn-danger ms-2',
            cancelButton: 'btn btn-outline-primary'
        },
        buttonsStyling: false,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        background: 'var(--bs-card-bg)',
        color: 'var(--bs-heading-color)'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = "<?= site_url('orders/delete_image') ?>/" + imgId + "/" + otId;
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '<?= csrf_token() ?>';
            csrfInput.value = '<?= csrf_hash() ?>';
            
            form.appendChild(csrfInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>

<!-- Dropzone JS/CSS -->
<link rel="stylesheet" href="<?= base_url('assets/libs/dropzone/dist/min/dropzone.min.css') ?>">
<script src="<?= base_url('assets/libs/dropzone/dist/min/dropzone.min.js') ?>"></script>

<style>
.dropzone {
    background: transparent;
    border: 2px dashed rgba(var(--bs-primary-rgb), 0.3);
    border-radius: 8px;
    padding: 20px;
    margin: 20px;
}
.dropzone .dz-message {
    margin: 2em 0;
}
</style>

<script>
Dropzone.autoDiscover = false;

document.addEventListener("DOMContentLoaded", function() {
    if (document.getElementById('imagesDropzone')) {
        var myDropzone = new Dropzone("#imagesDropzone", {
            paramName: "imagen", // El nombre del input esperado por el servidor
            autoProcessQueue: false, // No subir hasta que el usuario clique el botón
            uploadMultiple: false, // Enviamos uno por uno (ImageController::store solo procesa 'imagen')
            parallelUploads: 5,
            maxFiles: 20,
            acceptedFiles: "image/*",
            addRemoveLinks: true,
            dictRemoveFile: "Quitar",
            dictCancelUpload: "Cancelar",
            init: function() {
                var submitButton = document.querySelector("#submitDropzone");
                var dz = this;

                submitButton.addEventListener("click", function() {
                    if (dz.getQueuedFiles().length > 0) {
                        dz.processQueue();
                    } else {
                        Swal.fire('Atención', 'No has seleccionado ningún archivo.', 'info');
                    }
                });

                this.on("queuecomplete", function(file) {
                    // Cuando todos los archivos se han subido, recargamos la página
                    location.reload();
                });

                this.on("error", function(file, response) {
                    if (typeof response === 'object' && response.error) {
                        Swal.fire('Error', response.error, 'error');
                    }
                });
            }
        });
    }
});
</script>
