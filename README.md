El proyecto utiliza variables de entorno mediante un archivo .env y la librería vlucas/phpdotenv.

Para ejecutarlo:

Copiar el archivo .env.example, renombrar la copia a .env y completar las credenciales de la base de datos.
Iniciar MySQL desde XAMPP, abrir phpMyAdmin, crear una base de datos llamada ppampa e importar el archivo public/database/ppampa_backup.sql.
Abrir una terminal en la raíz del proyecto y ejecutar composer install para instalar las dependencias.
Desde esa misma carpeta, iniciar el servidor PHP con php -S localhost:8000 -t public.
Abrir http://localhost:8000 en el navegador.
