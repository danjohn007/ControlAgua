# ControlAgua - Sistema de Control de Suministro de Agua a Pipas

Sistema web completo para controlar, registrar y monitorear el suministro de agua a pipas mediante un registro centralizado de empresas, vehículos y plumas vehiculares automatizadas.

## 📋 Características Principales

### Módulos Implementados

1. **Módulo de Administración General (Dashboard)**
   - Panel principal con indicadores en tiempo real
   - Volumen total de agua suministrada (día/semana/mes)
   - Número de pipas activas
   - Empresas registradas
   - Gráficos interactivos con Chart.js
   - Entradas y salidas recientes

2. **Módulo de Registro de Empresas**
   - Registro completo con datos fiscales
   - Gestión de estados (Activa/Suspendida/En revisión)
   - Control de saldo y créditos
   - Historial de transacciones
   - Búsqueda y filtrado avanzado

3. **Módulo de Registro de Pipas**
   - Gestión completa de vehículos
   - Generación automática de códigos QR únicos
   - Estados: Activa/Bloqueada/En mantenimiento
   - Vinculación con empresas
   - Historial de suministros y accesos

4. **Módulo de Control de Acceso**
   - Registro de entradas y salidas
   - Validación de accesos autorizados
   - Control por código QR
   - Auditoría de accesos

5. **Módulo de Suministro de Agua**
   - Registro de cargas con folio único
   - Cálculo automático de tarifas
   - Generación de tickets digitales
   - Actualización automática de saldos

6. **Módulo Financiero**
   - Control de pagos y créditos
   - Múltiples métodos de pago
   - Actualización automática de saldos
   - Reportes financieros

7. **Módulo de Auditoría**
   - Bitácora completa del sistema
   - Registro de todas las operaciones
   - Trazabilidad de cambios
   - Control de accesos

8. **Módulo de Seguridad**
   - Sistema de autenticación robusto
   - 3 roles: Admin, Supervisor, Operador
   - Contraseñas encriptadas (password_hash)
   - Control de sesiones seguras

## 🚀 Tecnologías Utilizadas

- **Backend:** PHP 7.4+ (Puro, sin frameworks)
- **Base de Datos:** MySQL 5.7+
- **Frontend:** HTML5, CSS3, JavaScript
- **Framework CSS:** Bootstrap 5
- **Gráficos:** Chart.js
- **Iconos:** Bootstrap Icons
- **Arquitectura:** MVC (Modelo-Vista-Controlador)

## 📦 Instalación

### Requisitos Previos

- Servidor Apache con mod_rewrite habilitado
- PHP 7.4 o superior
- MySQL 5.7 o superior
- Extensiones PHP requeridas:
  - PDO
  - pdo_mysql
  - mbstring
  - session

### Pasos de Instalación

1. **Clonar o descargar el proyecto**
   ```bash
   git clone https://github.com/danjohn007/ControlAgua.git
   cd ControlAgua
   ```

2. **Configurar la base de datos**
   
   a. Crear la base de datos:
   ```bash
   mysql -u root -p < sql/schema.sql
   ```
   
   b. Cargar datos de ejemplo:
   ```bash
   mysql -u root -p < sql/datos_ejemplo.sql
   ```

3. **Configurar credenciales de base de datos**
   
   Editar el archivo `config/database.php` con tus credenciales:
   ```php
   return [
       'host' => 'localhost',
       'database' => 'controlagua',
       'username' => 'tu_usuario',
       'password' => 'tu_contraseña',
       'charset' => 'utf8mb4',
       'port' => 3306
   ];
   ```

4. **Configurar Apache**
   
   a. Asegúrate de que mod_rewrite esté habilitado:
   ```bash
   sudo a2enmod rewrite
   sudo systemctl restart apache2
   ```
   
   b. Configurar el VirtualHost o copiar el proyecto a la carpeta de Apache:
   ```bash
   sudo cp -r ControlAgua /var/www/html/
   ```
   
   c. Dar permisos apropiados:
   ```bash
   sudo chown -R www-data:www-data /var/www/html/ControlAgua
   sudo chmod -R 755 /var/www/html/ControlAgua
   ```

5. **Probar la instalación**
   
   Acceder a las páginas de prueba:
   - Test de URL Base: `http://localhost/ControlAgua/test-url`
   - Test de Conexión: `http://localhost/ControlAgua/test-connection`

6. **Acceder al sistema**
   
   URL: `http://localhost/ControlAgua/`

## 👥 Usuarios de Prueba

El sistema viene con usuarios de ejemplo ya configurados:

| Email | Contraseña | Rol |
|-------|-----------|-----|
| admin@controlagua.com | admin123 | Administrador |
| operador@controlagua.com | admin123 | Operador |
| supervisor@controlagua.com | admin123 | Supervisor |

## 📁 Estructura del Proyecto

```
ControlAgua/
├── app/
│   ├── controllers/      # Controladores MVC
│   │   ├── Auth.php
│   │   ├── DashboardController.php
│   │   ├── EmpresaController.php
│   │   ├── PipaController.php
│   │   └── SuministroController.php
│   ├── models/          # Modelos de datos
│   │   ├── Database.php
│   │   ├── Model.php
│   │   ├── Usuario.php
│   │   ├── Empresa.php
│   │   ├── Pipa.php
│   │   ├── Suministro.php
│   │   ├── Acceso.php
│   │   ├── Pago.php
│   │   └── Auditoria.php
│   └── views/           # Vistas
│       ├── layouts/
│       ├── auth/
│       ├── dashboard/
│       ├── empresas/
│       ├── pipas/
│       └── suministros/
├── config/              # Configuración
│   ├── config.php
│   ├── database.php
│   └── database.example.php
├── public/              # Archivos públicos
│   ├── css/
│   ├── js/
│   └── img/
├── sql/                 # Scripts SQL
│   ├── schema.sql
│   └── datos_ejemplo.sql
├── .htaccess           # Reescritura de URLs
├── .gitignore
├── index.php           # Punto de entrada
├── test-connection.php # Test de conexión BD
├── test-url.php        # Test de URL base
└── README.md
```

## 🔧 Configuración Avanzada

### URL Base Automática

El sistema detecta automáticamente la URL base, permitiendo instalarlo en cualquier directorio:
- Raíz del dominio: `http://example.com/`
- Subdirectorio: `http://example.com/controlagua/`
- Localhost: `http://localhost/ControlAgua/`

### URLs Amigables

Todas las rutas del sistema utilizan URLs amigables mediante `.htaccess`:
- `/login` - Inicio de sesión
- `/dashboard` - Panel principal
- `/empresas` - Listado de empresas
- `/pipas` - Gestión de pipas
- `/suministros` - Registro de suministros
- Y más...

### Seguridad

El sistema implementa múltiples capas de seguridad:
- Contraseñas hasheadas con `password_hash()`
- Sesiones seguras con configuración optimizada
- Protección contra SQL Injection (PDO preparados)
- Protección XSS en todas las salidas
- Control de acceso por roles
- Auditoría completa de operaciones

## 📊 Base de Datos

El esquema de la base de datos incluye las siguientes tablas principales:

- `usuarios` - Usuarios del sistema
- `empresas` - Empresas registradas
- `pipas` - Vehículos (pipas)
- `estaciones` - Estaciones de carga
- `accesos` - Control de entradas/salidas
- `suministros` - Registro de cargas de agua
- `pagos` - Pagos realizados
- `tarifas` - Tarifas configuradas
- `auditoria` - Bitácora del sistema
- `configuracion` - Configuración general

## 🎨 Interfaz de Usuario

- **Diseño Responsivo:** Totalmente adaptable a dispositivos móviles
- **Bootstrap 5:** Framework CSS moderno y elegante
- **Gráficos Interactivos:** Chart.js para visualización de datos
- **Iconos:** Bootstrap Icons para una UI consistente
- **Tema Personalizado:** Diseño profesional con gradientes y sombras

## 🔐 Roles y Permisos

### Administrador
- Acceso completo al sistema
- Gestión de usuarios
- Gestión de estaciones
- Configuración del sistema
- Eliminación de registros

### Supervisor
- Gestión de empresas y pipas
- Visualización de reportes
- Control de accesos
- Sin permisos de eliminación

### Operador
- Registro de suministros
- Control de accesos
- Visualización de información
- Sin permisos de edición

## 📱 Características Adicionales

- **Generación de Códigos QR:** Cada pipa tiene un código QR único
- **Folios Automáticos:** Generación automática de folios únicos
- **Cálculo de Tarifas:** Sistema flexible de tarifas por empresa
- **Control de Saldo:** Actualización automática de saldos
- **Búsqueda Avanzada:** Filtros y búsquedas en todos los módulos
- **Auditoría Completa:** Registro de todas las operaciones

## 🐛 Solución de Problemas

### Error de conexión a la base de datos
- Verificar credenciales en `config/database.php`
- Asegurarse de que MySQL esté ejecutándose
- Verificar que la base de datos existe

### URLs no funcionan (404)
- Verificar que mod_rewrite esté habilitado
- Revisar el archivo `.htaccess`
- Verificar permisos de archivos

### Errores de permisos
```bash
sudo chown -R www-data:www-data /var/www/html/ControlAgua
sudo chmod -R 755 /var/www/html/ControlAgua
```

## 📝 Licencia

Este proyecto es de código abierto y está disponible bajo la licencia MIT.

## 👨‍💻 Desarrollo

Para continuar el desarrollo del sistema, se pueden implementar:
- Módulo de reportes avanzados con exportación a PDF/Excel
- Sistema de notificaciones por email
- API REST para integración con apps móviles
- Sistema de facturación electrónica
- Dashboard con más gráficos y métricas
- Calendario de actividades con FullCalendar.js
- Sistema de respaldo automático

## 📞 Soporte

Para reportar problemas o solicitar características:
- Issues: https://github.com/danjohn007/ControlAgua/issues

## 🎯 Estado del Proyecto

✅ **Módulos Completados:**
- Estructura MVC completa
- Sistema de autenticación y autorización
- Dashboard con estadísticas
- Gestión de empresas
- Gestión de pipas con códigos QR
- Registro de suministros
- Control de accesos
- Sistema de pagos
- Auditoría completa
- Base de datos con datos de ejemplo

⏳ **Módulos por Desarrollar:**
- Reportes avanzados con exportación
- Sistema de notificaciones
- Gestión de usuarios (CRUD completo)
- Gestión de estaciones (CRUD completo)
- Calendario de actividades
- Impresión de tickets
- API para integración móvil

---

**Versión:** 1.0.0  
**Última actualización:** 2024  
**Desarrollado con:** ❤️ y PHP
