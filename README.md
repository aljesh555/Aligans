# Sports Management System

A comprehensive system for managing sports product sales, inventory, events, and teams.

## Project Structure

This project consists of four main components:

1. **Laravel API Server** - Backend RESTful API
2. **Flutter Mobile App** - Mobile application for customers
3. **Admin Dashboard** - Web interface for administrators
4. **Web Application** - Customer-facing SPA

## Setup Instructions

### Prerequisites
- PHP 8.1+ and Composer
- Node.js and npm
- Flutter SDK
- MySQL or PostgreSQL

### 1. Laravel API Server

```bash
# Navigate to the API server directory
cd api-server

# Install Laravel
composer create-project laravel/laravel .

# Install dependencies
composer install

# Set up environment file
cp .env.example .env
php artisan key:generate

# Configure your database in .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=sports_management
# DB_USERNAME=root
# DB_PASSWORD=

# Run migrations
php artisan migrate

# Seed database with initial data
php artisan db:seed

# Start development server
php artisan serve
```

### 2. Flutter Mobile App

```bash
# Navigate to the mobile app directory
cd mobile-app

# Create a new Flutter project
flutter create .

# Install dependencies
flutter pub get

# Build and run the app
flutter run
```

### 3. Admin Dashboard

```bash
# Navigate to the admin dashboard directory
cd admin-dashboard

# Create new React app
npx create-react-app . # or Vue: npm init vue@latest .

# Install dependencies
npm install

# Start development server
npm start # or npm run dev for Vue
```

### 4. Web Application (SPA)

```bash
# Navigate to the web app directory
cd web-app

# Create new React app
npx create-react-app . # or Vue: npm init vue@latest .

# Install dependencies
npm install

# Start development server
npm start # or npm run dev for Vue
```

## Development Roadmap

Please check AIProgress.txt for current project status and planned developments. 