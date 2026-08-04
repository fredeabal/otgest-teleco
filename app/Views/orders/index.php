<!-- =====================================================================
     CABECERA Y BREADCRUMB (NAVEGACIÓN)
     ===================================================================== -->
<div class="card border shadow-none position-relative overflow-hidden mb-4">
  <div class="card-body px-4 py-3">
    <div class="row align-items-center">
      <div class="col-12 col-md-8 text-center text-md-start">
        <h4 class="fw-semibold mb-2 mb-md-8">Órdenes de Trabajo</h4>
        <nav aria-label="breadcrumb" class="d-flex justify-content-center justify-content-md-start">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">
              <a class="text-muted text-decoration-none" href="<?= site_url() ?>">Inicio</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">Órdenes</li>
          </ol>
        </nav>
      </div>
      <div class="col-12 col-md-4 d-flex justify-content-center justify-content-md-end align-items-center mt-3 mt-md-0">
        <a href="<?= site_url('orders/create') ?>" class="btn btn-primary border-0 d-flex align-items-center gap-1">
          <i class="ti ti-plus"></i>
          <span>Nueva Orden</span>
        </a>
      </div>
    </div>
  </div>
</div>

<!-- =====================================================================
     LISTADO DE ÓRDENES Y BÚSQUEDA
     ===================================================================== -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-end align-items-center mb-4 gap-3">
                    <form method="GET" action="<?= site_url('orders') ?>" class="d-flex align-items-center gap-2 w-100 w-md-auto search-form-responsive ms-auto" autocomplete="off">
                        <div class="position-relative w-100 search-box-container">
                            <input type="text" name="search" id="search-orders" class="form-control" placeholder="Buscar orden..." value="<?= isset($search) ? esc($search) : '' ?>">
                            <button type="submit" class="position-absolute top-50 translate-middle-y bg-transparent border-0 text-muted" style="left: 0.75rem; padding:0; z-index: 10;">
                                <i class="ti ti-search search-icon text-muted" style="position:static; transform:none;"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <div id="orders-list-container">

                <?php if (empty($orders)): ?>
                    <div class="alert alert-dark text-center py-4" role="alert">
                        <?= !empty($search) ? 'No se encontraron órdenes para tu búsqueda.' : 'No se encontraron órdenes de trabajo.' ?>
                    </div>
                <?php else: ?>
                <div class="table-responsive-lg">
                    <table class="table table-hover align-middle text-nowrap text-center" id="orders-table">
                        <thead>
                            <tr>
                                <th class="text-start">Fecha</th>
                                <th class="text-center">Nº OT</th>
                                <th class="text-center d-none d-md-table-cell">Tipo</th>
                                <th class="text-start d-none d-md-table-cell">Cliente</th>
                                <th class="text-center d-none d-md-table-cell">Estado</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                             <tr onclick="window.location='<?= site_url('orders/show/' . $order['ot_id']) ?>'" class="cursor-pointer">
                                <td class="text-start"><?= esc(date('d/m/Y', strtotime($order['ot_fecha']))) ?></td>
                                <td class="text-center fw-bold text-primary-hover">
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        <?php 
                                        $opKey = strtolower($order['ot_operadora'] ?? '');
                                        $logoFile = '';
                                        if (in_array($opKey, ['digi', 'finetwork', 'jazztel', 'lowi', 'masmovil', 'movistar', 'o2', 'orange', 'pepephone', 'vodafone', 'yoigo', 'r-cable', 'virgin'])) {
                                            $logoFile = $opKey . '.png';
                                        }
                                        if ($logoFile): ?>
                                            <img src="<?= base_url('assets/images/operators/' . $logoFile) ?>" alt="<?= esc($order['ot_operadora']) ?>" style="width: 20px; height: 20px; object-fit: contain; flex-shrink: 0;" title="<?= esc($order['ot_operadora']) ?>">
                                        <?php endif; ?>
                                        <span><?= esc($order['ot_numero']) ?></span>
                                    </div>
                                </td>
                                <td class="text-center d-none d-md-table-cell"><?= esc($order['ot_tipo']) ?></td>
                                <td class="text-start d-none d-md-table-cell"><?= esc($order['ot_cliente']) ?></td>
                                <td class="text-center d-none d-md-table-cell">
                                    <?php if($order['ot_estado'] == 1): ?>
                                        <span class="badge bg-success badge-status">Finalizada</span>
                                    <?php elseif($order['ot_estado'] == 2): ?>
                                        <span class="badge bg-danger badge-status">Escalada</span>
                                    <?php elseif($order['ot_estado'] == 3): ?>
                                        <span class="badge bg-danger badge-status">Incidencia</span>
                                    <?php elseif($order['ot_estado'] == 4): ?>
                                        <span class="badge bg-danger badge-status">Anulada</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning badge-status text-dark">Pendiente</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end" onclick="event.stopPropagation();">
                                    <div class="dropdown">
                                        <button class="btn btn-sm bg-transparent border-0 text-muted shadow-none p-1" type="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-popper-config='{"strategy":"fixed"}'>
                                            <i class="ti ti-dots-vertical fs-5"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center gap-2" href="<?= site_url('orders/show/' . $order['ot_id']) ?>">
                                                    <i class="ti ti-eye fs-4"></i> Ver Detalle
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center gap-2" href="<?= site_url('orders/edit/' . $order['ot_id']) ?>">
                                                    <i class="ti ti-pencil fs-4"></i> Editar
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php if(isset($pager)): ?>
                    <div class="mt-4 d-flex justify-content-center">
                        <?= $pager->links() ?>
                    </div>
                <?php endif; ?>
                <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-orders');
    const form = searchInput.closest('form');
    const container = document.getElementById('orders-list-container');
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
            const newContainer = doc.getElementById('orders-list-container');
            if (newContainer) {
                container.innerHTML = newContainer.innerHTML;
            }
            
            // Actualizar la URL de la página sin recargar
            window.history.replaceState({}, '', url.toString());
        })
        .catch(err => console.error('Error en búsqueda AJAX:', err))
        .finally(() => {
            container.style.opacity = '1';
            container.style.pointerEvents = 'auto';
        });
    }
});
</script>
