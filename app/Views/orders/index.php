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
                            <button type="button" class="btn position-absolute text-muted end-0 top-50 translate-middle-y border-0" data-bs-toggle="collapse" data-bs-target="#advancedFilters" aria-expanded="false" aria-controls="advancedFilters" title="Filtros avanzados">
                                <i class="ti ti-adjustments-horizontal"></i>
                            </button>
                            <button type="submit" class="position-absolute top-50 translate-middle-y bg-transparent border-0 text-muted" style="left: 0.75rem; padding:0; z-index: 10;">
                                <i class="ti ti-search search-icon text-muted" style="position:static; transform:none;"></i>
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Collapse Filtros Avanzados -->
                <div class="collapse mb-4" id="advancedFilters">
                    <div class="card card-body bg-transparent border-0 shadow-none mb-0 p-0">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label fs-2 fw-semibold">Operadora</label>
                                <select class="form-select filter-select" id="filter-operadora" name="operadora">
                                    <option value="">Todas las operadoras</option>
                                    <?php 
                                        $operadoras = ['DIGI', 'FINETWORK', 'JAZZTEL', 'LOWI', 'MASMOVIL', 'MOVISTAR', 'O2', 'ORANGE', 'PEPEPHONE', 'R-CABLE', 'VIRGIN', 'VODAFONE', 'YOIGO'];
                                        foreach($operadoras as $op): 
                                    ?>
                                        <option value="<?= $op ?>" <?= (isset($operadora) && $operadora == $op) ? 'selected' : '' ?>><?= $op ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fs-2 fw-semibold">Estado</label>
                                <select class="form-select filter-select" id="filter-estado" name="estado">
                                    <option value="">Todos los estados</option>
                                    <option value="1" <?= (isset($estado) && $estado == '1') ? 'selected' : '' ?>>Finalizado</option>
                                    <option value="2" <?= (isset($estado) && $estado == '2') ? 'selected' : '' ?>>Escalado</option>
                                    <option value="3" <?= (isset($estado) && $estado == '3') ? 'selected' : '' ?>>Incidencia</option>
                                    <option value="4" <?= (isset($estado) && $estado == '4') ? 'selected' : '' ?>>Anulado</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fs-2 fw-semibold">Imputación</label>
                                <select class="form-select filter-select" id="filter-imputada" name="imputada">
                                    <option value="">Todas</option>
                                    <option value="1" <?= (isset($imputada) && $imputada == '1') ? 'selected' : '' ?>>Imputadas</option>
                                    <option value="0" <?= (isset($imputada) && $imputada == '0') ? 'selected' : '' ?>>Sin imputar</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fs-2 fw-semibold">Tipo</label>
                                <select class="form-select filter-select" id="filter-tipo" name="tipo">
                                    <option value="">Todos los tipos</option>
                                    <option value="INSTALACION" <?= (isset($tipo) && $tipo == 'INSTALACION') ? 'selected' : '' ?>>Instalación</option>
                                    <option value="AVERIA" <?= (isset($tipo) && $tipo == 'AVERIA') ? 'selected' : '' ?>>Avería</option>
                                    <option value="MODIFICACION" <?= (isset($tipo) && $tipo == 'MODIFICACION') ? 'selected' : '' ?>>Modificación</option>
                                    <option value="TRASLADO" <?= (isset($tipo) && $tipo == 'TRASLADO') ? 'selected' : '' ?>>Traslado</option>
                                    <option value="PORTABILIDAD" <?= (isset($tipo) && $tipo == 'PORTABILIDAD') ? 'selected' : '' ?>>Portabilidad</option>
                                    <option value="BAJA" <?= (isset($tipo) && $tipo == 'BAJA') ? 'selected' : '' ?>>Baja</option>
                                    <option value="AUDITORIA" <?= (isset($tipo) && $tipo == 'AUDITORIA') ? 'selected' : '' ?>>Auditoría</option>
                                </select>
                            </div>
                        </div>
                        <div class="row g-3 mt-1">
                            <?php if (auth()->user()->can('orders.view_all')): ?>
                                <div class="col-md-4">
                                    <label class="form-label fs-2 fw-semibold">Fecha Desde</label>
                                    <input type="text" class="form-control filter-input filter-datepicker" id="filter-fecha-desde" name="fecha_desde" placeholder="DD/MM/YYYY" value="<?= isset($fecha_desde) ? esc($fecha_desde) : '' ?>" autocomplete="off">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fs-2 fw-semibold">Fecha Hasta</label>
                                    <input type="text" class="form-control filter-input filter-datepicker" id="filter-fecha-hasta" name="fecha_hasta" placeholder="DD/MM/YYYY" value="<?= isset($fecha_hasta) ? esc($fecha_hasta) : '' ?>" autocomplete="off">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fs-2 fw-semibold">Técnico</label>
                                    <select class="form-select filter-select" id="filter-usuario" name="usuario">
                                        <option value="">Todos los técnicos</option>
                                        <?php foreach ($users as $usr): ?>
                                            <option value="<?= $usr->id ?>" <?= (isset($usuario) && $usuario == $usr->id) ? 'selected' : '' ?>><?= esc($usr->username) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php else: ?>
                                <div class="col-md-6">
                                    <label class="form-label fs-2 fw-semibold">Fecha Desde</label>
                                    <input type="text" class="form-control filter-input filter-datepicker" id="filter-fecha-desde" name="fecha_desde" placeholder="DD/MM/YYYY" value="<?= isset($fecha_desde) ? esc($fecha_desde) : '' ?>" autocomplete="off">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fs-2 fw-semibold">Fecha Hasta</label>
                                    <input type="text" class="form-control filter-input filter-datepicker" id="filter-fecha-hasta" name="fecha_hasta" placeholder="DD/MM/YYYY" value="<?= isset($fecha_hasta) ? esc($fecha_hasta) : '' ?>" autocomplete="off">
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
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
                                <?php if (auth()->user()->can('orders.view_all')): ?>
                                    <th class="text-start d-none d-md-table-cell">Técnico</th>
                                <?php endif; ?>
                                <th class="text-start d-none d-md-table-cell">Cliente</th>
                                <th class="text-center d-none d-md-table-cell">Estado</th>
                                <th class="text-end"></th>
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
                                <?php if (auth()->user()->can('orders.view_all')): ?>
                                    <td class="text-start d-none d-md-table-cell"><?= esc($order['username'] ?? 'Sistema') ?></td>
                                <?php endif; ?>
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
                                                <a class="dropdown-item d-flex align-items-center gap-2 text-body" href="<?= site_url('orders/show/' . $order['ot_id']) ?>">
                                                    <i class="ti ti-eye fs-4"></i> Ver Detalle
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center gap-2 text-body" href="<?= site_url('orders/edit/' . $order['ot_id']) ?>">
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
    const filterSelects = document.querySelectorAll('.filter-select, .filter-input');
    const form = searchInput.closest('form');
    const container = document.getElementById('orders-list-container');
    let debounceTimer;
    
    // Initialize separate Bootstrap Datepickers
    if ($.fn.datepicker) {
        $('.filter-datepicker').datepicker({
            format: 'dd/mm/yyyy',
            language: 'es',
            autoclose: true,
            todayHighlight: true
        }).on('changeDate', function() {
            performSearch();
        });
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        performSearch();
    });

    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            performSearch();
        }, 400); // 400ms delay for AJAX request
    });
    
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            performSearch();
        });
    });

    function performSearch() {
        const query = searchInput.value;
        const operadora = document.getElementById('filter-operadora').value;
        const estado = document.getElementById('filter-estado').value;
        const tipo = document.getElementById('filter-tipo').value;
        const imputada = document.getElementById('filter-imputada').value;
        const fechaDesde = document.getElementById('filter-fecha-desde').value;
        const fechaHasta = document.getElementById('filter-fecha-hasta').value;
        const usuarioEl = document.getElementById('filter-usuario');
        const usuario = usuarioEl ? usuarioEl.value : '';
        
        const url = new URL(form.action);
        
        if (query.trim() !== '') url.searchParams.set('search', query);
        else url.searchParams.delete('search');
        
        if (operadora !== '') url.searchParams.set('operadora', operadora);
        else url.searchParams.delete('operadora');
        
        if (estado !== '') url.searchParams.set('estado', estado);
        else url.searchParams.delete('estado');
        
        if (tipo !== '') url.searchParams.set('tipo', tipo);
        else url.searchParams.delete('tipo');
        
        if (imputada !== '') url.searchParams.set('imputada', imputada);
        else url.searchParams.delete('imputada');
        
        if (fechaDesde !== '') url.searchParams.set('fecha_desde', fechaDesde);
        else url.searchParams.delete('fecha_desde');
        
        if (fechaHasta !== '') url.searchParams.set('fecha_hasta', fechaHasta);
        else url.searchParams.delete('fecha_hasta');

        if (usuario !== '') url.searchParams.set('usuario', usuario);
        else url.searchParams.delete('usuario');

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
