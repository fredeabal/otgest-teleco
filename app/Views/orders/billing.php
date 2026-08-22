<!-- =====================================================================
     CABECERA Y BREADCRUMB (NAVEGACIÓN)
     ===================================================================== -->
<!-- Select2 CSS -->
<link rel="stylesheet" href="<?= base_url('assets/libs/select2/dist/css/select2.min.css') ?>">
<style>
    /* Ocultar el caret (flecha) de Select2 */
    .select2-container .select2-selection__arrow {
        display: none !important;
    }
    /* Estilos para igualar Select2 a un form-select de Bootstrap 5 en Modernize */
    .select2-container .select2-selection--single {
        height: 39px !important;
        background-color: var(--bs-body-bg) !important;
        border: 1px solid var(--bs-border-color) !important;
        border-radius: 7px !important;
        position: relative;
    }
    /* Asegurar que el texto seleccionado sea visible y centrado verticalmente */
    .select2-container .select2-selection--single .select2-selection__rendered {
        color: var(--bs-heading-color, #333) !important;
        line-height: 37px !important; /* 39px height - 2px border */
        padding-left: 14px !important;
        padding-right: 30px !important;
    }
    /* Posicionar la X de borrar */
    .select2-container .select2-selection__clear {
        position: absolute !important;
        right: 12px !important;
        top: 0 !important;
        height: 100% !important;
        display: flex !important;
        align-items: center !important;
        margin: 0 !important;
        font-size: 1.2rem;
        color: var(--bs-heading-color) !important;
        z-index: 10;
        transform: none !important;
    }
</style>

<div class="card border shadow-none position-relative overflow-hidden mb-4">
  <div class="card-body px-4 py-3">
    <div class="row align-items-center">
      <div class="col-12 col-md-6 mb-3 mb-md-0">
        <h4 class="fw-semibold mb-8">Facturación e Instalaciones</h4>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <a class="text-muted text-decoration-none" href="<?= site_url() ?>">Inicio</a>
            </li>
            <li class="breadcrumb-item">
              <a class="text-muted text-decoration-none" href="<?= site_url('orders') ?>">Órdenes</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">Facturación Mensual</li>
          </ol>
        </nav>
      </div>
      <div class="col-12 col-md-6 d-flex justify-content-end align-items-center gap-2">
         <!-- Botones de Acción Rápida -->
         <a href="<?= site_url('orders/billing/pdf?mes=' . $mes . '&year=' . $year . ($canViewAll && !empty($selectedUserId) ? '&user_id='.$selectedUserId : '')) ?>" class="btn btn-outline-primary px-3">
             <i class="ti ti-file-type-pdf fs-5"></i> Descargar PDF
         </a>
         <button type="button" onclick="confirmarEnvioCorreo()" class="btn btn-primary px-3">
             <i class="ti ti-mail fs-5"></i> Enviar por Correo
         </button>
      </div>
    </div>
  </div>
</div>

<!-- =====================================================================
     FILTRO MENSUAL
     ===================================================================== -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body p-3">
                <?php $colClass = $canViewAll ? 'col-md-4' : 'col-md-6'; ?>
                <form id="billingFilterForm" action="<?= site_url('orders/billing') ?>" method="get" class="row g-3 align-items-end">
                    <div class="<?= $colClass ?> col-sm-6">
                        <label for="year" class="form-label font-size-13 text-muted">Seleccionar Año</label>
                        <select class="form-select" id="year" name="year" onchange="this.form.submit()">
                            <?php
                            $currentYear = date('Y');
                            for($y = $currentYear; $y >= $currentYear - 5; $y--):
                            ?>
                                <option value="<?= $y ?>" <?= ($year == $y) ? 'selected' : '' ?>><?= $y ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="<?= $colClass ?> col-sm-6">
                        <label for="mes" class="form-label font-size-13 text-muted">Seleccionar Mes</label>
                        <select class="form-select" id="mes" name="mes" onchange="this.form.submit()">
                            <?php
                            $meses = [
                                '01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', '04' => 'Abril',
                                '05' => 'Mayo', '06' => 'Junio', '07' => 'Julio', '08' => 'Agosto',
                                '09' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'
                            ];
                            foreach($meses as $key => $val):
                            ?>
                                <option value="<?= $key ?>" <?= ($mes == $key) ? 'selected' : '' ?>><?= $val ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php if($canViewAll): ?>
                    <div class="col-md-4 col-sm-12">
                        <label for="user_id" class="form-label font-size-13 text-muted">Técnico (Opcional)</label>
                        <select class="form-control select2" id="user_id" name="user_id">
                            <option value="">Todos los técnicos</option>
                            <?php foreach($users as $u): ?>
                                <option value="<?= $u->id ?>" <?= (!empty($selectedUserId) && $selectedUserId == $u->id) ? 'selected' : '' ?>><?= esc($u->username) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- =====================================================================
     TABLA DE RESUMEN Y CALCULOS
     ===================================================================== -->
<div class="row">
    <!-- Tabla de OTs -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Instalaciones Completadas en el Periodo</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="text-white">
                            <tr>
                                <th scope="col">Fecha</th>
                                <th scope="col">Número OT</th>
                                <th scope="col" class="text-center">Operadora</th>
                                <th scope="col" class="text-center">Tipo</th>
                                <th scope="col">Cliente</th>
                                <th scope="col" class="text-end">Importe (€)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($orders)): ?>
                                <?php foreach($orders as $ord): ?>
                                    <tr onclick="window.location='<?= site_url('orders/show/' . $ord['ot_id']) ?>'" style="cursor: pointer;" class="hover-bg-light">
                                        <td><?= date('d/m/Y', strtotime($ord['ot_fecha'])) ?></td>
                                        <td>
                                            <span class="text-primary">
                                                <?= esc($ord['ot_numero']) ?>
                                            </span>
                                        </td>
                                        <td class="text-muted text-center">
                                            <?= esc($ord['ot_operadora'] ?: 'N/D') ?>
                                        </td>
                                        <td class="text-muted text-center">
                                            <?= esc($ord['ot_tipo']) ?>
                                        </td>
                                        <td class="text-muted">
                                            <div class="text-truncate" style="max-width: 400px;" title="<?= esc($ord['ot_cliente']) ?>">
                                                <?= esc($ord['ot_cliente']) ?>
                                            </div>
                                        </td>
                                        <td class="text-end text-dark fw-semibold">
                                            <?= number_format($ord['ot_precio'], 2, ',', '.') ?> €
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        No se encontraron instalaciones registradas en el mes seleccionado.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Resumen Financiero Premium -->
                <div class="row mt-5">
                    <div class="col-12">
                        <div class="card bg-dark-subtle border border-secondary border-opacity-10 shadow-sm rounded-4 overflow-hidden mb-0">
                            <div class="card-body p-4">
                                <h5 class="card-title fw-semibold text-white mb-4 d-flex align-items-center gap-2">
                                    <i class="ti ti-receipt-2 text-primary fs-6"></i> Resumen de Facturación
                                </h5>
                                
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="text-muted fs-3">Total OTs del mes</span>
                                    <span class="badge bg-primary-subtle text-primary fw-semibold fs-3 px-3 py-1 rounded-pill">
                                        <?= count($orders) ?>
                                    </span>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="text-muted fs-3">Base Imponible</span>
                                    <span class="text-white fw-semibold fs-4"><?= number_format($subtotal, 2, ',', '.') ?> €</span>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <span class="text-muted fs-3">IVA (21%)</span>
                                    <span class="text-white fw-semibold fs-4"><?= number_format($iva, 2, ',', '.') ?> €</span>
                                </div>
                                
                                <div class="p-3 bg-primary bg-opacity-10 rounded-3 border border-primary border-opacity-25">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-primary fw-bold fs-4">Total Facturado</span>
                                        <span class="text-primary fw-bolder fs-5"><?= number_format($total, 2, ',', '.') ?> €</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Formulario oculto para el envío del correo -->
<form id="emailForm" action="<?= site_url('orders/billing/email') ?>" method="post" class="d-none">
    <?= csrf_field() ?>
    <input type="hidden" name="mes" value="<?= $mes ?>">
    <input type="hidden" name="year" value="<?= $year ?>">
    <input type="hidden" name="email_destino" id="emailDestinoInput">
    <?php if($canViewAll && !empty($selectedUserId)): ?>
        <input type="hidden" name="user_id" value="<?= $selectedUserId ?>">
    <?php endif; ?>
</form>

<script>
/**
 * Muestra un modal SweetAlert2 preguntando el correo electrónico del destinatario
 * antes de enviar la facturación mensual.
 */
function confirmarEnvioCorreo() {
    Swal.fire({
        title: 'Enviar Reporte Mensual',
        text: 'Introduce la dirección de correo a la que deseas enviar la facturación mensual de este periodo (PDF adjunto):',
        input: 'email',
        inputPlaceholder: 'correo@ejemplo.com',
        inputValue: '<?= auth()->user()->getIdentities()[0]->secret ?? "" ?>', // Pre-cargar el correo del técnico por defecto
        showCancelButton: true,
        confirmButtonText: 'Enviar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true,
        customClass: {
            confirmButton: 'btn btn-primary ms-2',
            cancelButton: 'btn btn-outline-primary'
        },
        buttonsStyling: false,
        inputValidator: (value) => {
            if (!value) {
                return '¡Debes escribir un correo electrónico!';
            }
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                return 'Por favor, introduce un correo electrónico válido.';
            }
        }
    }).then((result) => {
        if (result.isConfirmed && result.value) {
            // Mostrar estado de carga en el modal
            Swal.fire({
                title: 'Enviando Reporte...',
                text: 'Por favor, espera un momento.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Asignar el correo al input oculto y enviar el formulario
            document.getElementById('emailDestinoInput').value = result.value;
            document.getElementById('emailForm').submit();
        }
    });
}
</script>

<!-- Select2 initialization -->
<script>
// Inicializar Select2 al cargar el DOM
document.addEventListener('DOMContentLoaded', function() {
    if (typeof $ !== 'undefined' && $.fn.select2) {
        $('.select2').select2({
            width: '100%',
            placeholder: 'Todos los técnicos',
            allowClear: true
        }).on('change', function() {
            $('#billingFilterForm').submit();
        });
    }
});
</script>
