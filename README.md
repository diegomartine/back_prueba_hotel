
# Backend - Laravel Application

Este es el backend de la aplicación desarrollado en **PHP con Laravel**.

## Requisitos

1. **PHP 8.0 o superior**.
2. **Composer**: Para gestionar las dependencias.
3. **Base de datos**: **PostgreSQL**.

## Instalación

1. Clona el repositorio:

   ```bash
   git clone https://github.com/diegomartine/back_prueba_hotel
   cd backend
   ```

2. Copia el archivo `.env.example` a `.env` y configura la base de datos:

   ```bash
   cp .env.example .env
   ```

3. Genera la clave de la aplicación:

   ```bash
   php artisan key:generate
   ```

4. Instala las dependencias con Composer:

   ```bash
   composer install
   ```

5. Inicia el servidor:

   ```bash
   php artisan serve
   ```

**Nota**: No se utilizan migraciones, asegúrate de que la base de datos y las tablas necesarias estén configuradas.

## Problemas Comunes

- **Base de datos**: Verifica que PostgreSQL esté corriendo y que las credenciales en `.env` sean correctas.
- **Migraciones**: No es necesario ejecutar `php artisan migrate`. Las tablas deben estar ya creadas.

## Contribuciones

1. Haz un fork del repositorio.
2. Crea una nueva rama con tus cambios.
3. Haz un Pull Request describiendo los cambios.
```