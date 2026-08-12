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
│   ├── base.css            # Shared styles (reset, body, sidebar, nav, badges)
│   ├── analytic.css        # analytics.php styles
│   ├── auth.css            # login.php & register.php styles
│   ├── calendar.css        # calendar.php styles
│   ├── dashboard.css       # dashboard.php styles (stats, cards)
│   ├── file_manager.css    # file_manager.php styles
│   ├── page.css            # page.php styles (editor, toolbar, actions)
│   ├── profile.css         # profile.php styles (form, inputs)
│   ├── task.css            # tasks.php & projects.php styles
│   ├── team.css            # team.php styles
│   ├── templates.css       # templates.php styles
│   ├── trash.css           # trash.php styles
│   └── style.css           # Legacy stylesheet (kept for reference)
├── js/
│   └── script.js           # Frontend scripts
├── sql/
│   └── lumio.sql           # Database schema
└── pages/
    ├── config.php          # Database connection & session start
    ├── auth_check.php      # Authentication guard
    ├── sidebar.php         # Shared sidebar component
    ├── login.php           # Login page           → base.css + auth.css
    ├── register.php        # Registration page    → base.css + auth.css
    ├── logout.php          # Session destroy & redirect
    ├── dashboard.php       # Main dashboard       → base.css + dashboard.css
    ├── page.php            # View/edit workspace page → base.css + page.css
    ├── create_page.php     # Create a new page (via POST)
    ├── update_page.php     # Edit an existing page (via POST)
    ├── delete_page.php     # Delete a page (via POST)
    ├── profile.php         # User profile         → base.css + profile.css
    ├── file_manager.php    # File management      → base.css + file_manager.css
    ├── tasks.php           # Task management      → base.css + task.css
    ├── calendar.php        # Calendar view        → base.css + calendar.css
    ├── projects.php        # Projects overview    → base.css + task.css
    ├── team.php            # Team members         → base.css + team.css
    ├── analytics.php       # Analytics dashboard  → base.css + analytic.css
    ├── notifications.php   # Notifications center → base.css + task.css
    ├── templates.php       # Page templates       → base.css + templates.css
    ├── settings.php        # User settings        → base.css + task.css
    └── trash.php           # Trash / deleted pages → base.css + trash.css
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
