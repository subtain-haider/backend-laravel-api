# Laravel API Backend

This is the backend API for the News App, built with Laravel and Docker.

## Requirements

- Docker

## Installation

1. Clone the repository:
    git clone https://github.com/subtain-haider/backend-laravel-api

2. Navigate to the project directory:
    cd backend-laravel-api

3. Copy the `.env.example` file to a new file called `.env`:
    cp .env.example .env

4. Update the `.env` file with your database and other configuration settings. Make sure to set the `DB_HOST` to the name of the database service defined in your `docker-compose.yml` file (e.g., `DB_HOST=db` if the database service is named "db").

5. Add your API keys for the following services to the `.env` file:
    NEWS_API_KEY=<your_news_api_key>
    GUARDIAN_API_KEY=<your_guardian_api_key>
    NEW_YORK_TIMES_API_KEY=<your_new_york_times_api_key>
    Replace `<your_news_api_key>`, `<your_guardian_api_key>`, and `<your_new_york_times_api_key>` with your actual API keys.

6. Build the Docker containers using the `docker-compose.yml` file provided in the root directory of your project:
    docker-compose build

7. Start the Docker containers:
    docker-compose up -d

8. Install the dependencies:
    docker-compose exec app composer install

9. Generate an application key:
    docker-compose exec app php artisan key:generate

10. Run the database migrations:
    docker-compose exec app php artisan migrate

The API will be available at `http://localhost:8000`.

## Endpoints

* List available endpoints and their descriptions here

## Deployment

* Include any deployment instructions specific to your project
