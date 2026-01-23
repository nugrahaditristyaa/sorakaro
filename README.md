# Sorakaro

**Sorakaro** is a comprehensive web application designed for learning the Karo language (Cakap Karo). Inspired by platforms like Duolingo, it offers an interactive learning experience through levels, lessons, quizzes, and a guidebook. The project serves as both a practical language learning tool and a robust academic project demonstrating modern web development practices with Laravel 12.

## 🚀 Features

### User Learning Experience
- **Structured Learning Path**: Progression through Levels and Lessons (e.g., Level 1 → Basics → Greetings).
- **Interactive Quizzes**: Multiple-choice questions (MCQ) to test knowledge.
- **Immediate Feedback**: Real-time scoring and result summaries (Passed/Failed).
- **Guidebook**: Dedicated resources for learning grammar and vocabulary before taking quizzes.
- **Dashboard Analytics**:
  - **KPI Cards**: Total attempts, Average Score, Pass Rate.
  - **Leaderboard**: Weekly top 3 rankings to encourage competition.
  - **Category Performance**: Visual breakdown of strengths per topic.
  - **Attempt History**: Detailed log of past quizzes.

### Administration & Management
- **Admin Panel**: Built with **Filament v3** for managing content.
- **Content Management**: CRUD operations for Levels, Lessons, Questions, and Choices.
- **User Management**: Manage registered users and their roles.
- **Role-Based Access Control (RBAC)**: Secure access using Spatie Permission (Admin vs. User).

## 🛠 Tech Stack

- **Framework**: [Laravel 12](https://laravel.com)
- **Frontend**: [Blade](https://laravel.com/docs/blade) + [Tailwind CSS](https://tailwindcss.com) + [Flowbite](https://flowbite.com)
- **Auth**: Laravel Breeze
- **Admin Panel**: [FilamentPHP v3](https://filamentphp.com)
- **Database**: MySQL 8.0+
- **Roles/Permissions**: `spatie/laravel-permission`
- **Asset Bundling**: Vite (Node.js)

## 📋 Requirements

Ensure your environment meets the following specifications:

- **PHP**: >= 8.2
- **Composer**: Latest version
- **Node.js**: >= 18.x (Required for Vite)
- **MySQL**: >= 5.7 or 8.0
- **Git**

## 💿 Installation Steps

Follow these steps to set up the project locally:

1.  **Clone the Repository**
    ```bash
    git clone https://github.com/your-username/sorakaro.git
    cd sorakaro
    ```

2.  **Install Backend Dependencies**
    ```bash
    composer install
    ```

3.  **Install Frontend Dependencies**
    ```bash
    npm install
    ```

4.  **Environment Configuration**
    Copy the example environment file and configure it:
    ```bash
    cp .env.example .env
    ```
    Open `.env` and set your database credentials:
    ```ini
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=sorakaro
    DB_USERNAME=root
    DB_PASSWORD=
    ```

5.  **Generate Application Key**
    ```bash
    php artisan key:generate
    ```

6.  **Run Migrations & Seeders**
    Create the necessary tables and default data:
    ```bash
    php artisan migrate --seed
    ```
    *Note: This creates a default user `test@example.com`.*

    **Important**: To set up roles, run the specific RoleSeeder if it wasn't run automatically:
    ```bash
    php artisan db:seed --class=RoleSeeder
    ```

7.  **Create an Admin User**
    To access the Filament Admin Panel, creating a dedicated Filament user is recommended:
    ```bash
    php artisan make:filament-user
    ```
    *Follow the prompts to enter a name, email, and password.*

8.  **Run the Application**
    Start the local development server:
    ```bash
    # Terminal 1: Start Laravel Server
    php artisan serve

    # Terminal 2: Start Vite (Frontend Assets)
    npm run dev
    ```

9.  **Access the App**
    - **User Dashboard**: [http://localhost:8000](http://localhost:8000)
    - **Admin Panel**: [http://localhost:8000/admin](http://localhost:8000/admin)

## ⚙️ Environment Configuration

Key `.env` variables to check:

- `APP_URL`: Set to `http://localhost:8000` for proper asset linking.
- `DB_DATABASE`: Ensure this matches your local MySQL database name.
- `FILESYSTEM_DISK`: Default is `local`. For production, consider `public` or `s3`.

## 🧑‍💻 Development Notes

- **Vite Hot Reload**: Ensure `npm run dev` is running while developing to see CSS/JS changes instantly.
- **Cache Clearing**: If you encounter configuration issues, run:
    ```bash
    php artisan optimize:clear
    ```
- **Filament Assets**: If the admin panel styling looks off, publish assets:
    ```bash
    php artisan filament:assets
    ```

## 📂 Folder Structure Overview

- **`app/Http/Controllers`**: Contains User-facing logic (`LearnController`, `DashboardController`, `LeaderboardController`).
- **`app/Filament/Resources`**: Contains Admin Panel logic (Resources for Levels, Lessons, etc.).
- **`resources/views`**: Blade templates for the frontend.
  - `layouts/`: Master layouts (App, Guests).
  - `learn/`: Quiz and Lesson pages.
  - `dashboard.blade.php`: Main user dashboard.
- **`routes/web.php`**: User-facing web routes.
- **`database/migrations`**: Database schema definitions.

## 🤝 Contribution Guide

1.  **Fork** the repository.
2.  Create a new **Branch** for your feature (`git checkout -b feature/AmazingFeature`).
3.  **Commit** your changes (`git commit -m 'Add some AmazingFeature'`).
4.  **Push** to the branch (`git push origin feature/AmazingFeature`).
5.  Open a **Pull Request**.

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

**Author**: Sorakaro Team
