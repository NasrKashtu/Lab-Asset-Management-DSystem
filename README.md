# Lab Asset Management System

A web-based system for tracking university lab equipment — projectors, computers, and peripherals. Manages inventory, borrowing records, and generates reports for lab administrators.

## Features

- Equipment inventory with category management
- Borrowing and return records with timestamps
- Admin dashboard with usage reports
- Role-based access: Admin / Lab Staff

## Tech Stack

![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat&logo=mysql&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=flat&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=flat&logo=css3&logoColor=white)

## Getting Started

### Requirements

- PHP 7.4+
- MySQL 5.7+
- A local server (XAMPP / WAMP / Laragon)

### Setup

```bash
git clone https://github.com/NasrKashtu/Lab-Asset-Management-DSystem.git
```

1. Import `database/schema.sql` into your MySQL instance.
2. Copy `config/db.example.php` to `config/db.php` and fill in your database credentials.
3. Place the project folder in your web server root (`htdocs` / `www`).
4. Open `http://localhost/Lab-Asset-Management-DSystem` in your browser.

## Author

**Nasr Kashtu** — [nasrkashtu@gmail.com](mailto:nasrkashtu@gmail.com)
