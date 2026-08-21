<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Reporte de Instalaciones - <?= $mes ?>/<?= $year ?></title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            font-size: 10px;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .header {
            margin-bottom: 20px;
            border-bottom: 2px solid #5d87ff;
            padding-bottom: 10px;
        }
        .header table {
            width: 100%;
        }
        .header .logo {
            font-size: 24px;
            font-weight: bold;
            color: #5d87ff;
        }
        .header .report-title {
            text-align: right;
            font-size: 16px;
            font-weight: bold;
            color: #555;
            text-transform: uppercase;
        }
        .info-table {
            width: 100%;
            margin-bottom: 15px;
        }
        .info-table td {
            padding: 2px 0;
        }
        .info-title {
            font-weight: bold;
            color: #555;
            width: 150px;
        }
        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table-data th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            color: #495057;
            font-weight: bold;
            text-align: left;
            padding: 4px 6px;
            text-transform: uppercase;
            font-size: 10px;
        }
        .table-data td {
            padding: 4px 6px;
            border-bottom: 1px solid #dee2e6;
        }
        .table-data tr:nth-child(even) {
            background-color: #fafafa;
        }
        .text-right {
            text-align: right;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 9px;
            font-weight: bold;
            color: #fff;
            background-color: #6c757d;
            border-radius: 4px;
        }
        .totals-table {
            width: 300px;
            margin-left: auto;
            border-top: 2px solid #dee2e6;
            margin-top: 15px;
        }
        .totals-table td {
            padding: 4px 6px;
            font-size: 12px;
        }
        .totals-table .grand-total {
            font-size: 14px;
            font-weight: bold;
            color: #5d87ff;
            border-top: 1px solid #dee2e6;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            border-top: 1px solid #dee2e6;
            padding-top: 10px;
            font-size: 10px;
            color: #777;
        }
    </style>
</head>
<body>

    <!-- CABECERA -->
    <div class="header">
        <table>
            <tr>
                <td class="logo">OtGest</td>
                <td class="report-title">Reporte Mensual de Instalaciones</td>
            </tr>
        </table>
    </div>

    <!-- INFORMACIÓN GENERAL -->
    <table class="info-table">
        <tr>
            <td class="info-title">Técnico:</td>
            <td><?= esc($tecnicoNombre) ?></td>
            <td class="info-title" style="text-align: right;">Periodo:</td>
            <td style="text-align: right;">
                <?php
                $meses = [
                    '01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', '04' => 'Abril',
                    '05' => 'Mayo', '06' => 'Junio', '07' => 'Julio', '08' => 'Agosto',
                    '09' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'
                ];
                echo ($meses[$mes] ?? $mes) . ' de ' . $year;
                ?>
            </td>
        </tr>
        <tr>
            <td class="info-title">Fecha de Reporte:</td>
            <td><?= date('d/m/Y') ?></td>
            <td class="info-title" style="text-align: right;">Total de Órdenes:</td>
            <td style="text-align: right; font-weight: bold;"><?= count($orders) ?></td>
        </tr>
    </table>

    <!-- TABLA DE ORDENES -->
    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 12%;">Fecha</th>
                <th style="width: 12%;">Número OT</th>
                <th style="width: 15%; text-align: center;">Operadora</th>
                <th style="width: 15%; text-align: center;">Tipo</th>
                <th style="width: 34%;">Cliente</th>
                <th style="width: 12%; text-align: right;">Importe</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($orders)): ?>
                <?php foreach($orders as $ord): ?>
                    <tr>
                        <td><?= date('d/m/Y', strtotime($ord['ot_fecha'])) ?></td>
                        <td><strong><?= esc($ord['ot_numero']) ?></strong></td>
                        <td style="text-align: center;"><?= esc($ord['ot_operadora'] ?: 'N/D') ?></td>
                        <td style="text-align: center;"><?= esc($ord['ot_tipo']) ?></td>
                        <td><?= esc($ord['ot_cliente']) ?></td>
                        <td class="text-right"><strong><?= number_format($ord['ot_precio'], 2, ',', '.') ?> €</strong></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px; color: #777;">
                        No se encontraron instalaciones registradas en este periodo.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- TOTALES -->
    <table class="totals-table">
        <tr>
            <td>Base Imponible:</td>
            <td class="text-right"><?= number_format($subtotal, 2, ',', '.') ?> €</td>
        </tr>
        <tr>
            <td>IVA (21%):</td>
            <td class="text-right"><?= number_format($iva, 2, ',', '.') ?> €</td>
        </tr>
        <tr class="grand-total">
            <td><strong>TOTAL:</strong></td>
            <td class="text-right"><strong><?= number_format($total, 2, ',', '.') ?> €</strong></td>
        </tr>
    </table>

    <!-- PIE DE PAGINA -->
    <div class="footer">
        Reporte generado automáticamente por el sistema OtGest. Documento justificativo de instalaciones mensuales.
    </div>

</body>
</html>
