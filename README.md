# 🚀 FileCrew - Intercambio y Transferencia de Archivos Segura

<p align="center">
  <img src="public/assets/images/logos/light-logo.svg" alt="FileCrew Logo" width="280">
  <br>
  <b>Plataforma de intercambio y transferencia de archivos autohospedada, basada en PHP (CodeIgniter 4) y SQLite3.</b>
  <br><br>
  <a href="https://github.com/fredeabal/filecrew/releases"><img src="https://img.shields.io/badge/version-1.0.0-orange.svg" alt="Version 1.0.0"></a>
  <a href="https://www.php.net/"><img src="https://img.shields.io/badge/PHP-8.2%2B-777BB4.svg" alt="PHP 8.2+"></a>
  <a href="https://codeigniter.com/"><img src="https://img.shields.io/badge/CodeIgniter-4.x-DD4814.svg" alt="CodeIgniter 4"></a>
  <a href="LICENSE"><img src="https://img.shields.io/badge/License-MIT-blue.svg" alt="License MIT"></a>
</p>

---

## 🚀 Instalación en 1 Paso

En cualquier servidor **Debian 11/12** o **Ubuntu 20.04 / 22.04 / 24.04** limpio con acceso root (VPS o máquina virtual), ejecuta el siguiente comando en tu terminal:

```bash
bash <(curl -s https://raw.githubusercontent.com/fredeabal/filecrew/main/install.sh)
```

El script se encargará automáticamente de todo el proceso de instalación y configuración del servidor web, dependencias y base de datos.

### 🔑 Credenciales por Defecto tras la Instalación
* **URL:** `http://TU_IP_O_DOMINIO`
* **Email:** `admin@demo.com`
* **Password:** `admin1234`

### ⚙️ Configuración Post-Instalación Recomendada

Para el correcto funcionamiento de todas las características de FileCrew, se recomienda configurar:

#### 1. 📧 Configuración de Correo (SMTP)
FileCrew utiliza el servicio de correo para enviar enlaces de descarga directamente a los destinatarios desde el panel y para la recuperación de contraseñas de los usuarios.
1. Ve a **Ajustes SMTP** (o en la sección de administración del sistema) en la barra lateral/menú.
2. Introduce los datos de tu servidor de correo (Host, Puerto, Usuario, Contraseña y tipo de encriptación TLS/SSL).
3. Utiliza la opción **Enviar Correo de Prueba** para verificar que la configuración sea correcta.

---

## 🔄 Actualizar a la última versión

Para actualizar un servidor FileCrew existente a la última versión disponible (sin perder tus archivos compartidos, configuraciones o base de datos), simplemente ejecuta:

```bash
bash <(curl -s https://raw.githubusercontent.com/fredeabal/filecrew/main/update.sh)
```

---

## 💡 ¿Qué es FileCrew?

**FileCrew** es una plataforma autohospedada que te permite alojar tu propio servidor para compartir documentos y archivos con total seguridad y privacidad. Evita los límites artificiales de los proveedores de almacenamiento en la nube públicos y mantén el control absoluto de tus datos físicos en tu propio hardware.

---

## ✨ Características Principales

* 🔗 **Compartir por Enlace:** Sube archivos y genera un enlace directo para que otras personas puedan descargarlos de manera sencilla.
* 📦 **Tamaño Ilimitado:** Sin restricciones artificiales de tamaño de archivo. El único límite es el espacio físico que tengas disponible en tu propio servidor (configurado para admitir hasta 10 GB por defecto).
* ⏳ **Control de Caducidad:** Establece una fecha y hora de expiración para que los enlaces compartidos dejen de funcionar automáticamente.
* 🔒 **Seguridad y Privacidad:** Protege tus descargas mediante contraseña y establece un límite máximo de descargas antes de que el enlace se invalide.
* 🔥 **Autodestrucción:** Si está activa, el archivo físico se borrará de forma irreversible del disco del servidor inmediatamente cuando expire el enlace o alcance su límite de descargas.
* 📧 **Envío por Correo:** Envía los enlaces de descarga directamente a los destinatarios a través de correo electrónico desde la plataforma.
* 🛡️ **Seguridad Avanzada (RBAC):** Gestión de usuarios y permisos modular basada en roles con integración de CodeIgniter Shield.

---

## 🛠️ Stack Tecnológico

| Componente | Tecnología |
| :--- | :--- |
| **Backend Core** | CodeIgniter 4.x (PHP 8.2+) |
| **Autenticación** | CodeIgniter Shield |
| **Base de Datos** | SQLite3 |
| **Frontend** | Bootstrap 5, SweetAlert2, Tabler Icons, Flatpickr |

---

## 🔒 Exención de Responsabilidad

- **Privacidad Local:** FileCrew no recopila ni transmite información a servidores externos. Toda la configuración y los archivos se almacenan localmente en tu propio servidor.
- **Responsabilidad:** El usuario es el único responsable de la seguridad de su infraestructura y de realizar copias de seguridad de sus archivos de datos.
- **Licencia:** Distribuido "tal cual" (As Is) bajo la licencia MIT.
