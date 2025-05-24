# Budget App 📊

Este proyecto es una aplicación web desarrollada con el framework Laravel que permite la gestión de presupuestos. Los usuarios pueden introducir detalles de sus costes, y la aplicación genera un presupuesto formateado listo para enviar al cliente.

## Características principales

-   Generación de presupuestos con IA: Utiliza reconocimiento de voz para crear presupuestos de forma automática.
-   Sistema de suscripciones: Diferentes planes con distintos niveles de acceso a funcionalidades.
-   Experiencia PWA completa: Instalable como aplicación nativa y funcionalidad offline.
-   Modo oscuro/claro: Interfaz adaptable a las preferencias del usuario.
-   Diseño responsive: Optimizado para todas las pantallas y dispositivos.

## Tecnologias

![](https://img.shields.io/badge/-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![](https://img.shields.io/badge/-316192?style=for-the-badge&logo=postgresql&logoColor=white)
![](https://img.shields.io/badge/-35495E?style=for-the-badge&logo=vuedotjs&logoColor=4FC08D)
![](https://img.shields.io/badge/-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)

## BBD

-   **Users**: Gestión de usuarios del sistema
-   **Budgets**: Presupuestos creados por los usuarios
-   **Costs**: Detalles de costes asociados a cada presupuesto
-   **Subscriptions**: Control de planes de suscripción
-   **Clients**: Información de los clientes
-   **User_Client**: Relación entre usuarios y clientes
-   **Additional_Prompt**: Configuraciones personalizadas
-   **Support**: Sistema de soporte y ayuda
-   **Plans**: Diferentes planes de suscripción disponibles

## 📝 Requisitos Previos

-   PHP >= 7.3
-   Composer
-   Sistema de gestión de bases de datos compatible con Eloquent (PostgreSQL recomendado)
-   npm

## 🚀 Instalación

1. **Clonar el repositorio**:

    ```bash
    git clone https://github.com/usuari/projecte-laravel.git
    ```

2. **Acceder al directorio del proyecto**:

    ```bash
    cd projecte-laravel
    ```

3. **Instalar las dependencias**:

    ```bash
    composer install && npm install
    ```

4. **Configurar el entorno**:

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

5. **Configurar base de datos y servicio API de IA (si se quiere usar la funcionalidad)** en el archivo `.env`

6. **Aplicar migraciones**:

    ```bash
    php artisan migrate
    ```

7. **Poblar la base de datos** (obligatorio para crear planes y permitir inicio de sesión):

    ```bash
    php artisan db:seed
    ```

## 💻 Uso

1. **Iniciar los servidores de desarrollo o iniciar Docker (se debe comentar la ultima parte del SSL)**:

    ```bash
    php artisan serve
    ```

    En otra terminal:

    ```bash
    npm run dev
    ```

    vs

    ```bash
    docker-compose up -d
    ```

2. **Acceder a la aplicación**:

    [http://localhost:8000](http://localhost:8000)

3. **Registrarse o iniciar sesión** para comenzar a gestionar presupuestos.

4. **Crear y gestionar presupuestos** para tus clientes.

## 📁 Estructura del Proyecto

-   `app/`: Modelos, controladores y servicios
-   `database/`: Migraciones y seeders
-   `resources/`: Vistas y recursos estáticos
-   `routes/`: Definición de rutas
-   `config/`: Archivos de configuración
-   `public/`: Punto de entrada y assets públicos

## 🤝 Contribución

Si deseas contribuir a este proyecto:

1. Haz un fork del repositorio
2. Crea una nueva rama (`git checkout -b feature/nueva-funcion`)
3. Realiza tus cambios y haz commit (`git commit -am 'Añade nueva función'`)
4. Sube los cambios a tu rama (`git push origin feature/nueva-funcion`)
5. Abre una Pull Request

## 📝 Licencia

Este proyecto es privado y no está disponible para distribución pública. Todos los derechos están reservados. No se permite la copia, modificación, distribución o uso del código sin el permiso explícito del propietario.

## 🔜 Futuras Mejoras

-   **Internacionalización**

    -   Soporte multi-idioma

-   **Pasarela de Pago**

    -   Integración con plataformas de pago

-   **Estadísticas de Usuario**
-   ~~Visualización de presupuestos por estado~~
-   ~~Agrupación por cliente~~
    -   Gráficos y reportes

-   **Visualización de PDF**
    -   Iframe para visualizar PDFs sin descargar
    -   Miniaturas para compartir por aplicaciones de mensajería
