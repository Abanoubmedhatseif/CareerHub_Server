# CareerHub-backend

RESTful API backend using Laravel

## Installation Instructions

-   Install Dependencies
    ```bash
    composer install
    ```

*   Create .env file

    ```bash
    cp .env.example .env
    ```

*   Generate encryption key

    ```bash
    php artisan key:generate
    ```

*   Migrate DB

    ```bash
    php artisan migrate
    ```

*   Seed DB

    ```bash
    php artisan db:seed
    ```

*   Add your cloudinary credentials to upload images and CVs

    You will find the necessary environment variables you need to fill in the .env.example file

-   Enjoy ^\_^
