<!-- =====================================================================
     CABECERA Y BREADCRUMB (NAVEGACIÓN)
     ===================================================================== -->
<div class="card border shadow-none position-relative overflow-hidden mb-4">
  <div class="card-body px-4 py-3">
    <div class="row align-items-center">
      <div class="col-9">
        <h4 class="fw-semibold mb-8">Editar Plantilla</h4>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <a class="text-muted text-decoration-none" href="<?= site_url() ?>">Inicio</a>
            </li>
            <li class="breadcrumb-item">
              <a class="text-muted text-decoration-none" href="<?= site_url('templates') ?>">Plantillas</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">Editar</li>
          </ol>
        </nav>
      </div>
      <div class="col-3 d-flex justify-content-end align-items-center">
      </div>
    </div>
  </div>
</div>

<!-- =====================================================================
     FORMULARIO DE EDICIÓN DE PLANTILLA
     ===================================================================== -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="<?= site_url('templates/update/' . $template['plantilla_id']) ?>" method="post" autocomplete="off">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label for="plantilla_nombre" class="form-label">Nombre de la plantilla</label>
                        <input type="text" class="form-control" id="plantilla_nombre" name="plantilla_nombre" placeholder="Ej. Cambio de CM" value="<?= old('plantilla_nombre', $template['plantilla_nombre']) ?>">
                    </div>
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-end mb-2">
                            <label for="plantilla_txt" class="form-label mb-0">Contenido de la plantilla</label>
                            <button type="button" class="btn btn-sm btn-primary" onclick="convertToUppercase()">
                                <i class="ti ti-letter-case-upper"></i> Mayúsculas
                            </button>
                        </div>
                        <textarea class="form-control font-monospace text-warning bg-dark" id="plantilla_txt" name="plantilla_txt" rows="8" placeholder="Escribe el texto de la plantilla..."><?= old('plantilla_txt', $template['plantilla_txt']) ?></textarea>
                    </div>
                    <div class="d-flex justify-content-center mt-4 gap-2 border-top pt-4">
                        <a href="<?= site_url('templates') ?>" class="btn btn-outline-primary px-4">
                            <i class="ti ti-arrow-left me-1"></i>Volver
                        </a>
                        <button type="button" onclick="confirmarEliminacion(<?= $template['plantilla_id'] ?>)" class="btn btn-outline-primary px-4">
                            <i class="ti ti-trash me-1"></i>Eliminar
                        </button>
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
function confirmarEliminacion(id) {
    Swal.fire({
        title: '¿Deseas eliminar la plantilla?',
        text: "Esta acción no se puede deshacer.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: 'var(--bs-primary)',
        cancelButtonColor: 'var(--bs-danger)',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        background: 'var(--bs-card-bg)',
        color: 'var(--bs-heading-color)'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = "<?= site_url('templates/delete') ?>/" + id;
            
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

function convertToUppercase() {
    const txtArea = document.getElementById('plantilla_txt');
    if (txtArea) {
        txtArea.value = txtArea.value.toUpperCase();
    }
}
</script>
