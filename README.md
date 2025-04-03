# TODO

-   Repasar Testing e2e

-   Landing (cachear i acabar)

    IA

-   Crear la View en la que s'interactue en el prompt + prompt por defecto
-   Speech recognition
-   Tabla BBD Prompt
-   Generar presupuesto con prompt IA
-   Limitar el uso de la IA segun el plan (creditos)

    BUDGETS

-   Agrupar botons de acciones en budgets en responsive
-   Acabar el boto de content en responsive
-   Millorar pdf
-   Cuando haya muchos items en content, que muestre ...

    ESTILOS

-   Mejorar logo / Buscar nuevo logo
-   Responsive design nav
-   Dark mode Login, Register

--- AMPLIACIONS

-   i18n, idiomes
-   Pasarela de Pago

-   Estadisticas usuario
    -   Budgets por estado
    -   Agrupar por cliente
    -   Graficos

# Budget App

Aquest projecte és una aplicació web desenvolupada amb el framework Laravel que permet la gestió de pressupostos. Els usuaris poden introduir detalls dels seus costos, i l'aplicació genera un pressupost formatejat llest per enviar al client.

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

[Estructura de Base de Datos](./esquema_reduit.pdf)

## Requisits

-   PHP >= 7.3
-   Composer
-   MySQL o qualsevol altre sistema de gestió de bases de dades compatible
    [!("./esquema_reduit.pdf")](esquema_reduit.pdf)

## Instal·lació

1. Clona el repositori:

    ```bash
    git clone https://github.com/usuari/projecte-laravel.git
    ```

2. Accedeix al directori del projecte:

    ```bash
    cd projecte-laravel
    ```

3. Instal·la les dependències:

    ```bash
    composer install && npm install
    ```

4. Crea un fitxer `.env` a partir del fitxer d'exemple:

    ```bash
    cp .env.example .env
    ```

5. Genera la clau de l'aplicació:

    ```bash
    php artisan key:generate
    ```

6. Configura la connexió a la base de dades al fitxer `.env`.

7. Executa les migracions per crear les taules de la base de dades:

    ```bash
    php artisan migrate
    ```

8. Opcional: Popula la base de dades amb dades de prova:
    ```bash
    php artisan db:seed
    ```

## Ús

1. Inicia el servidor de desenvolupament:

    ```bash
    php artisan serve y npm run dev
    ```

2. Accedeix a l'aplicació a través del navegador:

    ```
    http://localhost:8000
    ```

3. Registra't o inicia sessió per començar a gestionar les teves tasques.

4. Crea, edita i elimina tasques segons les teves necessitats.

## Estructura del projecte

-   `app/`: Conté els models, controladors i serveis de l'aplicació.
-   `database/`: Conté les migracions i seeders.
-   `resources/`: Conté les vistes i els recursos estàtics.
-   `routes/`: Conté els fitxers de rutes de l'aplicació.

## Contribució

Si vols contribuir a aquest projecte, si us plau, segueix els següents passos:

1. Fes un fork del repositori.
2. Crea una nova branca (`git checkout -b feature/nova-funcio`).
3. Fes els teus canvis i commiteja'ls (`git commit -am 'Afegeix nova funció'`).
4. Puja els canvis a la teva branca (`git push origin feature/nova-funcio`).
5. Crea una pull request.

## Llicència

Aquest projecte està llicenciat sota la [MIT License](LICENSE).
