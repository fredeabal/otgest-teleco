<!-- =====================================================================
     CABECERA Y BREADCRUMB (NAVEGACIÓN)
     ===================================================================== -->
<div class="card border shadow-none position-relative overflow-hidden mb-4">
  <div class="card-body px-4 py-3">
    <div class="row align-items-center">
      <div class="col-12 col-md-8 text-center text-md-start">
        <h4 class="fw-semibold mb-2 mb-md-8">Plantillas</h4>
        <nav aria-label="breadcrumb" class="d-flex justify-content-center justify-content-md-start">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">
              <a class="text-muted text-decoration-none" href="<?= site_url() ?>">Inicio</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">Plantillas</li>
          </ol>
        </nav>
      </div>
      <div class="col-12 col-md-4 d-flex justify-content-center justify-content-md-end align-items-center mt-3 mt-md-0">
        <a href="<?= site_url('templates/create') ?>" class="btn btn-primary border-0 d-flex align-items-center gap-1">
          <i class="ti ti-plus"></i>
          <span>Nueva Plantilla</span>
        </a>
      </div>
    </div>
  </div>
</div>

<!-- =====================================================================
     LISTADO DE PLANTILLAS Y BÚSQUEDA
     ===================================================================== -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-end align-items-center mb-4 gap-3">
                    <form method="GET" action="<?= site_url('templates') ?>" class="d-flex align-items-center gap-2 w-100 w-md-auto search-form-responsive ms-auto" autocomplete="off">
                        <div class="position-relative w-100 search-box-container">
                            <input type="text" name="search" id="search-templates" class="form-control" placeholder="Buscar plantilla..." value="<?= isset($search) ? esc($search) : '' ?>">
                            <button type="submit" class="position-absolute top-50 translate-middle-y bg-transparent border-0 text-muted" style="left: 0.75rem; padding:0; z-index: 10;">
                                <i class="ti ti-search search-icon text-muted" style="position:static; transform:none;"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <div id="templates-list-container">

                <?php if (empty($templates)): ?>
                    <div class="alert alert-dark text-center py-4" role="alert">
                        <?= !empty($search) ? 'No se encontraron plantillas para tu búsqueda.' : 'No se encontraron plantillas.' ?>
                    </div>
                <?php else: ?>
                <div class="table-responsive-lg">
                    <table class="table table-hover align-middle text-nowrap text-center" id="templates-table">
                        <thead>
                            <tr>
                                <th class="text-start">Nombre</th>
                                <th class="text-end"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($templates as $template): ?>
                             <tr onclick="window.location='<?= site_url('templates/edit/' . $template['plantilla_id']) ?>'" class="cursor-pointer">
                                <td class="text-start fw-bold text-primary-hover"><?= esc($template['plantilla_nombre']) ?></td>
                                <td class="text-end" onclick="event.stopPropagation();">
                                    <div class="dropdown">
                                        <button class="btn btn-sm bg-transparent border-0 text-muted shadow-none p-1" type="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-popper-config='{"strategy":"fixed"}'>
                                            <i class="ti ti-dots-vertical fs-5"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center gap-2" href="<?= site_url('templates/edit/' . $template['plantilla_id']) ?>">
                                                    <i class="ti ti-pencil fs-4"></i> Editar
                                                </a>
                                            </li>
                                            <li>
                                                <button class="dropdown-item d-flex align-items-center gap-2 text-danger" type="button" onclick="confirmarEliminacion(<?= $template['plantilla_id'] ?>)">
                                                    <i class="ti ti-trash fs-4"></i> Eliminar
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-templates');
    const form = searchInput.closest('form');
    const container = document.getElementById('templates-list-container');
    let debounceTimer;

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        performSearch(searchInput.value);
    });

    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            performSearch(this.value);
        }, 400); // 400ms delay for AJAX request
    });

    function performSearch(query) {
        const url = new URL(form.action);
        if (query.trim() !== '') {
            url.searchParams.set('search', query);
        } else {
            url.searchParams.delete('search');
        }

        container.style.opacity = '0.5';
        container.style.pointerEvents = 'none';

        fetch(url.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newContainer = doc.getElementById('templates-list-container');
            if (newContainer) {
                container.innerHTML = newContainer.innerHTML;
            }
            
            window.history.replaceState({}, '', url.toString());
        })
        .catch(err => console.error('Error en búsqueda AJAX:', err))
        .finally(() => {
            container.style.opacity = '1';
            container.style.pointerEvents = 'auto';
        });
    }
});

function confirmarEliminacion(id) {
    Swal.fire({
        title: '¿Deseas eliminar la plantilla?',
        text: "Esta acción no se puede deshacer.",
        icon: 'warning',
        showCancelButton: true,
        reverseButtons: true,
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
</script>
