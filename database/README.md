# Base de demostración Pampa

Archivos:
- `schema_demo.sql`: estructura de las seis tablas, sin datos personales.
- `demo_seed.sql`: 5 categorías, 3 proveedores ficticios y 25 productos ficticios.

**No importar estos scripts en la base actual `ppampa`**: el esquema usa `DROP TABLE IF EXISTS` y elimina tablas existentes.

## Preparar una base nueva en Fedora / MariaDB

1. Hacer primero un respaldo de la base actual (fuera del repositorio):

```bash
mariadb-dump -u TU_USUARIO -p ppampa > ~/ppampa_respaldo_privado.sql
```

2. Crear una base separada y cargar estructura y catálogo:

```bash
mariadb -u TU_USUARIO -p -e "CREATE DATABASE ppampa_demo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mariadb -u TU_USUARIO -p ppampa_demo < database/schema_demo.sql
mariadb -u TU_USUARIO -p ppampa_demo < database/demo_seed.sql
```

3. Cambiar `DB_NAME=ppampa_demo` en el archivo `.env` **local** (no subirlo a Git).
4. Iniciar la aplicación, registrar usuarios de demostración desde la interfaz y realizar algunas compras para generar ventas y estadísticas auténticas del flujo de la aplicación.
5. Probar catálogo, registro, login, carrito, pedidos y administración. Para crear un administrador de demostración, registrar una cuenta y asignarle `rol='admin'` mediante SQL local, únicamente en la base demo.

## Trasladar a la notebook

Con la base demo ya probada, exportarla a un archivo **privado**:

```bash
mariadb-dump -u TU_USUARIO -p --single-transaction ppampa_demo > ~/ppampa_demo_export.sql
```

Copiar el archivo a la notebook por un medio privado. Allí crear `ppampa_demo` e importar:

```bash
mariadb -u TU_USUARIO -p -e "CREATE DATABASE ppampa_demo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mariadb -u TU_USUARIO -p ppampa_demo < ~/ppampa_demo_export.sql
```

Clonar/actualizar el proyecto, ejecutar `composer install`, configurar el `.env` de la notebook y probar. **No subir el dump con usuarios ni hashes de contraseña a GitHub**, aunque sean de demostración.

Los nombres, precios, CUIT ficticios y existencias de `demo_seed.sql` son ilustrativos; verificarlos antes de cualquier uso comercial.
