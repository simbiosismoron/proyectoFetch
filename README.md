# Pampa — E-commerce de distribuidora

Proyecto académico desarrollado con PHP, JavaScript, HTML, CSS y MySQL/MariaDB. Incluye catálogo, carrito, registro e inicio de sesión, pedidos y panel administrativo.

## Requisitos

- Windows con XAMPP (MySQL/MariaDB y PHP) o un entorno equivalente.
- Composer instalado y disponible desde la terminal.
- PHP con extensiones PDO y pdo_mysql habilitadas.

> GitHub almacena el código, pero no ejecuta la aplicación PHP. Para verla funcionando hay que configurar una base de datos e iniciar un servidor PHP.

## Instalación en Windows con XAMPP

### 1. Descargar el proyecto

Clonar el repositorio con Git o usar **Code > Download ZIP** en GitHub. Extraer el ZIP si corresponde.

Abrir una terminal en la **carpeta raíz del proyecto** (la que contiene `composer.json`, `app/`, `config/` y `public/`).

### 2. Iniciar la base de datos

Abrir el panel de control de XAMPP e iniciar **MySQL**. Abrir `http://localhost/phpmyadmin`.

Crear una base de datos llamada `ppampa_demo` con codificación `utf8mb4` e importar el archivo:

```text
database/schema_demo.sql
```

Este archivo contiene **solo la estructura de las tablas**, sin usuarios, productos ni pedidos de ejemplo. Para probar el catálogo se deberán cargar categorías, proveedores y productos de prueba; para acceder al panel administrativo se necesitará un usuario con rol `admin`.

**Atención:** la importación crea o reemplaza tablas. Utilizar una base de datos de prueba, no una base con información que se desee conservar.

### 3. Configurar variables de entorno

Copiar `.env.example` y renombrar la copia como `.env` en la raíz del proyecto. Para una instalación predeterminada de XAMPP:

```dotenv
DB_HOST=localhost
DB_NAME=ppampa_demo
DB_USER=root
DB_PASSWORD=
```

Si el usuario MySQL tiene contraseña, colocarla en `DB_PASSWORD`. phpMyAdmin es una interfaz para administrar MySQL; las credenciales corresponden al servidor de base de datos.

No subir el archivo `.env` a GitHub.

### 4. Instalar dependencias

Desde la raíz del proyecto, ejecutar:

```bash
composer install
```

Composer instalará las dependencias en `vendor/`, incluida `vlucas/phpdotenv`, que lee el archivo `.env`.

### 5. Iniciar el servidor web

Desde la raíz del proyecto:

```bash
php -S localhost:8000 -t public
```

Si Windows no reconoce `php`, se puede utilizar el ejecutable de XAMPP:

```powershell
C:\xampp\php\php.exe -S localhost:8000 -t public
```

Abrir en el navegador:

**http://localhost:8000**

No abrir `public/index.php` con doble clic: PHP necesita ejecutarse en un servidor. La carpeta `public/` es la raíz pública del sitio. No es necesario iniciar Apache si se utiliza el servidor integrado de PHP; sí debe estar iniciado MySQL.

## Estructura principal

- `public/`: punto de entrada del sitio, JavaScript, estilos y endpoints API.
- `app/controllers/`: validación y coordinación de operaciones.
- `app/models/`: acceso a datos y reglas de la aplicación.
- `app/exceptions/`: excepciones específicas.
- `config/database.php`: conexión PDO a la base de datos.
- `database/schema_demo.sql`: esquema SQL para una instalación de prueba.

## Problemas frecuentes

- **Falta `vendor/autoload.php`**: ejecutar `composer install` desde la raíz.
- **Error de conexión a la base de datos**: comprobar que MySQL esté iniciado, que la base exista y que los valores de `.env` sean correctos.
- **No se reconoce `php`**: utilizar `C:\xampp\php\php.exe` o configurar PHP en el PATH.
- **El catálogo aparece vacío**: `schema_demo.sql` no contiene productos; hay que agregar datos de prueba.
- **El puerto 8000 está ocupado**: cambiarlo, por ejemplo a `8001`, tanto en el comando como en la URL.

## Nota

Este repositorio es un proyecto académico y el servidor integrado de PHP se utiliza para desarrollo y demostración local, no como configuración de producción.
