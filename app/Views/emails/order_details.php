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
                            <h2 style="color: #333f52; -webkit-text-fill-color: #333f52; margin-top: 0; text-align: center; font-weight: 600;">Detalles de Orden de Trabajo</h2>
                            <p style="color: #5a6a85; -webkit-text-fill-color: #5a6a85; font-size: 16px; line-height: 1.6; text-align: center; margin-bottom: 25px;">
                                Se ha adjuntado la información correspondiente a la orden solicitada.
                            </p>
                            <div style="background-color: #ffffff; border-radius: 8px; border: 1px solid #e9ecef; padding: 20px; margin: 25px 0;">
                                <p style="margin: 0 0 10px 0; font-size: 16px; font-weight: bold; color: #2A3547; -webkit-text-fill-color: #2A3547;">Nº OT: <?= esc($order['ot_numero']) ?></p>
                                <p style="margin: 0 0 10px 0; font-size: 14px; color: #5a6a85; -webkit-text-fill-color: #5a6a85;"><strong>Cliente:</strong> <?= esc($order['ot_cliente']) ?></p>
                                <p style="margin: 0 0 10px 0; font-size: 14px; color: #5a6a85; -webkit-text-fill-color: #5a6a85;"><strong>Operadora:</strong> <?= esc($order['ot_operadora']) ?></p>
                                <p style="margin: 0 0 10px 0; font-size: 14px; color: #5a6a85; -webkit-text-fill-color: #5a6a85;"><strong>Dirección:</strong> <?= esc($order['ot_direccion']) ?></p>
                                <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #e9ecef; font-size: 14px; color: #5a6a85; -webkit-text-fill-color: #5a6a85; white-space: pre-wrap;"><strong>Comentarios:</strong><br><?= esc($order['ot_txt']) ?></div>
                            </div>
                        </td>
                    </tr>
                </table>
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top: 20px;">
                    <tr>
                        <td align="center" style="padding: 0 20px;">
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
