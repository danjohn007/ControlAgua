# Guía de Instalación - ControlAgua

## Guía Paso a Paso para Instalar el Sistema de Control de Suministro de Agua

### 📋 Requisitos del Sistema

#### Software Requerido
- **Servidor Web:** Apache 2.4 o superior
- **PHP:** Versión 7.4 o superior
- **MySQL:** Versión 5.7 o superior (o MariaDB 10.2+)
- **Navegador Web:** Chrome, Firefox, Safari o Edge (última versión)

#### Extensiones PHP Requeridas
```bash
- pdo
- pdo_mysql
- mbstring
- session
- json
- mysqli (opcional)
```

#### Módulos Apache Requeridos
```bash
- mod_rewrite
- mod_headers (opcional, recomendado)
```

---

## 🚀 Instalación en Servidor Local (XAMPP/WAMP/LAMP)

### Paso 1: Descargar el Proyecto

```bash
# Opción A: Clonar desde Git
git clone https://github.com/danjohn007/ControlAgua.git
cd ControlAgua

# Opción B: Descargar ZIP y extraer
# Descargar desde GitHub y extraer en htdocs (XAMPP) o www (WAMP)
```

### Paso 2: Configurar Apache

#### Para XAMPP (Windows/Mac/Linux):

1. Copiar el proyecto a la carpeta htdocs:
   ```bash
   # Windows
   C:\xampp\htdocs\ControlAgua\
   
   # Mac
   /Applications/XAMPP/htdocs/ControlAgua/
   
   # Linux
   /opt/lampp/htdocs/ControlAgua/
   ```

2. Verificar que mod_rewrite esté habilitado:
   - Abrir `C:\xampp\apache\conf\httpd.conf`
   - Buscar: `#LoadModule rewrite_module modules/mod_rewrite.so`
   - Remover el `#` al inicio de la línea
   - Reiniciar Apache desde el panel de XAMPP

3. Configurar permisos (Linux/Mac):
   ```bash
   chmod -R 755 /opt/lampp/htdocs/ControlAgua
   chown -R daemon:daemon /opt/lampp/htdocs/ControlAgua
   ```

#### Para WAMP (Windows):

1. Copiar a `C:\wamp64\www\ControlAgua\`
2. mod_rewrite ya viene habilitado por defecto
3. Reiniciar todos los servicios desde el panel de WAMP

### Paso 3: Crear la Base de Datos

#### Opción A: Usando phpMyAdmin

1. Abrir phpMyAdmin: `http://localhost/phpmyadmin`
2. Hacer clic en "Nuevo" para crear una base de datos
3. Nombre: `controlagua`
4. Cotejamiento: `utf8mb4_unicode_ci`
5. Click en "Crear"
6. Seleccionar la base de datos `controlagua`
7. Ir a la pestaña "Importar"
8. Seleccionar archivo: `ControlAgua/sql/schema.sql`
9. Click en "Continuar"
10. Repetir para: `ControlAgua/sql/datos_ejemplo.sql`

#### Opción B: Usando Línea de Comandos

```bash
# Acceder a MySQL
mysql -u root -p

# Crear la base de datos
CREATE DATABASE controlagua CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
exit;

# Importar el esquema
mysql -u root -p controlagua < sql/schema.sql

# Importar datos de ejemplo
mysql -u root -p controlagua < sql/datos_ejemplo.sql
```

### Paso 4: Configurar Credenciales de Base de Datos

1. Abrir el archivo: `config/database.php`
2. Modificar las credenciales según tu configuración local:

```php
return [
    'host' => 'localhost',      // Normalmente localhost
    'database' => 'controlagua', // Nombre de tu base de datos
    'username' => 'root',        // Usuario de MySQL (root por defecto en XAMPP)
    'password' => '',            // Contraseña (vacío por defecto en XAMPP)
    'charset' => 'utf8mb4',
    'port' => 3306               // Puerto de MySQL (3306 por defecto)
];
```

**Nota:** El archivo ya existe con valores por defecto. En XAMPP la contraseña de root suele estar vacía por defecto.

### Paso 5: Verificar la Instalación

#### Test de Conexión

1. Abrir navegador
2. Ir a: `http://localhost/ControlAgua/test-connection`
3. Debe mostrar: "✓ Conexión Exitosa"
4. Verificar que aparezcan las tablas creadas

#### Test de URL Base

1. Ir a: `http://localhost/ControlAgua/test-url`
2. Verificar que la URL base sea correcta
3. Verificar módulos PHP requeridos

### Paso 6: Acceder al Sistema

1. Abrir: `http://localhost/ControlAgua/`
2. Usar uno de los usuarios de prueba:

| Usuario | Contraseña | Rol |
|---------|-----------|-----|
| admin@controlagua.com | admin123 | Administrador |
| supervisor@controlagua.com | admin123 | Supervisor |
| operador@controlagua.com | admin123 | Operador |

---

## 🌐 Instalación en Servidor de Producción

### Requisitos Previos

- Acceso SSH al servidor
- Acceso a cPanel o panel de administración
- Base de datos MySQL/MariaDB
- PHP 7.4+ instalado

### Método 1: cPanel (Hosting compartido)

#### Paso 1: Subir Archivos

1. Acceder a cPanel
2. Ir a "Administrador de archivos"
3. Navegar a `public_html/`
4. Crear carpeta `controlagua`
5. Subir todos los archivos del proyecto mediante "Cargar"
6. O usar FileZilla/FTP

#### Paso 2: Crear Base de Datos

1. En cPanel, ir a "Bases de datos MySQL"
2. Crear nueva base de datos: `usuario_controlagua`
3. Crear usuario MySQL con contraseña segura
4. Asignar usuario a la base de datos con todos los privilegios
5. Anotar: nombre de BD, usuario y contraseña

#### Paso 3: Importar Base de Datos

1. En cPanel, ir a "phpMyAdmin"
2. Seleccionar la base de datos creada
3. Ir a "Importar"
4. Subir `sql/schema.sql`
5. Subir `sql/datos_ejemplo.sql`

#### Paso 4: Configurar

1. Editar `config/database.php` con los datos de cPanel
2. Guardar cambios

#### Paso 5: Configurar .htaccess

El archivo `.htaccess` ya viene configurado, pero verificar:

```apache
RewriteBase /controlagua/

# Si está en la raíz, cambiar a:
RewriteBase /
```

### Método 2: Servidor VPS/Dedicado (Ubuntu/Debian)

```bash
# Actualizar sistema
sudo apt update && sudo apt upgrade -y

# Instalar LAMP stack
sudo apt install apache2 mysql-server php libapache2-mod-php php-mysql php-mbstring -y

# Habilitar mod_rewrite
sudo a2enmod rewrite

# Reiniciar Apache
sudo systemctl restart apache2

# Clonar proyecto
cd /var/www/html
sudo git clone https://github.com/danjohn007/ControlAgua.git

# Dar permisos
sudo chown -R www-data:www-data /var/www/html/ControlAgua
sudo chmod -R 755 /var/www/html/ControlAgua

# Configurar MySQL
sudo mysql -u root -p

# En MySQL:
CREATE DATABASE controlagua CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'controlagua_user'@'localhost' IDENTIFIED BY 'PASSWORD_SEGURA_AQUI';
GRANT ALL PRIVILEGES ON controlagua.* TO 'controlagua_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Importar BD
mysql -u controlagua_user -p controlagua < /var/www/html/ControlAgua/sql/schema.sql
mysql -u controlagua_user -p controlagua < /var/www/html/ControlAgua/sql/datos_ejemplo.sql

# Editar configuración
sudo nano /var/www/html/ControlAgua/config/database.php
# Actualizar con las credenciales de MySQL

# Configurar Virtual Host (opcional)
sudo nano /etc/apache2/sites-available/controlagua.conf
```

Contenido del Virtual Host:

```apache
<VirtualHost *:80>
    ServerAdmin admin@tudominio.com
    ServerName tudominio.com
    DocumentRoot /var/www/html/ControlAgua
    
    <Directory /var/www/html/ControlAgua>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/controlagua_error.log
    CustomLog ${APACHE_LOG_DIR}/controlagua_access.log combined
</VirtualHost>
```

```bash
# Habilitar el sitio
sudo a2ensite controlagua.conf
sudo systemctl reload apache2
```

---

## 🔒 Seguridad Post-Instalación

### 1. Cambiar Contraseñas por Defecto

1. Iniciar sesión como administrador
2. Ir a "Usuarios"
3. Cambiar contraseñas de todos los usuarios de prueba

### 2. Eliminar Archivos de Prueba (Producción)

```bash
rm test-connection.php
rm test-url.php
```

### 3. Configurar SSL/HTTPS

- Obtener certificado SSL (Let's Encrypt es gratis)
- Configurar Apache para usar HTTPS
- Actualizar en `.htaccess` para forzar HTTPS

### 4. Configurar Permisos de Archivos

```bash
# Solo el servidor web puede escribir
chmod 755 -R /ruta/a/ControlAgua
chmod 644 /ruta/a/ControlAgua/config/*.php
chmod 700 /ruta/a/ControlAgua/config
```

### 5. Proteger Archivos Sensibles

Ya incluido en `.htaccess`:
```apache
<FilesMatch "\.(htaccess|htpasswd|ini|log|sh|sql)$">
    Order Allow,Deny
    Deny from all
</FilesMatch>
```

---

## 🐛 Solución de Problemas Comunes

### Problema: Error 404 en todas las URLs

**Solución:**
- Verificar que mod_rewrite esté habilitado
- Verificar permisos del archivo `.htaccess`
- Verificar configuración de Apache AllowOverride

### Problema: Error de conexión a base de datos

**Solución:**
- Verificar credenciales en `config/database.php`
- Verificar que MySQL esté ejecutándose
- Probar conexión desde terminal: `mysql -u usuario -p`

### Problema: Página en blanco

**Solución:**
- Activar display_errors en `config/config.php`
- Revisar logs de Apache
- Verificar versión de PHP (mínimo 7.4)

### Problema: CSS/JS no se cargan

**Solución:**
- Verificar que la URL base sea correcta
- Revisar en `test-url.php`
- Limpiar caché del navegador

### Problema: URLs no amigables funcionan pero las amigables no

**Solución:**
```bash
# Verificar que mod_rewrite esté activo
apache2ctl -M | grep rewrite

# Debe aparecer: rewrite_module (shared)
```

---

## 📞 Soporte

Si encuentras problemas durante la instalación:

1. Verificar la documentación en README.md
2. Revisar los archivos de test
3. Crear un issue en: https://github.com/danjohn007/ControlAgua/issues

---

## ✅ Verificación Final

Lista de comprobación post-instalación:

- [ ] Base de datos creada e importada
- [ ] Credenciales configuradas correctamente
- [ ] test-connection.php muestra conexión exitosa
- [ ] test-url.php muestra configuración correcta
- [ ] Login funciona con usuarios de prueba
- [ ] Dashboard muestra estadísticas
- [ ] Puede crear una empresa de prueba
- [ ] Puede crear una pipa de prueba
- [ ] Puede registrar un suministro
- [ ] Todas las URLs amigables funcionan

¡Felicidades! El sistema está instalado y listo para usar. 🎉
