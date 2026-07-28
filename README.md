# 🚀 FileCrew - Compartición y Transferencia de Archivos Segura

Plataforma de compartición y transferencia de archivos autohospedada basada en PHP (CodeIgniter 4) y SQLite3. FileCrew te permite alojar tu propio servidor para compartir documentos y archivos, manteniendo el control total sobre tus datos.

---

## Características Principales

* 🔗 **Compartir por Enlace**: Sube archivos y genera un enlace directo para que otras personas puedan descargarlos de manera sencilla.
* 📦 **Tamaño Ilimitado**: Sin restricciones artificiales de tamaño de archivo. El único límite es el espacio físico que tengas disponible en tu propio disco duro.
* ⏳ **Control de Caducidad**: Configura una fecha de expiración para que los enlaces compartidos dejen de funcionar automáticamente pasado cierto tiempo.
* 🔒 **Seguridad y Privacidad**: Protege tus descargas mediante contraseña y establece un límite máximo de visitantes/descargas (para que el enlace caduque después de un número específico de descargas). Eliges todo esto al momento de subir: público o privado, con o sin contraseña, y la caducidad.
* 📧 **Envío por Correo**: Envía los enlaces de descarga directamente a los destinatarios a través de correo electrónico desde la plataforma.

---

## Stack Tecnológico

- **Backend Core**: CodeIgniter 4 (PHP 8.2+)
- **Autenticación**: CodeIgniter Shield (Usuarios y Roles RBAC)
- **Base de Datos**: SQLite3
- **Frontend**: Bootstrap 5, SweetAlert2, Tabler Icons
