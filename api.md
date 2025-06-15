# API Documentation

## Authentication

### Login
- **URL** : `/api/v1/login`
- **Method** : `POST`
- **Middleware** : `tenant.scope`
- **Description** : Authenticates a user and returns a token
- **Request Body** :
  ```json
  {
    "email": "user@example.com",
    "password": "password"
  }
  ```
- **Response** : Returns authentication token and user details

### Register
- **URL** : `/api/v1/register`
- **Method** : `POST`
- **Middleware** : `tenant.scope`
- **Description** : Registers a new user
- **Request Body** :
  ```json
  {
    "name": "John Doe",
    "email": "user@example.com",
    "password": "password",
    "password_confirmation": "password"
  }
  ```
- **Response** : Returns authentication token and user details

### Logout
- **URL** : `/api/v1/logout`
- **Method** : `POST`
- **Middleware** : `auth:sanctum`, `tenant.scope`, `tenant.access`
- **Description** : Logs out the current user
- **Response** : Success message

## Tenant Management (Superadmin only)

### List Tenants
- **URL** : `/api/v1/tenants`
- **Method** : `GET`
- **Middleware** : `auth:sanctum`, `superadmin`
- **Description** : Returns a list of all tenants

### Get Tenant
- **URL** : `/api/v1/tenants/{id}`
- **Method** : `GET`
- **Middleware** : `auth:sanctum`, `superadmin`
- **Description** : Returns details of a specific tenant

### Create Tenant
- **URL** : `/api/v1/tenants`
- **Method** : `POST`
- **Middleware** : `auth:sanctum`, `superadmin`
- **Description** : Creates a new tenant
- **Request Body** :
  ```json
  {
    "name": "Tenant Name",
    "slug": "tenant-slug",
    "domain": "tenant.example.com",
    "is_active": true
  }
  ```

### Update Tenant
- **URL** : `/api/v1/tenants/{id}`
- **Method** : `PUT/PATCH`
- **Middleware** : `auth:sanctum`, `superadmin`
- **Description** : Updates an existing tenant

### Delete Tenant
- **URL** : `/api/v1/tenants/{id}`
- **Method** : `DELETE`
- **Middleware** : `auth:sanctum`, `superadmin`
- **Description** : Deletes a tenant

## User Management

### List Users
- **URL** : `/api/v1/users`
- **Method** : `GET`
- **Middleware** : `auth:sanctum`, `tenant.scope`, `tenant.access`, `tenant.admin`
- **Description** : Returns a list of users for the current tenant

### Get User
- **URL** : `/api/v1/users/{id}`
- **Method** : `GET`
- **Middleware** : `auth:sanctum`, `tenant.scope`, `tenant.access`, `tenant.admin`
- **Description** : Returns details of a specific user

### Create User
- **URL** : `/api/v1/users`
- **Method** : `POST`
- **Middleware** : `auth:sanctum`, `tenant.scope`, `tenant.access`, `tenant.admin`
- **Description** : Creates a new user

### Update User
- **URL** : `/api/v1/users/{id}`
- **Method** : `PUT/PATCH`
- **Middleware** : `auth:sanctum`, `tenant.scope`, `tenant.access`, `tenant.admin`
- **Description** : Updates an existing user

### Delete User
- **URL** : `/api/v1/users/{id}`
- **Method** : `DELETE`
- **Middleware** : `auth:sanctum`, `tenant.scope`, `tenant.access`, `tenant.admin`
- **Description** : Deletes a user

## Team Management

### List Teams
- **URL** : `/api/v1/teams`
- **Method** : `GET`
- **Middleware** : `auth:sanctum`, `tenant.scope`, `tenant.access`, `tenant.admin`
- **Description** : Returns a list of teams for the current tenant

### List Active Teams
- **URL** : `/api/v1/teams/active`
- **Method** : `GET`
- **Middleware** : `auth:sanctum`, `tenant.scope`, `tenant.access`, `tenant.admin`
- **Description** : Returns a list of active teams for the current tenant

### Get Team
- **URL** : `/api/v1/teams/{id}`
- **Method** : `GET`
- **Middleware** : `auth:sanctum`, `tenant.scope`, `tenant.access`, `tenant.admin`
- **Description** : Returns details of a specific team

### Create Team
- **URL** : `/api/v1/teams`
- **Method** : `POST`
- **Middleware** : `auth:sanctum`, `tenant.scope`, `tenant.access`, `tenant.admin`
- **Description** : Creates a new team
- **Request Body** :
  ```json
  {
    "name": "Team Name",
    "description": "Team Description",
    "is_active": true,
    "max_concurrent_bookings": 1
  }
  ```

### Update Team
- **URL** : `/api/v1/teams/{id}`
- **Method** : `PUT/PATCH`
- **Middleware** : `auth:sanctum`, `tenant.scope`, `tenant.access`, `tenant.admin`
- **Description** : Updates an existing team

### Delete Team
- **URL** : `/api/v1/teams/{id}`
- **Method** : `DELETE`
- **Middleware** : `auth:sanctum`, `tenant.scope`, `tenant.access`, `tenant.admin`
- **Description** : Deletes a team

## Team Members Management

### List Team Members
- **URL** : `/api/v1/teams/{team}/members`
- **Method** : `GET`
- **Middleware** : `auth:sanctum`, `tenant.scope`, `tenant.access`, `tenant.admin`
- **Description** : Returns a list of members for a specific team

### Add Team Member
- **URL** : `/api/v1/teams/{team}/members`
- **Method** : `POST`
- **Middleware** : `auth:sanctum`, `tenant.scope`, `tenant.access`, `tenant.admin`
- **Description** : Adds a user to a team
- **Request Body** :
  ```json
  {
    "user_id": 1
  }
  ```

### Remove Team Member
- **URL** : `/api/v1/teams/{team}/members`
- **Method** : `DELETE`
- **Middleware** : `auth:sanctum`, `tenant.scope`, `tenant.access`, `tenant.admin`
- **Description** : Removes a user from a team
- **Request Body** :
  ```json
  {
    "user_id": 1
  }
  ```

### Sync Team Members
- **URL** : `/api/v1/teams/{team}/members/sync`
- **Method** : `POST`
- **Middleware** : `auth:sanctum`, `tenant.scope`, `tenant.access`, `tenant.admin`
- **Description** : Syncs team members (replaces all members)
- **Request Body** :
  ```json
  {
    "user_ids": [1, 2, 3]
  }
  ```

## Team Availability Management

### Get Team Availability
- **URL** : `/api/v1/teams/{team}/availability`
- **Method** : `GET`
- **Middleware** : `auth:sanctum`, `tenant.scope`, `tenant.access`, `tenant.admin`
- **Description** : Returns all availability records for a team

### Get Active Team Availability
- **URL** : `/api/v1/teams/{team}/availability/active`
- **Method** : `GET`
- **Middleware** : `auth:sanctum`, `tenant.scope`, `tenant.access`, `tenant.admin`
- **Description** : Returns active availability records for a team

### Get Team Availability for Day
- **URL** : `/api/v1/teams/{team}/availability/day/{dayOfWeek}`
- **Method** : `GET`
- **Middleware** : `auth:sanctum`, `tenant.scope`, `tenant.access`, `tenant.admin`
- **Description** : Returns availability records for a specific day (0=Sunday, 6=Saturday)

### Sync Team Availability
- **URL** : `/api/v1/teams/{team}/availability/sync`
- **Method** : `POST`
- **Middleware** : `auth:sanctum`, `tenant.scope`, `tenant.access`, `tenant.admin`
- **Description** : Syncs team availability (replaces all availability records)
- **Request Body** :
  ```json
  {
    "availability": [
      {
        "day_of_week": 1,
        "start_time": "09:00",
        "end_time": "17:00",
        "is_active": true
      },
      {
        "day_of_week": 2,
        "start_time": "09:00",
        "end_time": "17:00",
        "is_active": true
      }
    ]
  }
  ```

### Create Availability Record
- **URL** : `/api/v1/team-availability`
- **Method** : `POST`
- **Middleware** : `auth:sanctum`, `tenant.scope`, `tenant.access`, `tenant.admin`
- **Description** : Creates a new availability record
- **Request Body** :
  ```json
  {
    "team_id": 1,
    "day_of_week": 1,
    "start_time": "09:00",
    "end_time": "17:00",
    "is_active": true
  }
  ```

### Update Availability Record
- **URL** : `/api/v1/team-availability/{id}`
- **Method** : `PATCH`
- **Middleware** : `auth:sanctum`, `tenant.scope`, `tenant.access`, `tenant.admin`
- **Description** : Updates an existing availability record

### Delete Availability Record
- **URL** : `/api/v1/team-availability/{id}`
- **Method** : `DELETE`
- **Middleware** : `auth:sanctum`, `tenant.scope`, `tenant.access`, `tenant.admin`
- **Description** : Deletes an availability record

## Booking Management

### Generate Slots
- **URL** : `/api/v1/teams/{id}/generate-slots`
- **Method** : `GET`
- **Middleware** : `auth:sanctum`, `tenant.scope`, `tenant.access`, `tenant.admin`
- **Description** : Generates available booking slots for a team
- **Query Parameters** :
  - `from` : Start date (YYYY-MM-DD)
  - `to` : End date (YYYY-MM-DD)
- **Response** : Returns a list of available slots

### List Bookings
- **URL** : `/api/v1/bookings`
- **Method** : `GET`
- **Middleware** : `auth:sanctum`, `tenant.scope`, `tenant.access`, `tenant.admin`
- **Description** : Returns a list of bookings for the current user

### Create Booking
- **URL** : `/api/v1/bookings`
- **Method** : `POST`
- **Middleware** : `auth:sanctum`, `tenant.scope`, `tenant.access`
- **Description** : Creates a new booking
- **Request Body** :
  ```json
  {
    "team_id": 1,
    "booking_date": "2023-07-01",
    "start_time": "09:00",
    "end_time": "10:00",
    "customer_name": "John Doe",
    "customer_email": "john@example.com",
    "customer_phone": "1234567890",
    "location": "123 Main St",
    "notes": "Additional notes"
  }
  ```

### Cancel Booking
- **URL** : `/api/v1/bookings/{id}`
- **Method** : `DELETE`
- **Middleware** : `auth:sanctum`, `tenant.scope`, `tenant.access`, `tenant.admin`
- **Description** : Cancels a booking