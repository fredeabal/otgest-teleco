<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light">
    <meta name="supported-color-schemes" content="light">
    <style>
        :root { color-scheme: light; }
    </style>
</head>
<body style="background-color: #ffffff; margin: 0; padding: 0; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #ffffff; background-image: linear-gradient(#ffffff, #ffffff); margin: 0; padding: 40px 20px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
        <tr>
            <td align="center">
                <img src="<?= $logoUrl ?>" alt="Logo" style="max-width: 180px; margin-bottom: 30px; display: block;">
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f8f9fa; background-image: linear-gradient(#f8f9fa, #f8f9fa); border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #e9ecef;">
                    <tr>
                        <td align="left" style="padding: 40px;">
                            <h2 style="color: #333f52; -webkit-text-fill-color: #333f52; margin-top: 0; text-align: center; font-weight: 600;">Reporte de Facturación</h2>
                            <p style="color: #5a6a85; -webkit-text-fill-color: #5a6a85; font-size: 16px; line-height: 1.6; text-align: center; margin-bottom: 25px;">
                                Adjunto encontrarás el reporte de órdenes de trabajo correspondiente al mes de <strong><?= esc($nombreMes) ?> de <?= esc($year) ?></strong> generado por el técnico <strong><?= esc($tecnicoNombre) ?></strong>.
                            </p>
                            
                            <div style="background-color: #ffffff; border-radius: 8px; border: 1px solid #e9ecef; padding: 20px; margin: 25px 0;">
                                <p style="margin: 0 0 15px 0; font-size: 16px; font-weight: bold; color: #2A3547; -webkit-text-fill-color: #2A3547; border-bottom: 1px solid #e9ecef; padding-bottom: 10px;">Resumen del Periodo</p>
                                
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td style="padding: 8px 0; font-size: 14px; color: #5a6a85; -webkit-text-fill-color: #5a6a85;">Instalaciones realizadas:</td>
                                        <td style="padding: 8px 0; font-size: 14px; font-weight: bold; text-align: right; color: #2A3547; -webkit-text-fill-color: #2A3547;"><?= count($orders) ?></td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 8px 0; font-size: 14px; color: #5a6a85; -webkit-text-fill-color: #5a6a85;">Base Imponible:</td>
                                        <td style="padding: 8px 0; font-size: 14px; font-weight: bold; text-align: right; color: #2A3547; -webkit-text-fill-color: #2A3547;"><?= number_format($subtotal, 2, ',', '.') ?> &euro;</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 8px 0; font-size: 14px; color: #5a6a85; -webkit-text-fill-color: #5a6a85;">IVA (21%):</td>
                                        <td style="padding: 8px 0; font-size: 14px; font-weight: bold; text-align: right; color: #2A3547; -webkit-text-fill-color: #2A3547;"><?= number_format($iva, 2, ',', '.') ?> &euro;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="border-top: 1px solid #e9ecef; margin-top: 5px; padding-top: 5px;"></td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 12px 0 0 0; color: #5d87ff; font-weight: bold; font-size: 16px; -webkit-text-fill-color: #5d87ff;">Total Acumulado:</td>
                                        <td style="padding: 12px 0 0 0; font-weight: bold; text-align: right; color: #5d87ff; font-size: 16px; -webkit-text-fill-color: #5d87ff;"><?= number_format($total, 2, ',', '.') ?> &euro;</td>
                                    </tr>
                                </table>
                            </div>
                            
                            <p style="color: #8c98a4; -webkit-text-fill-color: #8c98a4; font-size: 13px; line-height: 1.5; text-align: center; margin-top: 25px; margin-bottom: 0;">
                                El documento PDF con el desglose detallado de todas las instalaciones se encuentra adjunto a este correo.
                            </p>
                        </td>
                    </tr>
                </table>
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top: 20px;">
                    <tr>
                        <td align="center" style="padding: 0 20px;">
                            <p style="color: #8c98a4; -webkit-text-fill-color: #8c98a4; font-size: 11px; line-height: 1.5; margin: 0 0 10px 0;">
                                <strong>Aviso Importante:</strong> Este es un correo generado automáticamente por el sistema OtGest para el envío de reportes de facturación.<br>
                                Por favor, no respondas a esta dirección de correo, ya que es una bandeja de salida no monitorizada.
                            </p>
                            <p style="color: #8c98a4; -webkit-text-fill-color: #8c98a4; font-size: 11px; line-height: 1.5; margin: 0;">
                                &copy; <?= date('Y') ?> OtGest
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
