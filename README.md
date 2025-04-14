# TODO

    IA

-   Mostrar avis de la descarga reconeixement de veu

-   Repasar Testing e2e

-   Landing (cachear i acabar)
    (Peu de pàgina en la landing page (portada) inclou: Copyright, contacte o similar on diga qui ho desenvolupa, política de cookies, tractament de dades i avis legal (més o menys). No cal que estiga tot "legalista" pots fer una versió a partir d'alguna web inclús indicant que és un projecte o treball i que no està en producció. No voldria que consumires massa temps en aquest apartat però deixar ja els enllaços (en qualsevol projecte real caldria posar-ho))

    ESTILOS

-   Mejorar logo / Buscar nuevo logo
-   Responsive design nav

--- AMPLIACIONES

-   i18n, idiomes
-   Pasarela de Pago

-   Estadisticas usuario

    -   Budgets por estado
    -   Agrupar por cliente
    -   Graficos

-   Iframe para PDF
    -   Mini Captura para a poder enviar por app de mensageria

# Budget App

Este proyecto es una aplicación web desarrollada con el framework Laravel que permite la gestión de presupuestos. Los usuarios pueden introducir detalles de sus costes, y la aplicación genera un presupuesto formateado listo para enviar al cliente.

## Stack

-   Laravel
-   PostgreSQL
-   VueJS
-   TailwindCSS

## Estructura de Base de Datos

1. Users
2. Budgets
3. Costs
4. Suscriptions
5. Clients
6. User_Client
7. Aditiional_Prompt
8. Support
9. Plans

## Requisitos

-   PHP >= 7.3
-   Composer
-   MySQL o cualquier otro sistema de gestión de bases de datos compatible

## Instalación

1. Clona el repositorio:

    ```bash
    git clone https://github.com/usuari/projecte-laravel.git
    ```

2. Accede al directorio del proyecto:

    ```bash
    cd projecte-laravel
    ```

3. Instala las dependencias:

    ```bash
    composer install && npm install
    ```

4. Crea un archivo `.env` a partir del archivo de ejemplo:

    ```bash
    cp .env.example .env
    ```

5. Genera la clave de la aplicación:

    ```bash
    php artisan key:generate
    ```

6. Configura la conexión a la base de datos en el archivo `.env`.

7. Ejecuta las migraciones para crear las tablas de la base de datos:

    ```bash
    php artisan migrate
    ```

8. Imprescindible: Puebla la base de datos con datos de prueba, se crean los planes, si no, no se podrán crear las suscripciones:
    ```bash
    php artisan db:seed
    ```

## Uso

1. Inicia el servidor de desarrollo:

    ```bash
    php artisan serve y npm run dev
    ```

2. Accede a la aplicación a través del navegador:

    ```
    http://localhost:8000
    ```

3. Regístrate o inicia sesión para comenzar a gestionar tus tareas.

4. Crea, edita y elimina tareas según tus necesidades.

## Estructura del proyecto

-   `app/`: Contiene los modelos, controladores y servicios de la aplicación.
-   `database/`: Contiene las migraciones y seeders.
-   `resources/`: Contiene las vistas y los recursos estáticos.
-   `routes/`: Contiene los archivos de rutas de la aplicación.

## Contribución

Si quieres contribuir a este proyecto, por favor, sigue los siguientes pasos:

1. Haz un fork del repositorio.
2. Crea una nueva rama (`git checkout -b feature/nueva-funcion`).
3. Haz tus cambios y comitéalos (`git commit -am 'Añade nueva función'`).
4. Sube los cambios a tu rama (`git push origin feature/nueva-funcion`).
5. Crea una pull request.

## Licencia

Este proyecto está licenciado bajo la [MIT License](LICENSE).
