# Instalación de la base de datos Restaurant Rico

Este documento explica cómo instalar y cargar la base de datos del proyecto utilizando el archivo SQL llamado restaurante_rick.sql.

## 1. ¿Qué motor de base de datos usar?

Se recomienda usar MySQL 8.0.x.

¿Por qué?
- El script fue generado con MySQL 8.0.30.
- El archivo usa características compatibles con MySQL 8, como la codificación utf8mb4 y la configuración de colación utf8mb4_0900_ai_ci.
- Para una instalación más segura y estable, se recomienda usar MySQL Server 8.0 en lugar de versiones antiguas o de otros motores.

> Si vas a instalarlo en Windows, lo más práctico es usar MySQL Installer, XAMPP con MySQL o una instalación manual de MySQL Server.

## 2. Requisitos previos

Antes de importar la base de datos, necesitas tener instalado lo siguiente:
- MySQL Server 8.0.x
- Un cliente para administrar MySQL, por ejemplo:
  - MySQL Workbench
  - HeidiSQL
  - phpMyAdmin
- Acceso al servicio de MySQL en tu computadora

## 3. Pasos para instalar la base de datos

### Paso 1: Instalar MySQL Server

1. Descarga e instala MySQL Server 8.0 desde el sitio oficial de MySQL.
2. Durante la instalación, configura una contraseña para el usuario root o crea un usuario nuevo con permisos.
3. Asegúrate de que el servicio de MySQL quede activo.

### Paso 2: Iniciar el servicio de MySQL

En Windows, el servicio suele iniciarse automáticamente al instalar MySQL. Si no lo hace, puedes activarlo desde:
- Servicios de Windows
- Buscar “MySQL80” o “MySQL”
- Iniciar el servicio

### Paso 3: Abrir un cliente de MySQL

Puedes usar cualquiera de estas herramientas:
- MySQL Workbench
- HeidiSQL
- phpMyAdmin

Conéctate al servidor MySQL usando:
- Host: 127.0.0.1 o localhost
- Puerto: 3306
- Usuario: root o el usuario que hayas creado
- Contraseña: la que configuraste durante la instalación

### Paso 4: Importar el archivo SQL

El archivo restaurante_rick.sql ya contiene:
- La creación de la base de datos
- La creación de todas las tablas
- La inserción de datos de ejemplo

#### Opción A: Importar con MySQL Workbench

1. Abre MySQL Workbench.
2. Conéctate a tu servidor MySQL.
3. Ve a la opción “Data Import”.
4. Selecciona “Import from Self-Contained File”.
5. Busca el archivo restaurante_rick.sql dentro de la carpeta del proyecto.
6. Elige la base de datos de destino o deja que el script cree la base de datos automáticamente.
7. Haz clic en “Start Import”.

#### Opción B: Importar con HeidiSQL

1. Abre HeidiSQL y conéctate al servidor MySQL.
2. Crea una nueva sesión o usa la existente.
3. En la barra de herramientas, busca la opción para ejecutar un archivo SQL.
4. Selecciona restaurante_rick.sql.
5. Ejecuta el script.
6. Espera a que termine la importación.

#### Opción C: Importar desde la terminal de MySQL

Si prefieres usar la línea de comandos, puedes ejecutar:

```bash
mysql -u root -p < restaurante_rick.sql
```

Si tu archivo está en otra ubicación, usa la ruta completa, por ejemplo:

```bash
mysql -u root -p < C:\ruta\al\archivo\restaurante_rick.sql
```

Al ejecutar el comando, se te pedirá la contraseña del usuario MySQL.

### Paso 5: Verificar que la base de datos se haya creado correctamente

Después de importar, verifica que exista la base de datos y las tablas.

Puedes ejecutar estas consultas:

```sql
SHOW DATABASES;
USE restaurant_rico;
SHOW TABLES;
```

Si aparecen tablas como:
- categorias
- productos
- pedidos
- facturas
- detalle_pedidos

entonces la importación fue exitosa.

## 4. Nombre de la base de datos

El archivo SQL crea la base de datos con el nombre:

```sql
restaurant_rico
```

Por lo tanto, la base de datos que debes usar en tu aplicación o conexión será:
- Nombre de la base de datos: restaurant_rico

## 5. Configuración de conexión recomendada

Si tu proyecto necesita conectarse a la base de datos, usa estos datos como referencia:
- Host: localhost o 127.0.0.1
- Puerto: 3306
- Base de datos: restaurant_rico
- Usuario: root o un usuario creado específicamente para el proyecto
- Contraseña: la configurada en MySQL

Ejemplo de conexión en PHP, Java, Python o cualquier lenguaje de programación:

```text
Servidor: localhost
Puerto: 3306
Base de datos: restaurant_rico
Usuario: root
Contraseña: tu_contraseña
```

## 6. Solución de problemas comunes

### Error: “Access denied for user 'root'@'localhost'”

Significa que la contraseña del usuario root no es correcta o que no tienes permisos. Revisa la contraseña configurada durante la instalación.

### Error: “Unknown database 'restaurant_rico'”

Esto ocurre si la base de datos no fue creada correctamente. Vuelve a importar el archivo SQL.

### Error: “Can't connect to MySQL server on '127.0.0.1'”

Verifica que el servicio de MySQL esté funcionando y que el puerto 3306 esté disponible.

### Error al importar el archivo SQL

Asegúrate de:
- Tener permisos suficientes en MySQL
- Estar usando una versión compatible de MySQL
- No tener el archivo abierto en otro programa que lo bloquee

## 7. Resumen rápido

Para instalar la base de datos correctamente:
1. Instala MySQL 8.0.x.
2. Inicia el servicio de MySQL.
3. Conéctate con un cliente como Workbench o HeidiSQL.
4. Importa el archivo restaurante_rick.sql.
5. Verifica que la base de datos restaurant_rico y sus tablas estén presentes.

Si tienes dudas, puedes volver a este documento y seguir los pasos en orden.
