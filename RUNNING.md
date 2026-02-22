# Local Development Guide

## Prerequisites
- Docker & Docker Compose
- Node.js & NPM
- PHP 8.4 & Composer (Optional, but recommended for local CLI)

## Installation
The project uses [Laravel Sail](https://laravel.com/docs/sail) for local development.

To install everything and bootstrap the project, run:
```bash
make install
```
This command will:
1. Create a `.env` file from `.env.example`.
2. Install PHP dependencies via a temporary Docker container (bootstrapping Sail).
3. Start the Docker containers via Sail.
4. Install root NPM dependencies (via Sail).
5. Install Nuxt-specific dependencies in `resources/nuxt/admin`.
6. Run migrations and seed the database with test data.

## Running the Project
To start the application and the Nuxt development server, run:
```bash
make dev
```
- **Laravel API/Web**: [http://localhost:8080](http://localhost:8080)
- **Nuxt Dev Server**: [http://localhost:3000](http://localhost:3000) (Default Nuxt port)

### Available Make Commands
Run `make help` or simply `make` to see all available commands.

- `make up`: Start containers.
- `make down`: Stop containers.
- `make fresh`: Reset and re-seed the database.
- `make test`: Run Pest tests.
- `make build`: Build and generate Nuxt assets into `public/dist` (for local production-like testing).

## Frontend Structure
- **Nuxt Application**: Located in `resources/nuxt/admin`.
- **Laravel Views/Assets**: Standard Laravel structure for fallback or future migrations.
