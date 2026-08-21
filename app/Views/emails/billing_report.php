<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light">
    <meta name="supported-color-schemes" content="light">
    <title>Reporte de Órdenes de Trabajo</title>
    <style>
        :root {
            color-scheme: light;
            supported-color-schemes: light;
        }
    </style>
</head>
<body style="margin: 0; padding: 0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #ffffff; color: #333333;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #ffffff; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="600" border="0" cellspacing="0" cellpadding="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #5d87ff; padding: 30px 40px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 24px; font-weight: 600;">OtGest</h1>
                            <p style="color: #e0e7ff; margin: 5px 0 0 0; font-size: 14px;">Reporte de Órdenes de Trabajo</p>
                        </td>
                    </tr>
                    
                    <!-- Body -->
                    <tr>
                        <td style="padding: 40px;">
                            <p style="font-size: 16px; line-height: 1.5; margin-top: 0;">Hola,</p>
                            <p style="font-size: 16px; line-height: 1.5; margin-bottom: 30px;">
                                Adjunto encontrarás el reporte de órdenes de trabajo correspondiente al mes de <strong><?= esc($nombreMes) ?> de <?= esc($year) ?></strong> generado por el técnico <strong><?= esc($tecnicoNombre) ?></strong>.
                            </p>
                            
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #f8f9fa; border-radius: 6px; border: 1px solid #e9ecef;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <h3 style="margin: 0 0 15px 0; font-size: 16px; color: #495057; border-bottom: 1px solid #e9ecef; padding-bottom: 10px;">Resumen del Periodo</h3>
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td style="padding: 8px 0; color: #6c757d; font-size: 14px;">Instalaciones realizadas:</td>
                                                <td style="padding: 8px 0; font-weight: 600; text-align: right; color: #212529; font-size: 14px;"><?= count($orders) ?></td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0; color: #6c757d; font-size: 14px;">Base Imponible:</td>
                                                <td style="padding: 8px 0; font-weight: 600; text-align: right; color: #212529; font-size: 14px;"><?= number_format($subtotal, 2, ',', '.') ?> &euro;</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0; color: #6c757d; font-size: 14px;">IVA (21%):</td>
                                                <td style="padding: 8px 0; font-weight: 600; text-align: right; color: #212529; font-size: 14px;"><?= number_format($iva, 2, ',', '.') ?> &euro;</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="border-top: 1px solid #e9ecef; margin-top: 5px; padding-top: 5px;"></td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 12px 0 0 0; color: #5d87ff; font-weight: bold; font-size: 16px;">Total Acumulado:</td>
                                                <td style="padding: 12px 0 0 0; font-weight: bold; text-align: right; color: #5d87ff; font-size: 16px;"><?= number_format($total, 2, ',', '.') ?> &euro;</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            
                            <p style="font-size: 14px; color: #6c757d; margin-top: 30px; line-height: 1.5;">
                                El documento PDF con el desglose detallado de todas las instalaciones se encuentra adjunto a este correo.
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8f9fa; border-top: 1px solid #e9ecef; padding: 20px 40px; text-align: center;">
                            <p style="margin: 0; font-size: 12px; color: #adb5bd; line-height: 1.5;">
                                <strong>Aviso Importante:</strong> Este es un correo generado automáticamente por el sistema OtGest para el envío de reportes de facturación.<br>
                                Por favor, no respondas a esta dirección de correo, ya que es una bandeja de salida no monitorizada y no podremos leer tu mensaje.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
