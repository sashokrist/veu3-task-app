<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Vue 3 Task App

A simple task manager built with Laravel (API & backend) and Vue 3 (frontend, SPA via Inertia.js).

---

## Clone the Repository

**SSH:**
```sh
git clone git@github.com:sashokrist/veu3-task-app.git
```

**HTTPS:**
```sh
git clone https://github.com/sashokrist/veu3-task-app.git
```

---

## Installation & Running

1. **Install PHP dependencies:**
   ```sh
   composer install
   ```

2. **Install JS dependencies:**
   ```sh
   npm install
   ```

3. **Copy `.env` and set up your database:**
   ```sh
   cp .env.example .env
   # Edit .env and set DB_... variables
   ```

4. **Generate app key:**
   ```sh
   php artisan key:generate
   ```

5. **Run migrations:**
   ```sh
   php artisan migrate
   ```

6. **(Optional) Seed database:**
   ```sh
   php artisan db:seed
   ```

7. **Start the dev servers:**
   - **Backend (Laravel):**
     ```sh
     php artisan serve
     ```
   - **Frontend (Vite):**
     ```sh
     npm run dev
     ```

8. **Visit the app:**
   - Open [http://127.0.0.1:8000](http://127.0.0.1:8000) in your browser.

---

## Functionality

- **Authentication:** Register, login, logout (Laravel Breeze/Jetstream).
- **Dashboard:** Welcome page after login.
- **Tasks Page:**
  - List all tasks (paginated, 5 per page).
  - Filter tasks by title and completion status.
  - Add new tasks (only for logged-in users).
  - Edit and delete tasks (only for logged-in users).
  - Mark tasks as completed/incomplete.
  - Select multiple tasks and bulk delete.
- **Navigation:** Top navigation bar with links to Dashboard, Tasks, Profile, and Logout.
- **Validation & Error Handling:** User-friendly error messages for all actions.
- **API:** All task actions use `/api/tasks` endpoints.

---

## Routes

### Web Routes

| Method | URI         | Name      | Description                |
|--------|-------------|-----------|----------------------------|
| GET    | /           | welcome   | Welcome page               |
| GET    | /dashboard  | dashboard | User dashboard (auth)      |
| GET    | /tasks      | tasks     | Task manager page (auth)   |
| ...    | ...         | ...       | Auth routes (login, etc.)  |

### API Routes

| Method | URI           | Description           |
|--------|---------------|----------------------|
| GET    | /api/tasks    | List all tasks       |
| POST   | /api/tasks    | Create a new task    |
| PUT    | /api/tasks/{id}| Update a task       |
| DELETE | /api/tasks/{id}| Delete a task       |

---

## Author

**sasho_dev**

-