# Lumio ✨ — Personal Workspace Manager

> A clean, minimal, and intuitive workspace management system inspired by Notion — built with PHP & MySQL.

---

## 📋 Table of Contents

- [About the Project](#about-the-project)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Project Structure](#project-structure)
- [Database Schema](#database-schema)
- [Getting Started](#getting-started)
- [Usage](#usage)
- [Pages Overview](#pages-overview)
- [Contributing](#contributing)
- [License](#license)

---

## 📌 About the Project

**Lumio** is a full-stack web application that allows users to create and manage their personal workspace. Users can register, log in, and organize their content through a hierarchical page system — with support for nested sub-pages, a modern sidebar navigation, and a real-time dashboard overview.

This project was built as a training project for the **NTI (National Telecommunication Institute)** program.

---

## ✨ Features

- 🔐 **User Authentication** — Secure registration & login with hashed passwords
- 📊 **Dashboard** — Overview of total pages, root pages, and nested sub-pages
- 📝 **Page Management** — Create, Read, Update, and Delete workspace pages
- 🌿 **Nested Pages** — Infinite hierarchical sub-page support
- 👤 **User Profile** — View and manage your account details
- 📂 **File Manager** — Manage your workspace files
- ✅ **Tasks** — Keep track of your tasks
- 📅 **Calendar** — Visualize your schedule
- 📁 **Projects** — Organize work into projects
- 👥 **Team Members** — Collaborate with your team
- 📈 **Analytics** — Gain insights into your workspace activity
- 🔔 **Notifications** — Stay updated with alerts
- 📑 **Templates** — Reuse page templates
- ⚙️ **Settings** — Customize your preferences
- 🗑 **Trash** — Recover deleted pages

---

## 🛠 Tech Stack

| Layer       | Technology          |
|-------------|---------------------|
| Backend     | PHP 8+              |
| Database    | MySQL (via PDO)     |
| Frontend    | HTML5, CSS3, JS     |
| Server      | Apache (XAMPP)      |
| Auth        | PHP Sessions        |
| Security    | password_hash(), htmlspecialchars(), Prepared Statements |

---

## 📁 Project Structure

```
project_nti/
├── index.php               # Entry point — redirects to login or dashboard
├── css/
│   └── style.css           # Global stylesheet
├── js/
│   └── script.js           # Frontend scripts
├── sql/
│   └── lumio.sql           # Database schema
└── pages/
    ├── config.php          # Database connection & session start
    ├── auth_check.php      # Authentication guard
    ├── sidebar.php         # Shared sidebar component
    ├── login.php           # Login page
    ├── register.php        # Registration page
    ├── logout.php          # Session destroy & redirect
    ├── dashboard.php       # Main dashboard with stats
    ├── page.php            # View a single workspace page
    ├── create_page.php     # Create a new page
    ├── update_page.php     # Edit an existing page
    ├── delete_page.php     # Delete a page
    ├── profile.php         # User profile page
    ├── file_manager.php    # File management
    ├── tasks.php           # Task management
    ├── calendar.php        # Calendar view
    ├── projects.php        # Projects overview
    ├── team.php            # Team members
    ├── analytics.php       # Analytics dashboard
    ├── notifications.php   # Notifications center
    ├── templates.php       # Page templates
    ├── settings.php        # User settings
    └── trash.php           # Trash / deleted pages
```

---

## 🗄 Database Schema

The application uses **two main tables**:

### `users`
| Column       | Type           | Description               |
|--------------|----------------|---------------------------|
| `id`         | INT (PK, AI)   | Unique user ID            |
| `username`   | VARCHAR(50)    | Unique username           |
| `email`      | VARCHAR(100)   | Unique email address      |
| `password`   | VARCHAR(255)   | Bcrypt hashed password    |
| `created_at` | TIMESTAMP      | Account creation date     |

### `pages`
| Column       | Type           | Description                        |
|--------------|----------------|------------------------------------|
| `id`         | INT (PK, AI)   | Unique page ID                     |
| `user_id`    | INT (FK)       | References `users.id`              |
| `parent_id`  | INT (FK, NULL) | References `pages.id` (nested)     |
| `title`      | VARCHAR(150)   | Page title                         |
| `icon`       | VARCHAR(10)    | Emoji icon for the page            |
| `content`    | LONGTEXT       | Full page content                  |
| `created_at` | TIMESTAMP      | Page creation date                 |
| `updated_at` | TIMESTAMP      | Last modification date             |

> Pages support **infinite nesting** via the `parent_id` self-referencing foreign key.

---

## 🚀 Getting Started

### Prerequisites

- [XAMPP](https://www.apachefriends.org/) (or any Apache + PHP + MySQL stack)
- PHP >= 8.0
- MySQL >= 5.7

### Installation

1. **Clone the repository** into your XAMPP `htdocs` folder:
   ```bash
   git clone https://github.com/YOUR_USERNAME/project_nti.git
   ```

2. **Start XAMPP** — make sure Apache and MySQL services are running.

3. **Create the database:**
   - Open [phpMyAdmin](http://localhost/phpmyadmin)
   - Create a new database named `lumio_db`
   - Import the SQL file: `sql/lumio.sql`

4. **Configure the database connection** in `pages/config.php`:
   ```php
   $db_host = "localhost";
   $db_name = "lumio_db";
   $db_user = "root";
   $db_pass = "";   // change if you have a MySQL password
   ```

5. **Open the app** in your browser:
   ```
   http://localhost/project_nti/
   ```

---

## 📖 Usage

1. Navigate to `http://localhost/project_nti/`
2. **Register** a new account
3. **Log in** with your credentials
4. Start creating pages from the sidebar using **"+ New Page"**
5. Pages can be nested inside each other for organized, hierarchical notes
6. Use the sidebar to navigate between Dashboard, Profile, Tasks, and more

---

## 📄 Pages Overview

| Page              | Route                      | Description                          |
|-------------------|----------------------------|--------------------------------------|
| Home (redirect)   | `/index.php`               | Redirects to login or dashboard      |
| Login             | `/pages/login.php`         | User login form                      |
| Register          | `/pages/register.php`      | New user registration                |
| Dashboard         | `/pages/dashboard.php`     | Stats & recent pages overview        |
| View Page         | `/pages/page.php?id=X`     | View a specific workspace page       |
| Create Page       | `/pages/create_page.php`   | Create a new page (via POST)         |
| Update Page       | `/pages/update_page.php`   | Edit an existing page                |
| Delete Page       | `/pages/delete_page.php`   | Delete a page (via POST)             |
| Profile           | `/pages/profile.php`       | User account details                 |
| File Manager      | `/pages/file_manager.php`  | Manage workspace files               |
| Tasks             | `/pages/tasks.php`         | Task list & management               |
| Calendar          | `/pages/calendar.php`      | Calendar view                        |
| Projects          | `/pages/projects.php`      | Projects overview                    |
| Team              | `/pages/team.php`          | Team members                         |
| Analytics         | `/pages/analytics.php`     | Activity analytics                   |
| Notifications     | `/pages/notifications.php` | Notification center                  |
| Templates         | `/pages/templates.php`     | Reusable page templates              |
| Settings          | `/pages/settings.php`      | User preferences                     |
| Trash             | `/pages/trash.php`         | Deleted pages recovery               |
| Logout            | `/pages/logout.php`        | Destroy session & redirect           |

---

## 🤝 Contributing

Contributions, issues, and feature requests are welcome!

1. Fork the project
2. Create your feature branch: `git checkout -b feature/AmazingFeature`
3. Commit your changes: `git commit -m 'Add some AmazingFeature'`
4. Push to the branch: `git push origin feature/AmazingFeature`
5. Open a Pull Request

---

## 📝 License

This project is open source and available under the [MIT License](LICENSE).

---

<div align="center">
  Made with ❤️ as an NTI Training Project
</div>
