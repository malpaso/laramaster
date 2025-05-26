# Project Name - Laramaster

## Overview

This application facilitates the management of companies and employees.

## Tech Stack

- **Backend**: Laravel 10.x
- **Database**: SQLite
- **Frontend**: Vue.js / Inertia
- **Styling**: Shadcn-vue / Tailwind CSS
- **Testing**: Pest

## Prerequisites

Before running this application, ensure you have:

- PHP 8.1 or higher
- Composer
- Node.js 18+ and npm
- SQLite3
- Git

## Installation

### 1. Clone the Repository
- If using Laravel Herd, make sure to clone into your `Sites` directory.
```bash
git clone https://github.com/malpaso/laramaster.git
cd laramaster
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node Dependencies

```bash
npm install
```

### 4. Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 6. Database Setup

- **The database is SQLite, and the file is located in the `database` directory. Migrations are already run and the database is seeded with sample data.**

```bash
# Refresh the database file
php artisan migrate:fresh

# Run migrations
php artisan migrate

# Seed database with sample data (optional)
php artisan db:seed
```

### 7. Build Assets

```bash
npm run build
```

### 8. Start the Application
- If using Laravel Herd, the application will be available at `http://laramaster.test`

```bash
# Start the Vite development server
# Only if running in development mode
npm run dev
```

```bash
# Not necessary if using Laravel Herd
php artisan serve
```

The application will be available at `http://localhost:8000`

## Key Features

- Feature 1: Companies - create and edit company information.
- Feature 2: Employees - create and manage employees per company.
- Feature 3: Dashboard with relevant daily statistics

## Testing

Run the test suite:

```bash
# Run all tests
php artisan test

# Run specific test files
php artisan test tests/Feature/Http/Controllers/CompanyControllerTest.php
php artisan test tests/Feature/Http/Controllers/EmployeeControllerTest.php
```

## Database Schema

Key tables:

- **users**: User authentication and profiles
- **companies**: Company information
- **employees**: Company employees

## Deployment Notes

For production deployment:

```bash
# Optimize for production
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build
```

## Troubleshooting

### Common Issues

**Database Connection Error**
- Verify SQLite3 is installed
- Ensure database file exists

**Permission Errors**
```bash
chmod -R 775 storage bootstrap/cache
```

**Missing Dependencies**
```bash
composer install
npm install
```

---
