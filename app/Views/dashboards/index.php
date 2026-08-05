<!-- =====================================================================
     CABECERA Y BREADCRUMB (NAVEGACIÓN)
     ===================================================================== -->
<div class="card border shadow-none position-relative overflow-hidden mb-4">
  <div class="card-body px-4 py-3">
    <div class="row align-items-center">
      <div class="col-sm-6 col-12 mb-3 mb-sm-0">
        <h4 class="fw-semibold mb-8">Panel de Usuario</h4>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <a class="text-muted text-decoration-none" href="<?= base_url() ?>">Inicio</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">Dashboard</li>
          </ol>
        </nav>
      </div>
      <div class="col-sm-6 col-12 d-flex justify-content-sm-end align-items-center">
        <form method="get" action="<?= base_url('dashboard') ?>" id="dashboardFilterForm" autocomplete="off">
          <div class="input-group input-group-sm">
            <select name="filter" class="form-select" onchange="toggleCustomDates(this.value)" style="max-width: 180px;">
              <option value="total" <?= ($currentFilter ?? '') === 'total' ? 'selected' : '' ?>>Histórico (Todo)</option>
              <option value="12months" <?= ($currentFilter ?? '') === '12months' ? 'selected' : '' ?>>12 Meses</option>
              <option value="day" <?= ($currentFilter ?? '') === 'day' ? 'selected' : '' ?>>Hoy</option>
              <option value="month" <?= ($currentFilter ?? 'month') === 'month' ? 'selected' : '' ?>>Este Mes</option>
              <option value="year" <?= ($currentFilter ?? '') === 'year' ? 'selected' : '' ?>>Este Año</option>
              <option value="custom" <?= ($currentFilter ?? '') === 'custom' ? 'selected' : '' ?>>Personalizado</option>
            </select>
            
            <input type="text" name="start_date" id="start_date" class="form-control mydatepicker custom-date-input" placeholder="Inicio" value="<?= esc($startDate ?? '') ?>" style="display: <?= ($currentFilter ?? '') === 'custom' ? 'block' : 'none' ?> !important; max-width: 120px;">
            <input type="text" name="end_date" id="end_date" class="form-control mydatepicker custom-date-input" placeholder="Fin" value="<?= esc($endDate ?? '') ?>" style="display: <?= ($currentFilter ?? '') === 'custom' ? 'block' : 'none' ?> !important; max-width: 120px;">
            
            <button type="submit" class="btn btn-primary"><i class="ti ti-filter"></i></button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
function toggleCustomDates(val) {
    const inputs = document.querySelectorAll('.custom-date-input');
    inputs.forEach(input => {
        if(val === 'custom') {
            input.style.setProperty('display', 'block', 'important');
        } else {
            input.style.setProperty('display', 'none', 'important');
        }
    });
}
</script>

<!-- =====================================================================
     TARJETAS DE ESTADÍSTICAS (MÉTRICAS DEL USUARIO)
     ===================================================================== -->
<div class="row">
    <!-- Card Instalaciones -->
    <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
        <div class="card bg-card-instalaciones overflow-hidden h-100 rounded-3 shadow-sm position-relative border-0">
            <div class="card-body dashboard-card-content p-4">
                <h6 class="text-white fw-medium mb-1 fs-4">Instalaciones</h6>
                <h2 class="text-white fw-bolder mb-0 display-6"><?= esc($metrics['INSTALACION'] ?? 0) ?></h2>
            </div>
            <i class="ti ti-hammer dashboard-card-icon text-white"></i>
        </div>
    </div>

    <!-- Card Averías -->
    <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
        <div class="card bg-card-averias overflow-hidden h-100 rounded-3 shadow-sm position-relative border-0">
            <div class="card-body dashboard-card-content p-4">
                <h6 class="text-white fw-medium mb-1 fs-4">Averías</h6>
                <h2 class="text-white fw-bolder mb-0 display-6"><?= esc($metrics['AVERIA'] ?? 0) ?></h2>
            </div>
            <i class="ti ti-tool dashboard-card-icon text-white"></i>
        </div>
    </div>

    <!-- Card Modificaciones -->
    <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
        <div class="card bg-card-modificaciones overflow-hidden h-100 rounded-3 shadow-sm position-relative border-0">
            <div class="card-body dashboard-card-content p-4">
                <h6 class="text-white fw-medium mb-1 fs-4">Modificaciones</h6>
                <h2 class="text-white fw-bolder mb-0 display-6"><?= esc($metrics['MODIFICACION'] ?? 0) ?></h2>
            </div>
            <i class="ti ti-settings dashboard-card-icon text-white"></i>
        </div>
    </div>

    <!-- Card Traslados -->
    <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
        <div class="card bg-card-traslados overflow-hidden h-100 rounded-3 shadow-sm position-relative border-0">
            <div class="card-body dashboard-card-content p-4">
                <h6 class="text-white fw-medium mb-1 fs-4">Traslados</h6>
                <h2 class="text-white fw-bolder mb-0 display-6"><?= esc($metrics['TRASLADO'] ?? 0) ?></h2>
            </div>
            <i class="ti ti-truck dashboard-card-icon text-white"></i>
        </div>
    </div>

    <!-- Card Portabilidad -->
    <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
        <div class="card bg-card-portabilidad overflow-hidden h-100 rounded-3 shadow-sm position-relative border-0">
            <div class="card-body dashboard-card-content p-4">
                <h6 class="text-white fw-medium mb-1 fs-4">Portabilidad</h6>
                <h2 class="text-white fw-bolder mb-0 display-6"><?= esc($metrics['PORTABILIDAD'] ?? 0) ?></h2>
            </div>
            <i class="ti ti-arrows-left-right dashboard-card-icon text-white"></i>
        </div>
    </div>

    <!-- Card Bajas -->
    <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
        <div class="card bg-card-bajas overflow-hidden h-100 rounded-3 shadow-sm position-relative border-0">
            <div class="card-body dashboard-card-content p-4">
                <h6 class="text-white fw-medium mb-1 fs-4">Bajas</h6>
                <h2 class="text-white fw-bolder mb-0 display-6"><?= esc($metrics['BAJA'] ?? 0) ?></h2>
            </div>
            <i class="ti ti-mood-sad dashboard-card-icon text-white"></i>
        </div>
    </div>

    <!-- Card Auditorias -->
    <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
        <div class="card bg-card-auditorias overflow-hidden h-100 rounded-3 shadow-sm position-relative border-0">
            <div class="card-body dashboard-card-content p-4">
                <h6 class="text-white fw-medium mb-1 fs-4">Auditorias</h6>
                <h2 class="text-white fw-bolder mb-0 display-6"><?= esc($metrics['AUDITORIA'] ?? 0) ?></h2>
            </div>
            <i class="ti ti-eye dashboard-card-icon text-white"></i>
        </div>
    </div>

    <!-- Card Total -->
    <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
        <div class="card bg-card-total overflow-hidden h-100 rounded-3 shadow-sm position-relative border-0">
            <div class="card-body dashboard-card-content p-4">
                <h6 class="text-white fw-medium mb-1 fs-4">Total</h6>
                <h2 class="text-white fw-bolder mb-0 display-6"><?= esc($metrics['TOTAL'] ?? 0) ?></h2>
            </div>
            <i class="ti ti-box dashboard-card-icon text-white"></i>
        </div>
    </div>
</div>

<!-- =====================================================================
     GRÁFICO ESTADÍSTICO
     ===================================================================== -->
<div class="row mt-2">
    <div class="col-12">
        <div class="card border shadow-none">
            <div class="card-body p-4">
                <?php
                  $titles = [
                      'day' => 'Estadísticas (Hoy)',
                      'month' => 'Estadísticas (Este Mes)',
                      'year' => 'Estadísticas (Este Año)',
                      'custom' => 'Estadísticas (Periodo Personalizado)',
                      '12months' => 'Estadísticas (Últimos 12 meses)',
                      'total' => 'Estadísticas Globales (Histórico)'
                  ];
                  $chartTitle = $titles[$currentFilter ?? 'month'] ?? 'Estadísticas Globales';
                ?>
                <h5 class="card-title fw-semibold mb-4"><?= esc($chartTitle) ?></h5>
                <div id="stats-chart"></div>
            </div>
        </div>
    </div>
</div>

<!-- Importar ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var chartLabels = <?= $chartLabels ?? '[]' ?>;
    var chartData = <?= $chartData ?? '[]' ?>;

    var isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
    var foreColor = isDark ? '#a1aab2' : '#5a6a85';
    var gridBorderColor = isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)';

    var options = {
        series: [{
            name: "Órdenes",
            data: chartData
        }],
        chart: {
            height: 350,
            type: 'area',
            fontFamily: "'Plus Jakarta Sans', sans-serif",
            foreColor: foreColor,
            toolbar: { show: false },
            zoom: { enabled: false },
            background: 'transparent',
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800
            }
        },
        colors: ['#7267EF'],
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.4,
                opacityTo: 0.05,
                stops: [0, 90, 100]
            }
        },
        markers: {
            size: 5,
            colors: ["#fff"],
            strokeColors: "#7267EF",
            strokeWidth: 2,
            hover: { size: 7 }
        },
        xaxis: {
            categories: chartLabels,
            axisBorder: { show: false },
            axisTicks: { show: false },
            tooltip: { enabled: false },
            labels: {
                style: {
                    colors: foreColor
                }
            }
        },
        yaxis: {
            labels: {
                style: {
                    colors: foreColor
                }
            }
        },
        grid: {
            borderColor: gridBorderColor,
            strokeDashArray: 0,
            yaxis: { lines: { show: true } }
        },
        theme: {
            mode: isDark ? 'dark' : 'light'
        },
        tooltip: {
            theme: isDark ? 'dark' : 'light'
        }
    };

    var chart = new ApexCharts(document.querySelector("#stats-chart"), options);
    chart.render();
});
</script>
