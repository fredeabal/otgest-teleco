# 🚀 OtGest - Sistema de Gestión de Órdenes de Trabajo

<p align="center">
  <img src="public/assets/images/logos/light-logo.svg" alt="OtGest Logo" width="280">
  <br>
  <b>Plataforma autohospedada de gestión, control y seguimiento de órdenes de trabajo (OT), basada en PHP (CodeIgniter 4) y SQLite3.</b>
  <br><br>
  <a href="https://github.com/fredeabal/otgest-teleco/releases"><img src="https://img.shields.io/badge/version-1.0.0-orange.svg" alt="Version 1.0.0"></a>
  <a href="https://www.php.net/"><img src="https://img.shields.io/badge/PHP-8.2%2B-777BB4.svg" alt="PHP 8.2+"></a>
  <a href="https://codeigniter.com/"><img src="https://img.shields.io/badge/CodeIgniter-4.x-DD4814.svg" alt="CodeIgniter 4"></a>
  <a href="LICENSE"><img src="https://img.shields.io/badge/License-MIT-blue.svg" alt="License MIT"></a>
</p>

---

## 🚀 Instalación en 1 Paso

En cualquier servidor **Debian 11/12** o **Ubuntu 20.04 / 22.04 / 24.04** limpio con acceso root (VPS o máquina virtual), ejecuta el siguiente comando en tu terminal:

```bash
bash <(curl -s https://raw.githubusercontent.com/fredeabal/otgest-teleco/main/install.sh)
```

El script se encargará automáticamente de todo el proceso de instalación y configuración del servidor web Nginx, dependencias del sistema, PHP, base de datos SQLite y permisos del proyecto.

### 🔑 Credenciales por Defecto tras la Instalación
* **URL:** `http://TU_IP_O_DOMINIO`
* **Email:** `admin@demo.com`
* **Password:** `admin1234`

### ⚙️ Configuración Post-Instalación Recomendada

Para el correcto funcionamiento de todas las características de OtGest, se recomienda configurar:

#### 1. 📧 Configuración de Correo (SMTP)
OtGest utiliza el servicio de correo para enviar copias detalladas de las órdenes de trabajo directamente a los clientes y para la recuperación de contraseñas de los usuarios.
1. Ve a **Ajustes SMTP** en la barra lateral del panel de administración.
2. Introduce los datos de tu servidor de correo (Host, Puerto, Usuario, Contraseña y tipo de encriptación TLS/SSL).
3. Utiliza la opción **Enviar Correo de Prueba** para verificar que la configuración sea correcta.

#### 🔧 Archivo de Configuración Global (.env)
El archivo `.env` se encuentra en la raíz de la instalación y almacena las variables principales del sistema (como la URL base del sitio `app.baseURL`, la ubicación de la base de datos SQLite y el entorno de ejecución). Puedes editarlo en cualquier momento desde la terminal mediante el comando:

```bash
nano /var/www/otgest/.env
```

---

## 🔄 Actualizar a la última versión

Para actualizar un servidor OtGest existente a la última versión disponible (sin perder tus datos registrados, configuraciones o base de datos), simplemente ejecuta:

```bash
bash <(curl -s https://raw.githubusercontent.com/fredeabal/otgest-teleco/main/update.sh)
```

---

## ✨ Características Principales

* 📊 **Dashboard de Métricas:** Panel visual con contador de órdenes agrupadas por tipo (Instalación, Avería, Modificación, Traslado, Portabilidad, Baja, Auditoría) y un gráfico histórico dinámico interactivo mediante ApexCharts.
* ⚡ **Validación de Duplicados en Tiempo Real:** Al ingresar o editar un "Número de Orden", un script AJAX consulta al servidor instantáneamente. Si ya existe, se ilumina el campo en color de marca (`primary`), bloquea el envío y proporciona un enlace directo y discreto (sin subrayado) para abrir la orden existente.
* 🔄 **Navegación de Órdenes Dinámica:** Botones de Anterior y Siguiente en la vista de detalle de cada orden. Respeta el sistema de permisos (un técnico normal solo navegará por sus propias órdenes y un administrador con acceso global navegará por toda la base de datos secuencialmente).
* 📁 **Gestión de Archivos y Fotos (Dropzone):** Sube imágenes o documentos relacionados directamente a las órdenes de trabajo, con previsualización en tiempo real y carga limpia (eliminando físicamente archivos antiguos del servidor con `unlink` al ser reemplazados o borrados).
* 📧 **Envío de Ficha por Correo:** Posibilidad de enviar la información y detalles completos de la orden de trabajo en formato email al cliente final.
* 🛠️ **Mantenimiento Integrado:** Opciones para desfragmentar y optimizar la base de datos SQLite (`VACUUM`), limpiar sesiones activas, eliminar archivos de logs y depurador (Debugbar), y realizar copias de seguridad descargables o restaurar respaldos `.db`.
* 🛡️ **Seguridad Basada en Roles (RBAC):** Control de acceso avanzado configurado mediante CodeIgniter Shield para regular los menús y acciones de Administradores y Técnicos.

---

## 🛠️ Stack Tecnológico

| Componente | Tecnología |
| :--- | :--- |
| **Backend Core** | CodeIgniter 4.x (PHP 8.2+) |
| **Autenticación** | CodeIgniter Shield |
| **Base de Datos** | SQLite3 |
| **Frontend** | Bootstrap 5 (Estética Dark Premium), SweetAlert2, ApexCharts, Flatpickr, Tabler Icons |

---

## 🔒 Exención de Responsabilidad

- **Privacidad Local:** OtGest no recopila ni transmite información a servidores externos. Toda la configuración, contraseñas y base de datos se almacenan localmente en tu propio servidor.
- **Responsabilidad:** El usuario es el único responsable de la seguridad de su infraestructura y de realizar copias de seguridad de sus archivos de datos de forma regular.
- **Licencia:** Distribuido "tal cual" (As Is) bajo la licencia MIT.
