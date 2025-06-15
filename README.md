# Multi-Tenant Inspection Booking System

[![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)

## Overview

This is a modular, multi-tenant SaaS application built with Laravel, designed to manage inspection bookings for different companies (tenants). Each tenant can manage users, inspection teams, team members, team availability, and dynamically generate time slots for bookings. The system ensures no scheduling conflicts and provides a comprehensive RESTful API for interaction.

### Modules
1. **Tenant** : Handles multi-tenancy functionality
2. **User** : Manages user accounts and authentication
3. **Auth** : Handles authentication and authorization
4. **Teams** : Manages inspection teams
5. **TeamMembers** : Manages team membership
6. **TeamAvailability** : Manages team availability schedules
7. **Bookings** : Handles the booking process for inspections

### Multi-tenancy
The system uses a multi-tenant architecture where each tenant has its own isolated data. The BelongsToTenant trait is used to scope models to the current tenant. Middleware is used to enforce tenant access control:

- **TenantScopeMiddleware** : Sets the current tenant based on the request
- **EnsureTenantAccess** : Ensures the user has access to the current tenant
- **EnsureTenantAdmin** : Ensures the user is an admin for the current tenant

## Features

- ✅ Multi-tenancy with tenant_id scoping
- ✅ User management with full CRUD operations
- ✅ Team management with member syncing
- ✅ Team availability management with individual and bulk (sync) operations
- ✅ Dynamic generation of 1-hour time slots based on team availability
- ✅ Booking system with conflict prevention
- ✅ Token-based API authentication using Laravel Sanctum
- ✅ Modular HMVC structure

## Tech Stack

| Technology | Purpose |
|------------|---------|
| **Backend** | Laravel (latest version) |
| **Authentication** | Laravel Sanctum |
| **Database** | MySQL |
| **Folder Structure** | HMVC with /Modules |

## Setup Instructions

### 1. Clone the Repository:
```bash
git clone https://github.com/yourusername/your-repo.git
cd your-repo
```

### 2. Install Dependencies:
```bash
composer install
```

### 3. Set Up Environment Variables:

Copy .env.example to .env and configure your database, app URL, and Sanctum settings:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 4. Run Migrations And Seed Some Dummy Data For Tenants And Users:
```bash
php artisan migrate --seed
```

### 5. Start the Server:
```bash
php artisan serve
```

### API Base URL:

Access the API at `http://localhost:8000/api/v1`.

## API Usage

### Authentication:

Use `POST /api/v1/login` to obtain a token.

Include the token in the Authorization header as `Bearer {token}` for protected routes.

### Tenant Resolution:

Tenants are resolved via subdomains (e.g., `tenantone.localhost`) or custom domains.

For testing, use the `X-Tenant-Slug` header (e.g., `X-Tenant-Slug: tenantone`) or config the host file from system32 in windows.

### Key Endpoints:

| Endpoint | Description |
|----------|-------------|
| **Users** | `GET /api/v1/users`, `POST /api/v1/users`, `PATCH /api/v1/users/{id}`, `DELETE /api/v1/users/{id}` |
| **Teams** | `GET /api/v1/teams`, `POST /api/v1/teams`, `GET /api/v1/teams/{id}`, `PATCH /api/v1/teams/{id}`, `DELETE /api/v1/teams/{id}` |
| **Team Members** | `GET /api/v1/teams/{team}/members`, `POST /api/v1/teams/{team}/members/sync` |
| **Team Availability** | `GET /api/v1/teams/{team}/availability`, `POST /api/v1/teams/{team}/availability/sync`, `POST /api/v1/team-availability`, `PATCH /api/v1/team-availability/{id}`, `DELETE /api/v1/team-availability/{id}` |
| **Generate Slots** | `GET /api/v1/teams/{id}/generate-slots?from=2025-06-01&to=2025-06-07` |
| **Bookings** | `GET /api/v1/bookings`, `POST /api/v1/bookings`, `DELETE /api/v1/bookings/{id}` |

### Full API Documentation:

See `/api.md` for detailed endpoint descriptions, including all additional CRUD operations and features.

## Multi-Tenancy and Time Slot Generation

### Multi-Tenancy: 
Data is isolated using tenant_id scoping. Middleware ensures users only access their tenant's data.

### Time Slot Generation: 
Slots are generated dynamically based on team availability and existing bookings. They are not stored in the database, ensuring real-time accuracy.

## Additional Features

- **User Management**: Full CRUD operations for managing users within each tenant.
- **Team Member Sync**: Easily manage team members with a sync endpoint that allows adding or removing multiple members at once.
- **Availability Management**: Manage team availability with both individual CRUD operations and a sync endpoint for bulk updates.