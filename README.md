# Library Tracker System

A full-stack library management system built with PHP and MySQL. Admins can manage a book collection with secure login, borrowing/due-date tracking, and CSV export, while a public browsing page lets anyone search the catalog without needing an account.

## Features

- **Secure admin login** — passwords hashed, protected against SQL injection via prepared statements
- **Book management (CRUD)** — add, edit, delete books with server-side validation
- **Borrowing tracker** — mark books as borrowed, set a due date, automatic overdue detection
- **Dashboard** — live stats (total, available, borrowed, overdue) plus a doughnut chart showing status breakdown
- **CSV export** — download the full book collection as a spreadsheet
- **Public browsing page** — search and filter books by title, author, genre, or status, no login required
- **Toast notifications** — success messages after adding, editing, or deleting a book

## Built with

- PHP 8
- MySQL / MariaDB
- HTML, CSS, JavaScript
- Chart.js (dashboard chart)

## Setup

1. Install [XAMPP](https://www.apachefriends.org/) (or similar) and start Apache + MySQL
2. Copy this project folder into `htdocs`
3. Open `http://localhost/phpmyadmin`, create a database named `library_db`
4. Import `library_db.sql` into that database — this also creates a default admin account (see the SQL file for credentials)
5. Configure `db.php` with your local MySQL username/password if different from the defaults
6. Visit `http://localhost/library_track_system/index.php` and log in as admin

## Notes

This is a student project built to practice full-stack development, secure coding practices (prepared statements, password hashing), and PHP/MySQL fundamentals. The booking/borrowing system is fully functional but intended for demonstration — there is no email or SMS integration for real-world reminders.