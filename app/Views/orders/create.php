<!-- =====================================================================
     CABECERA Y BREADCRUMB (NAVEGACIÓN)
     ===================================================================== -->
<div class="card border shadow-none position-relative overflow-hidden mb-4">
  <div class="card-body px-4 py-3">
    <div class="row align-items-center">
      <div class="col-12">
        <h4 class="fw-semibold mb-8">Nueva Orden</h4>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <a class="text-muted text-decoration-none" href="<?= site_url() ?>">Inicio</a>
            </li>
            <li class="breadcrumb-item">
              <a class="text-muted text-decoration-none" href="<?= site_url('orders') ?>">Órdenes</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">Crear</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
</div>

<!-- =====================================================================
     FORMULARIO DE CREACIÓN DE ORDEN
     ===================================================================== -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="<?= site_url('orders/store') ?>" method="post" autocomplete="off">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="ot_operadora" class="form-label">Operadora</label>
                            <select class="form-select" id="ot_operadora" name="ot_operadora">
                                <option value="">Seleccione...</option>
                                <?php 
                                    $operadoras = ['DIGI', 'FINETWORK', 'JAZZTEL', 'LOWI', 'MASMOVIL', 'MOVISTAR', 'O2', 'ORANGE', 'PEPEPHONE', 'R-CABLE', 'VIRGIN', 'VODAFONE', 'YOIGO'];
                                    $selected_op = old('ot_operadora');
                                    foreach($operadoras as $op): 
                                ?>
                                    <option value="<?= $op ?>" <?= ($selected_op == $op) ? 'selected' : '' ?>><?= $op ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="ot_numero" class="form-label">Número de Orden</label>
                            <input type="text" class="form-control" id="ot_numero" name="ot_numero" value="<?= old('ot_numero') ?>">
                            <div id="ot_numero_feedback" class="text-primary mt-1 d-none" style="font-size: 0.875em;"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="ot_cliente" class="form-label">Cliente</label>
                            <input type="text" class="form-control" id="ot_cliente" name="ot_cliente" value="<?= old('ot_cliente') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="ot_direccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="ot_direccion" name="ot_direccion" value="<?= old('ot_direccion') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="ot_tipo" class="form-label">Tipo de Trabajo</label>
                            <select class="form-select" id="ot_tipo" name="ot_tipo">
                                <option value="">Seleccione...</option>
                                <?php 
                                    $tipos = [
                                        'INSTALACION' => 'Instalación', 
                                        'AVERIA' => 'Avería', 
                                        'MODIFICACION' => 'Modificación', 
                                        'TRASLADO' => 'Traslado', 
                                        'BAJA' => 'Baja', 
                                        'AUDITORIA' => 'Auditoría', 
                                        'PORTABILIDAD' => 'Portabilidad'
                                    ];
                                    $selected_tipo = old('ot_tipo');
                                    foreach($tipos as $val => $label): 
                                ?>
                                    <option value="<?= $val ?>" <?= ($selected_tipo == $val) ? 'selected' : '' ?>><?= $label ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="ot_estado" class="form-label">Estado de la Orden</label>
                            <?php $estado = old('ot_estado', 1); ?>
                            <select class="form-select" id="ot_estado" name="ot_estado">
                                <option value="1" <?= ($estado == 1) ? 'selected' : '' ?>>Finalizada</option>
                                <option value="2" <?= ($estado == 2) ? 'selected' : '' ?>>Escalada</option>
                                <option value="3" <?= ($estado == 3) ? 'selected' : '' ?>>Incidencia</option>
                                <option value="4" <?= ($estado == 4) ? 'selected' : '' ?>>Anulada</option>
                            </select>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="template_selector" class="form-label">Plantilla</label>
                            <select class="form-select" id="template_selector" onchange="insertTemplate()">
                                <option value="">Seleccione una plantilla...</option>
                                <?php if (!empty($templates)): ?>
                                    <?php foreach($templates as $tpl): ?>
                                        <option value="<?= htmlspecialchars($tpl['plantilla_txt']) ?>"><?= esc($tpl['plantilla_nombre']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="col-12 mb-4">
                            <div class="d-flex flex-wrap justify-content-between align-items-end mb-2 gap-2">
                                <label for="ot_txt" class="form-label mb-0">Comentarios Técnicos</label>
                                <div class="d-flex gap-2 align-items-center">
                                    <button type="button" class="btn btn-sm btn-primary" onclick="convertToUppercase()">
                                        <i class="ti ti-letter-case-upper"></i> Mayúsculas
                                    </button>
                                </div>
                            </div>
                            <textarea class="form-control font-monospace text-warning bg-dark" id="ot_txt" name="ot_txt" rows="6"><?= old('ot_txt') ?></textarea>
                        </div>

                        <div class="col-12 mb-4">
                            <div class="form-check form-switch px-4 py-3">
                                <input class="form-check-input switch-custom-size" type="checkbox" role="switch" id="ot_imputada" name="ot_imputada" value="1" <?= (old('ot_imputada') == 1) ? 'checked' : '' ?>>
                                <label class="form-check-label ms-2 pt-0 fw-semibold cursor-pointer" for="ot_imputada">
                                    Orden Imputada en Almacén
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center mt-4 gap-2 border-top pt-4">
                        <a href="<?= site_url('orders') ?>" class="btn btn-outline-primary px-4 me-2">
                            <i class="ti ti-arrow-left me-1"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="ti ti-device-floppy me-1"></i>Crear Orden
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function convertToUppercase() {
    const textarea = document.getElementById('ot_txt');
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;

    const text = textarea.value;
    const urlRegex = /(https?:\/\/[^\s]+)/g;
    const parts = text.split(urlRegex);
    const result = parts.map(part => {
        if (part.match(urlRegex)) return part;
        return part.toUpperCase();
    }).join('');

    textarea.value = result;
    textarea.setSelectionRange(start, end);
    textarea.focus();
    if(typeof autoResizeTextarea === 'function') autoResizeTextarea(textarea);
}

function insertTemplate() {
    const selector = document.getElementById('template_selector');
    const textarea = document.getElementById('ot_txt');
    if (selector.value) {
        if (textarea.value.trim() !== '') {
            textarea.value += '\n' + selector.value;
        } else {
            textarea.value = selector.value;
        }
        selector.value = ''; // Reset
        if(typeof autoResizeTextarea === 'function') autoResizeTextarea(textarea);
    }
}

// Auto-resize para el textarea de comentarios
function autoResizeTextarea(el) {
    el.style.overflow = 'hidden';
    el.style.height = 'auto';
    el.style.height = el.scrollHeight + 'px';
}
document.addEventListener('DOMContentLoaded', function() {
    const tx = document.getElementById('ot_txt');
    if (tx) {
        tx.addEventListener('input', function() { autoResizeTextarea(this); });
        // Ajuste inicial si hay contenido cargado
        setTimeout(() => autoResizeTextarea(tx), 100);
    }

    // Validación de número de orden duplicado en tiempo real
    const inputNumero = document.getElementById('ot_numero');
    const feedback = document.getElementById('ot_numero_feedback');
    const submitBtn = document.querySelector('button[type="submit"]');
    let timeout = null;
    let currentCsrf = '<?= csrf_hash() ?>';

    if (inputNumero) {
        inputNumero.addEventListener('input', function() {
            clearTimeout(timeout);
            const numero = this.value.trim();
            
            if (numero.length < 3) {
                inputNumero.style.borderColor = '';
                if (feedback) feedback.classList.add('d-none');
                if (submitBtn) submitBtn.disabled = false;
                return;
            }

            timeout = setTimeout(() => {
                fetch('<?= site_url('orders/check-numero') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: new URLSearchParams({
                        '<?= csrf_token() ?>': currentCsrf,
                        'ot_numero': numero
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.csrfToken) {
                        currentCsrf = data.csrfToken; // Actualizamos el token para la siguiente llamada
                    }
                    if (data.status === 'error') {
                        inputNumero.style.borderColor = 'var(--bs-primary)';
                        if (feedback) {
                            feedback.innerHTML = `<a href="<?= site_url('orders/show/') ?>${data.order_id}" class="text-primary text-decoration-none">${data.message}</a>`;
                            feedback.classList.remove('d-none');
                        }
                        if (submitBtn) submitBtn.disabled = true;
                    } else {
                        inputNumero.style.borderColor = '';
                        if (feedback) feedback.classList.add('d-none');
                        if (submitBtn) submitBtn.disabled = false;
                    }
                })
                .catch(error => console.error('Error:', error));
            }, 500); // 500ms debounce
        });
    }
});
</script>
