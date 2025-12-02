# 🌐 CRM Ticket Management System

A lightweight CRM Ticketing System built with **PHP + MySQL**, featuring:

- Secure authentication  
- Ticket creation & assignment  
- Role-based access control  
- File uploads  
- Fully documented architecture  

---

# 🏗️ Project Overview

This CRM Ticket System helps manage support tickets with clear:

✔ Author & Assignee roles  
✔ Secure login & registration  
✔ File attachments  
✔ Strict access control  
✔ Simple and responsive UI  

**Tech Stack Used**

- PHP 8+
- MySQL / MariaDB
- HTML5 + CSS3
- WAMP / XAMPP / Shared Hosting
- PDO (Protection against SQL Injection)

---

# 📁 Folder Structure

```
crm-ticket/
│
├── README.md
├── CRM-TICKET-PROJECT.pdf
│
├── index.php
├── auth_login.php
├── auth_register.php
├── logout.php
│
├── admin_tickets.php
├── admin_users.php
│
├── tickets_list.php
├── tickets_view.php
├── tickets_create.php
├── tickets_edit.php
├── tickets_delete.php
├── tickets_assign.php
│
├── .htaccess
├── .env
│
├── assets/
│   └── styles.css
│
├── layout/
│   ├── header.php
│   └── footer.php
│
├── storage/
│   └── uploads/
│
├── sql/
│   └── migrations.sql
│
└── src/
    ├── db.php
    └── helpers/
        └── functions.php
```

---

# 🖥️ Installation Manual (WAMP / XAMPP / Hosting)

## ✅ Step 1 — Install WAMP or XAMPP

**WAMP**  
Download → https://www.wampserver.com/en/  
Start **Apache + MySQL**

**XAMPP**  
Download → https://www.apachefriends.org/  
Start **Apache + MySQL**

---

## ✅ Step 2 — Place Project Files

Copy entire folder into:

**WAMP**
```
C:/wamp64/www/
```

**XAMPP**
```
C:/xampp/htdocs/
```

**Shared Hosting**
```
public_html/
```

---

## ✅ Step 3 — Create Database

Open:
```
http://localhost/phpmyadmin
```

Create database:
```
crm_ticket
```

Import:
```
sql/migrations.sql
```

---

## ✅ Step 4 — Configure Database in PHP

Edit:
```
src/db.php
```

```php
$host = "localhost";
$dbname = "crm_ticket";
$username = "root";
$password = "";  // default for WAMP/XAMPP
```

---

## ✅ Step 5 — Run the Application

Open in browser:

```
http://localhost/crm-ticket/
```

---

# 🗄️ Database Layout

## 1️⃣ users Table

| Column      | Type            | Description        |
|-------------|------------------|--------------------|
| id          | INT PK AI        | User ID            |
| name        | VARCHAR(150)     | Full name          |
| email       | VARCHAR(150) UNIQUE | Login email    |
| password    | VARCHAR(255)     | Hashed password    |
| created_at  | TIMESTAMP        | Created date       |

---

## 2️⃣ tickets Table

| Column       | Type                                      | Description                 |
|--------------|--------------------------------------------|-----------------------------|
| id           | INT PK AI                                  | Ticket ID                   |
| name         | VARCHAR(255)                               | Ticket title                |
| description  | TEXT                                       | Details                     |
| status       | ENUM('pending','inprogress','completed','onhold') | Ticket status       |
| file         | VARCHAR(255)                               | Attachment path             |
| created_by   | INT FK → users.id                          | Author                      |
| assigned_to  | INT FK → users.id                          | Assignee                    |
| created_at   | TIMESTAMP                                  | Created time                |
| updated_at   | TIMESTAMP                                  | Updated time                |
| completed_at | TIMESTAMP                                  | Completion time             |
| deleted_at   | TIMESTAMP NULL                             | Soft delete                 |

---

## 3️⃣ assignments Table

| Column      | Type      | Description      |
|-------------|-----------|------------------|
| id          | INT PK AI | Assignment ID    |
| ticket_id   | INT FK    | Related ticket   |
| assigned_to | INT FK    | Assigned user    |
| assigned_at | TIMESTAMP | Assignment time  |

---

# 🔗 Entity Relationship Diagram (ERD)

```
Users (1) -------- (M) Tickets
Users (1) -------- (M) Assignments
Tickets (1) ----- (M) Assignments
```

**Meaning:**

- A user can create many tickets  
- A user can be assigned many tickets  
- A ticket can have multiple assignment records  

---

# 🔐 Security Rules

## Authentication
✔ Only logged-in users can access dashboard & tickets  

## Authorization
✔ Authors can edit/delete only their own tickets  
✔ Assignees can update only ticket status  
✔ Admins can view everything  
✔ Guests can access only:  
- Login  
- Register  

## Security Features
- Password hashing (BCRYPT)
- PDO prepared queries (SQL injection safe)
- Validation & sanitization
- File upload validation
- Access control on all pages

---

# 🎫 Ticket Workflow

1. User creates a ticket  
2. Admin assigns it to a user  
3. Assigned user updates status:  
   - pending  
   - inprogress  
   - onhold  
   - completed  
4. Author/Admin can edit full details  
5. Ticket stored with timestamps

---

# 🕒 Task Breakdown (16 hrs – 5 days)

| Task | Time |
|------|-------|
| File structure setup | 5 min |
| Database layout | 30 min |
| Migrations (DDL) | 1 hr |
| Configuration | 20 min |
| Frontend forms | 2 hrs |
| Listing pages | 1 hr |
| Navigation bar | 30 min |
| Validation | 1 hr |
| CRUD operations | 3 hrs |
| Integration | 3.5 hrs |
| Access control security | 3 hrs |

---

# ✔️ Deliverables

- Full Source Code  
- GitHub Repository  
- Live Website as https://crud-ticket.42web.io/  
- Documentation (PDF)  
- README.md  



