# Project Structure

## Overview
This project is a full-stack sports management application that consists of:
- Backend API server (Laravel)
- Frontend SPA client (Vue.js)
- Mobile App (Flutter)
- Admin Dashboard (placeholder)

## Root Structure (D:/aligans)

```
aligans/
├── api-server/               # Laravel Backend (Sports Management API)
├── spa-client/               # Vue.js SPA (Customer-facing Web App)
├── mobile-app/               # Flutter Admin App
├── admin-dashboard/          # Web-based Admin Dashboard (empty directory)
├── ProjectStructure.md       # This file - project overview document
├── AIProgress.txt            # AI-generated progress notes
├── command.txt               # AI/CLI command log
├── prompt.txt                # History of prompts and interactions
├── README.md                 # General project overview
└── composer-setup.exe        # PHP Composer installer
```

## Backend (api-server/)
The Laravel backend provides the API endpoints and database management.

```
api-server/
├── app/
│   ├── Console/                 # Console commands
│   ├── Exceptions/              # Exception handlers
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Api/            # API controllers including StorefrontController
│   │   ├── Middleware/         # Request middleware
│   │   └── Requests/           # Form requests and validation
│   ├── Models/                  # Eloquent models (User, Product, Event, Team, etc.)
│   └── Providers/               # Service providers
├── bootstrap/                   # Framework bootstrap files
├── config/                      # Configuration files
├── database/
│   ├── factories/               # Model factories for testing/seeding
│   ├── migrations/              # Database migrations
│   └── seeders/                 # Database seeders including AttendanceSeeder
├── mobile-app/                  # Duplicate mobile app folder (should be removed)
├── lang/                        # Language files
├── public/                      # Publicly accessible files
├── resources/                   # Views and uncompiled assets
├── routes/
│   └── api.php                  # API routes definition
├── storage/                     # Application storage
├── tests/                       # Automated tests
├── vendor/                      # Composer dependencies
├── .env                         # Environment variables
├── artisan                      # Laravel command-line tool
├── composer.json                # PHP dependencies
└── other config files           # Various configuration files
```

## Frontend (spa-client/)
The Vue.js SPA handles the client-side user interface.

```
spa-client/
├── public/                      # Static assets
│   └── index.html               # Main HTML file
├── src/
│   ├── assets/                  # Static assets (images, fonts, etc.)
│   ├── components/              # Reusable Vue components
│   │   ├── Navbar.vue           # Navigation bar component
│   │   ├── TestCard.vue         # Test component
│   │   └── TailwindTest.vue     # Tailwind CSS test component
│   ├── layouts/                 # Layout components
│   ├── router/
│   │   └── index.js             # Vue Router configuration
│   ├── services/                # API service modules
│   ├── store/                   # Vuex store modules
│   ├── views/                   # Page components
│   │   ├── auth/                # Authentication pages
│   │   │   ├── Login.vue        # Login page
│   │   │   └── Register.vue     # Registration page
│   │   ├── events/              # Event-related pages
│   │   │   ├── Events.vue       # Events listing page
│   │   │   └── EventDetail.vue  # Event details page
│   │   ├── products/            # Product-related pages
│   │   │   ├── Products.vue     # Products listing page
│   │   │   └── ProductDetail.vue # Product details page
│   │   ├── teams/               # Team-related pages
│   │   │   ├── Teams.vue        # Teams listing page
│   │   │   └── TeamDetail.vue   # Team details page
│   │   └── Home.vue             # Homepage
│   ├── App.vue                  # Root Vue component
│   └── main.js                  # Application entry point
├── node_modules/                # Node.js dependencies
├── .gitignore                   # Git ignore file
├── babel.config.js              # Babel configuration
├── package.json                 # NPM dependencies
├── tailwind.config.js           # Tailwind CSS configuration
└── vue.config.js                # Vue CLI configuration
```

## Mobile App (Flutter)
The Flutter mobile app serves as an admin interface.

```
mobile-app/
├── lib/                         # Flutter application code
├── assets/                      # Application assets
├── .env                         # Environment variables
└── pubspec.yaml                 # Flutter dependencies
```

## Admin Dashboard
An empty directory reserved for a future web-based admin dashboard implementation.

## Environment Setup
- Backend API server runs at: http://127.0.0.1:8000 (using PHP artisan serve)
- Frontend SPA client runs at: http://localhost:8088 (using npm run serve)

## Key Features
1. User authentication (login/register)
2. Product browsing and management
3. Event management
4. Team management with player profiles
5. Attendance tracking for players

## Development Workflow
1. Start the Laravel backend server: `cd api-server && php artisan serve`
2. Start the Vue.js development server: `cd spa-client && npm run serve`
3. Access the application at http://localhost:8088

## Recent Changes
- Router (spa-client/src/router/index.js) has been updated to remove references to deleted files:
  - Removed import for TailwindTest.vue and NotFound.vue
  - Removed TailwindTest route
  - Updated 404 route to redirect to Home instead of using NotFound component
- Removed NotFound.vue and TailwindTest.vue files from the views directory

## Known Issues
- There is a duplicate mobile-app folder inside api-server that should be addressed
- The admin-dashboard directory exists but is currently empty

## Notes
- This structure document is regularly updated to reflect the current project state 