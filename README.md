# restaurant-fron

## Descripción del proyecto
`restaurant-fron` es una aplicación frontend desarrollada con **Vue 3**, **Vue Router**, **Vuex** y **TailwindCSS/PostCSS** para gestionar un restaurante. Incluye:

- Panel de administración y gestión de pedidos, ventas, mesas y stock de platos.
- Módulo de mantenimiento para categorías, platos, usuarios y roles.
- Página pública de inicio con secciones de menú y testimonios.
- Recursos estáticos para imágenes, logos y galerías.

## Estructura principal de carpetas

### 📁 `src/`
Carpeta principal de código fuente.

- `App.vue` - Componente raíz que envuelve la app con proveedores de tema y sidebar.
- `main.js` - Inicializa Vue, router, store y librerías globales.
- `router/index.js` - Configura las rutas de la aplicación.
- `store/index.js` - Vuex para gestión de sesión, token y usuario.

#### `src/assets/`
Recursos estáticos de la aplicación:
- `logo.png` - Logo principal.
- `main.css` - Estilos globales del dashboard.
- `styles/` - Estilos específicos para bienvenida, login y home.
- `img/`, `sounds/` - Imágenes y sonidos usados en la app.

#### `src/components/`
Componentes reutilizables y específicos de UI.

- `admin/` - Componentes para el panel de administración.
  - `PedidosRecientes.vue`
  - `VentasAll.vue`
- `common/` - Componentes genéricos compartidos.
  - `CommonGridShape.vue`, `ComponentCard.vue`, `CountDown.vue`, `DropdownMenu.vue`, `PageBreadcrumb.vue`, `ThemeToggler.vue`, `v-click-outside.vue`
- `home/` - Componentes de la página pública.
  - `PageHome.vue`
  - `section_menu/SectionMenu.vue`
  - `testimonial/Testimonial.vue`
- `layout/` - Estructura de la interfaz y control de temas/sidebar.
  - `AdminLayout.vue`, `AppHeader.vue`, `AppSidebar.vue`, `Backdrop.vue`, `FullScreenLayout.vue`, `ThemeProvider.vue`, `SidebarProvider.vue`, `SidebarWidget.vue`
- `mantenimiento/` - Componentes de administración de datos maestros.
  - `categorias/` - Gestión de categorías.
  - `platos/` - Gestión de platos.
  - `roles/` - Gestión de roles.
  - `usuarios/` - Gestión de usuarios.
- `mesas/` - Vista y gestión de mesas.
- `Modal/` - Componentes modales reutilizables.
- `stok-platos/` - Control de stock de platos.

#### `src/composables/`
Hooks personalizados para lógica compartida.
- `useSidebar.js` - Manejo del estado del sidebar.
- `useUsuario.js` - Manejo de información de usuario.

#### `src/icons/`
Componentes de iconos SVG en Vue usados por la interfaz.
- Ejemplos: `HomeIcon.vue`, `MenuIcon.vue`, `UserCircleIcon.vue`, `VentasIcon.vue`, `TrashIcon.vue`, `WarningIcon.vue`, etc.
- `index.js` - Exporta y registra iconos para su uso global.

#### `src/views/`
Vistas principales de la aplicación.
- `HomeView.vue` - Página de login o entrada principal.
- `LoginView.vue` - Vista de inicio de sesión.
- `DashUiView.vue` - Dashboard principal para el panel administrativo.
- `views/Admin/` - Vistas de administración de pedidos y ventas.
- `views/Mantenimiento/` - Vistas de mantenimiento de categorías, usuarios, roles y platos.
- `views/Mesas/` - Vista de gestión de mesas.
- `views/Op_Plato/` - Vistas de stock de platos.
- `views/Home/` - Página pública de la app.

### 📁 `public/`
Archivos estáticos que se sirven directamente.
- `index.html` - Plantilla HTML principal.
- `favicon.ico` - Ícono de la aplicación.
- `gallery/` - Galería de imágenes estáticas.
- `images/` - Carpetas de imágenes por categoría:
  - `brand/`, `cards/`, `carousel/`, `chat/`, `country/`, `error/`, `grid-image/`, `icons/`, `logo/`, `product/`, `shape/`, `task/`, `user/`, `video-thumb/`

## Archivos importantes

- `package.json` - Dependencias, scripts y configuración básica.
- `vue.config.js` - Configuración de Vue CLI.
- `babel.config.js` - Configuración de Babel.
- `postcss.config.js` - Configuración de PostCSS.
- `jsconfig.json` - Configuración de rutas y alias JS.

## Comandos disponibles

```bash
npm install
npm run serve
npm run build
```

## Dependencias clave

- `vue` 3
- `vue-router` 4
- `vuex` 4
- `@fortawesome/fontawesome-free`
- `bootstrap` 5
- `swiper`
- `apexcharts` / `vue3-apexcharts`
- `axios`
- `dropzone`
- `flatpickr`
- `html2canvas`
- `jspdf`
- `qrcode.vue`
- `sweetalert2`
- `jsvectormap`
- `tweakpane`
- `vue-shadow-dom`

## Rutas principales

- `/` → `HomeView`
- `/login` → `LoginView`
- `/admin` → `DashUiView`
- `/mantenimiento/platos` → Gestión de platos
- `/mantenimiento/categorias` → Gestión de categorías
- `/mantenimiento/usuarios` → Gestión de usuarios
- `/mantenimiento/roles` → Gestión de roles
- `/home` → Página pública principal
- `/mesas` → Gestión de mesas
- `/stock/platos` → Stock de platos
- `/pedidos_recientes` → Pedidos recientes
- `/ventas` → Reporte de ventas

## Recursos visuales

- Logo principal: `src/assets/logo.png`
- Imágenes de la landing y el dashboard: `public/images/`
- Imágenes de galería: `public/gallery/`

## Notas

- La app está diseñada para operar con un backend que provea autenticación y datos de restaurante.
- Vuex almacena token, rol y datos de usuario en `localStorage`.
- La mayoría de los estilos globales se importan en `src/main.js`.

---

Si necesitas, puedo también agregar un diagrama de navegación o una sección de instalación avanzada paso a paso.