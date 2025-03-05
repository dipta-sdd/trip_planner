# Trip Planner Application

## Overview
The Trip Planner Application is designed to help users efficiently plan, organize, and track their travel itineraries. It allows users to create trips, manage schedules, invite companions, and keep track of essential travel details such as accommodation, transportation, and expenses.

The system supports three user roles: **System Admin**, **Regular Users**, and **Trip Participants**. It aims to provide a seamless, user-friendly experience for trip planning while enabling collaboration among friends and family.

## Core Features

- **User registration and login**: Users can create an account and log in to manage trips.
- **Trip creation, update, and deletion**: Users can create, modify, and delete trips.
- **Itinerary management**: Users can manage destinations, travel dates, and activities.
- **Collaborative trips**: Users can invite others to collaborate on a trip.
- **Shared itinerary access**: All trip participants can view and modify (admins) the itinerary.
- **Transportation management**: Tracks flights, trains, car rentals, etc.
- **Accommodation tracking**: Manage hotels, Airbnb, hostels, etc.
- **Budget tracking**: Track and manage trip expenses.

## User Roles & Permissions

### 1. System Admin:
- Manage all users and their activities.

### 2. Regular Users:
- Register and log in using email and password.
- Create and manage personal trips.
- Add destinations, activities, transportation, and accommodations.
- Invite others to collaborate on a trip.
- Set trip budgets and track expenses.
- View the itinerary in a calendar or list format.

### 3. Trip Participants:
- Accept or decline trip invitations.
- View and edit the trip itinerary (if permitted by the trip owner).
<!-- - Add personal notes and track expenses for the trip. -->

---

## Getting Started

### 1. Server (Laravel Backend)

#### a. Clone the repository

```bash
git clone <repository_url>
cd <project_directory>/server
```

#### b. Install PHP dependencies

Run the following command to install all PHP dependencies using Composer:

```bash
composer install
```

#### c. Database Setup

1. Configure the `.env` file:

   Rename the `.env.example` file to `.env`:

   ```bash
   cp .env.example .env
   ```

   Open the `.env` file and update the following database connection details:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=<your_database_name>
   DB_USERNAME=<your_database_username>
   DB_PASSWORD=<your_database_password>
   ```

2. Import the database:

   Ensure you have MySQL or another relational database set up and running. 

   - Find the `database.sql` file in the `server` directory.
   - Import this SQL backup into your database:

   ```bash
   mysql -u <username> -p <database_name> < path_to_database.sql
   ```


##### Or
 3. Run Migrations

    If necessary, run the migrations to set up additional database tables:

    ```bash
    php artisan migrate
    ```

#### e. Serve the Laravel Application

You can start the Laravel server using the built-in PHP server:

```bash
php artisan serve
```

The Laravel API will be available at `http://localhost:8000`.

---

### 2. Client (Nuxt.js Frontend)

#### a. Install dependencies

Navigate to the `client` directory and run:

```bash
cd client
npm install
```

#### b. Run the application

To run the Nuxt.js application locally, simply run:

```bash
npm run dev
```

The Nuxt.js app will be available at `http://localhost:3000`.

---
