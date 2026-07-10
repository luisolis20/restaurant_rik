# Backend del sistema de restaurante

Este proyecto corresponde al backend de una aplicación de gestión para un restaurante. Está desarrollado con Laravel 10 y expone una API REST para la administración de usuarios, roles, categorías, productos, pedidos, mesas, facturación, inventarios, calificaciones, QR de mesas y tiempos de preparación.

## Descripción general

El sistema está orientado a cubrir los procesos operativos de un restaurante, incluyendo:

- autenticación y autorización de usuarios;
- gestión de roles y permisos;
- administración de categorías y productos;
- registro y seguimiento de pedidos;
- asignación y control de mesas;
- generación y validación de códigos QR asociados a mesas;
- emisión de facturas y detalle de ventas;
- control de inventario;
- registro de calificaciones de clientes;
- métricas y estadísticas de ventas y desempeño.

## Stack tecnológico

- PHP 8.1+
- Laravel Framework 10.x
- Laravel Sanctum para autenticación por tokens
- Tymon JWT Auth para autenticación JWT
- Simple QrCode para generación de códigos QR
- Vite para la integración de assets frontend
- PHPUnit para pruebas unitarias y de integración
- Docker para entornos de desarrollo y pruebas

## Estructura del proyecto

```text
.
├── app/
│   ├── Console/
│   ├── Exceptions/
│   ├── Http/
│   │   ├── Controllers/
│   │   └── Middleware/
│   ├── Models/
│   └── Providers/
├── bootstrap/
├── config/
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── docker/
│   ├── 8.0/
│   ├── 8.1/
│   ├── 8.2/
│   ├── 8.3/
│   ├── 8.4/
│   ├── 8.5/
│   ├── mariadb/
│   ├── mysql/
│   └── pgsql/
├── public/
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
├── routes/
├── storage/
├── tests/
├── vendor/
├── artisan
├── composer.json
├── composer.lock
├── package.json
├── phpunit.xml
├── sail
├── vite.config.js
├── .editorconfig
├── .env.example
├── .gitattributes
├── .gitignore
└── README.md
```

## Descripción de carpetas y archivos

### Archivos raíz

- `artisan`: punto de entrada de la consola de Laravel. Permite ejecutar comandos como migraciones, seeders, pruebas, limpiar caché y tareas personalizadas del sistema.
- `composer.json`: archivo de configuración de Composer. Define dependencias del proyecto, autoloading PSR-4, scripts de instalación y scripts post-install/post-update.
- `composer.lock`: lockfile generado por Composer que garantiza versiones consistentes de las dependencias.
- `package.json`: configuración de dependencias frontend y scripts para Vite.
- `phpunit.xml`: configuración del framework de pruebas PHPUnit.
- `sail`: script de ejecución de Laravel Sail para levantar entornos Docker.
- `vite.config.js`: configuración de Vite para compilar y servir assets del frontend.
- `.editorconfig`: reglas de estilo de edición compartidas entre editores.
- `.env.example`: plantilla de variables de entorno para configuración local.
- `.gitattributes`: definiciones de atributos Git para normalizar comportamiento de archivos.
- `.gitignore`: exclusiones de archivos y directorios del repositorio.
- `README.md`: documentación del proyecto.

### Carpeta `app/`

Contiene la lógica de negocio principal de la aplicación.

#### `app/Console/`
- `Kernel.php`: registra comandos de consola personalizados del sistema.

#### `app/Exceptions/`
- `Handler.php`: centraliza el manejo de excepciones, errores HTTP y respuestas de error globales.

#### `app/Http/`
- `Kernel.php`: define el middleware global, grupos de middleware y aliases utilizados por la aplicación.

##### `app/Http/Controllers/`
Esta carpeta contiene los controladores que exponen los endpoints de la API REST.

- `AuthController.php`: autenticación, login, logout, refresh y obtención del usuario autenticado.
- `UserController.php`: gestión de usuarios.
- `RoleController.php`: administración de roles.
- `CategoriaController.php`: CRUD de categorías de productos.
- `ProductoController.php`: CRUD de productos, carga de fotografía y consulta de productos habilitados.
- `CalificacionesController.php`: registro y consulta de calificaciones.
- `PedidosController.php`: creación, consulta y estado de pedidos.
- `MesaController.php`: administración de mesas, disponibilidad y generación de QR.
- `DetallePedidoController.php`: gestión del detalle de pedidos y carrito.
- `FacturaController.php`: generación de facturas, dashboard y estadísticas de ventas.
- `DetalleFacturaController.php`: detalle de líneas de factura.
- `InventarioController.php`: control de inventarios.
- `QrMesaController.php`: verificación y administración de códigos QR vinculados a mesas.
- `TiempoPreparacionController.php`: administración de tiempos de preparación.
- `Controller.php`: controlador base compartido por los demás controladores.

##### `app/Http/Middleware/`
- `Authenticate.php`: middleware para proteger rutas por autenticación.
- `EncryptCookies.php`: manejo de cookies cifradas.
- `PreventRequestsDuringMaintenance.php`: bloqueo temporal del sistema durante mantenimiento.
- `RedirectIfAuthenticated.php`: redirección de usuarios autenticados.
- `TrimStrings.php`: normalización de cadenas.
- `TrustHosts.php`: validación de hosts de confianza.
- `TrustProxies.php`: configuración de proxies.
- `ValidateSignature.php`: validación de firmas de solicitudes.
- `VerifyCsrfToken.php`: protección CSRF para formularios web.

#### `app/Models/`
Modelos Eloquent que representan entidades del dominio del negocio.

- `User.php`: modelo de usuarios.
- `Rol.php`: modelo de roles.
- `Categoria.php`: categorías de productos.
- `Producto.php`: productos del menú.
- `Calificacion.php`: calificaciones del cliente.
- `Pedido.php`: pedidos generales del sistema.
- `DetallePedido.php`: líneas de pedido.
- `Mesa.php`: mesas del restaurante.
- `QrMesa.php`: registros QR asociados a mesas.
- `Factura.php`: facturas emitidas.
- `DetalleFactura.php`: detalle de los productos facturados.
- `Inventario.php`: control de stock e inventario.
- `TiempoPreparacion.php`: tiempos de preparación de productos.

#### `app/Providers/`
- `AppServiceProvider.php`: registro de servicios de la aplicación.
- `AuthServiceProvider.php`: configuración de políticas y autenticación.
- `BroadcastServiceProvider.php`: registro de servicios de broadcasting.
- `EventServiceProvider.php`: registro de listeners y eventos.
- `RouteServiceProvider.php`: configuración de rutas y middleware por defecto.

### Carpeta `bootstrap/`
- `app.php`: archivo principal de arranque de la aplicación Laravel.
- `cache/`: almacenamiento de caché de la aplicación y de paquetes.

### Carpeta `config/`
Contiene la configuración de todos los servicios y componentes del framework.

- `app.php`: configuración general de la aplicación.
- `auth.php`: autenticación y guards.
- `broadcasting.php`: configuración de broadcasting.
- `cache.php`: configuración de caché.
- `cors.php`: políticas CORS.
- `database.php`: conexión a bases de datos.
- `filesystems.php`: configuración de discos de archivos.
- `flare.php`: integración con Flare.
- `hashing.php`: algoritmo de hashing.
- `ignition.php`: configuración de Ignition para errores.
- `jwt.php`: configuración de JWT.
- `logging.php`: canales de logging.
- `mail.php`: envío de correos.
- `queue.php`: configuración de colas de trabajo.
- `sanctum.php`: configuración de Sanctum.
- `services.php`: integraciones externas.
- `session.php`: configuración de sesiones.
- `tinker.php`: configuración de Tinker.
- `view.php`: configuración de vistas Blade.

### Carpeta `database/`
Define la persistencia de datos y los datos iniciales del sistema.

#### `database/factories/`
- `UserFactory.php`: fábrica para generar usuarios de prueba.

#### `database/migrations/`
Archivos de migración para crear y modificar esquemas de base de datos.

- `2014_10_12_000000_create_users_table.php`
- `2014_10_12_100000_create_password_reset_tokens_table.php`
- `2019_08_19_000000_create_failed_jobs_table.php`
- `2019_12_14_000001_create_personal_access_tokens_table.php`

#### `database/seeders/`
- `DatabaseSeeder.php`: seeder principal que inicializa datos base de la aplicación.

### Carpeta `docker/`
Archivos de contenedorización para múltiples variantes de PHP y bases de datos.

- `8.0/`: Dockerfile, `php.ini`, `start-container`, `supervisord.conf`
- `8.1/`: Dockerfile, `php.ini`, `start-container`, `supervisord.conf`
- `8.2/`: Dockerfile, `php.ini`, `start-container`, `supervisord.conf`
- `8.3/`: Dockerfile, `php.ini`, `start-container`, `supervisord.conf`
- `8.4/`: Dockerfile, `php.ini`, `start-container`, `supervisord.conf`
- `8.5/`: Dockerfile, `php.ini`, `start-container`, `supervisord.conf`
- `mariadb/`: scripts para creación de base de datos de pruebas.
- `mysql/`: scripts para creación de base de datos de pruebas.
- `pgsql/`: scripts para creación de base de datos de pruebas.

### Carpeta `public/`
Contiene los archivos accesibles públicamente por el servidor web.

- `index.php`: punto de entrada público de la aplicación.
- `.htaccess`: reglas de reescritura para URLs limpias.
- `robots.txt`: instrucciones para motores de búsqueda.
- `favicon.ico`: icono del sitio.

### Carpeta `resources/`
Recursos de la interfaz y vistas de la aplicación.

#### `resources/css/`
- `app.css`: estilos base del frontend.

#### `resources/js/`
- `app.js`: lógica principal de JavaScript.
- `bootstrap.js`: inicialización del entorno frontend.

#### `resources/views/`
Vistas Blade de la aplicación.

- `welcome.blade.php`: vista inicial por defecto.
- `errors/`: plantillas de páginas de error HTTP (401, 402, 403, 404, 419, 429, 500, 503).
- `vendor/`: vistas Blade provistas por paquetes y componentes de terceros.

### Carpeta `routes/`
Define las rutas HTTP del sistema.

- `api.php`: rutas principales de la API REST, incluyendo recursos para usuarios, roles, categorías, productos, pedidos, mesas, facturas, inventario, QR y métricas.
- `web.php`: rutas web tradicionales del framework.
- `console.php`: comandos de consola.
- `channels.php`: definiciones de canales de broadcasting.

### Carpeta `storage/`
Directorio de almacenamiento runtime de Laravel.

- `app/`: archivos generados por el usuario o la aplicación.
- `app/public/`: archivos públicos accesibles desde el sistema.
- `framework/`: caché, sesiones, vistas y estado de pruebas.
- `logs/`: registros de la aplicación.

### Carpeta `tests/`
Pruebas automatizadas del proyecto.

- `CreatesApplication.php`: configuración de la aplicación para pruebas.
- `TestCase.php`: clase base para los test cases.
- `Feature/`: pruebas funcionales del sistema.
- `Unit/`: pruebas unitarias.

### Carpeta `vendor/`
Directorio generado por Composer que contiene todas las dependencias del proyecto instaladas, incluidos Laravel, paquetes de soporte, utilidades de PHP y librerías externas.

## Observaciones de arquitectura

Este repositorio sigue la estructura clásica de Laravel y separa responsabilidades en:

- controladores para la exposición de endpoints;
- modelos Eloquent para acceso a datos;
- migraciones para versionado del esquema;
- seeders para datos iniciales;
- middleware para seguridad y validación del flujo de peticiones;
- rutas centralizadas para la API;
- pruebas automatizadas para validar el comportamiento del sistema.

## Notas de uso

Para trabajar con este proyecto es habitual ejecutar comandos como:

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Este README tiene como finalidad documentar la organización y propósito de cada parte del backend del sistema de restaurante.
