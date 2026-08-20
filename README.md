# Hontech Auto Center Inc. — Web Application & RBAC System
**Technology Stack:** PHP 8+ | MySQL / MariaDB | HTML5 | Vanilla CSS3 | Vanilla JavaScript (Tailored for XAMPP)

---

## 🚀 Quickstart Guide for XAMPP

### 1. Project Location
Place this folder into your XAMPP `htdocs` directory:
```
C:\xampp\htdocs\Hontech_LandingPage
```
*(Or keep it in your development folder and create a symbolic link / virtual host).*

### 2. Start Services
1. Open the **XAMPP Control Panel**.
2. Click **Start** for **Apache** and **MySQL**.

### 3. Import Database (`hontech_db.sql`)
1. Open your browser and navigate to **phpMyAdmin**: [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
2. Click on the **Import** tab.
3. Choose the file: `database/hontech_db.sql`.
4. Click **Import / Go**. The database `hontech_db` with all tables, categories, sample articles, and demo staff accounts will be created automatically.

---

## 🔑 Default Staff & Employee Login Credentials

Access the employee portal at: **`http://localhost/Hontech_LandingPage/admin/login.php`**

| Role | Email | Password | Access Level |
| :--- | :--- | :--- | :--- |
| **Super Administrator** | `admin@hontech.com` | `password123` | Full system control, staff roster, audit logs |
| **Service Manager** | `manager@hontech.com` | `password123` | Dispatch appointments, job orders, inquiries |
| **Senior Technician** | `technician@hontech.com` | `password123` | View assigned job orders, update service status |
| **Marketing Editor** | `marketing@hontech.com` | `password123` | Create, edit, and publish blog articles & tips |

*(Note: On the login page, you can also click the **"Quick Demo Logins"** buttons to automatically fill in test credentials!)*

---

## 🌐 Public Website Features
- **Landing Page (`index.php`)**: Full company profile, 16+ expert staff, 1,200 sqm workshop details, 6 C's philosophy, milestones, and services.
- **Interactive Cost Estimator (`includes/estimator.php`)**: Real-time service pricing calculation based on vehicle type with an instant booking modal.
- **Blog & Car Care Tips (`blog.php` & `blog-post.php`)**: Searchable, category-filtered articles with article reading view.
- **Contact & Inquiry System (`api/submit_contact.php`)**: AJAX-powered inquiry submission with toast notifications.

---

## 📁 System Architecture & Directory Structure
```
Hontech_LandingPage/
├── config/
│   └── db.php                  # PDO database connection with XAMPP defaults & fallback
├── database/
│   └── hontech_db.sql          # Complete schema (roles, employees, blogs, bookings, inquiries)
├── includes/
│   ├── header.php              # Global head, meta tags, and stylesheets
│   ├── navbar.php              # Responsive navigation with Staff Portal button
│   ├── hero.php                # Hero banner, interactive canvas, and statistics
│   ├── about.php               # About company & quality standards
│   ├── vision_mission.php      # Vision, mission, and 6 C's
│   ├── services.php            # Service breakdown
│   ├── estimator.php           # Service Cost Calculator & booking modal
│   ├── blog_preview.php        # Latest blog articles query on homepage
│   ├── milestones.php          # Milestones & company growth timeline
│   ├── team.php                # Specialist team departments
│   ├── faq.php                 # Accordion FAQs
│   ├── contact.php             # Contact form & location information
│   └── footer.php              # Global footer
├── api/
│   ├── submit_contact.php      # Saves customer inquiries to MySQL
│   ├── submit_booking.php      # Saves service appointments to MySQL
│   ├── auth.php                # Staff authentication, password_verify & RBAC sessions
│   └── blog_actions.php        # Create, edit, delete blog articles
├── admin/
│   ├── login.php               # Unified staff login screen
│   ├── logout.php              # Session destruction & sign out
│   ├── dashboard.php           # Role-based dashboard (Admin, Manager, Tech, Marketing)
│   ├── bookings.php            # Appointment manager & bay dispatcher
│   ├── inquiries.php           # Customer inquiries manager
│   ├── posts.php               # Blog posts listing & actions
│   ├── post-editor.php         # Blog article writer with image upload
│   └── employees.php           # Staff roster & role view
├── uploads/
│   └── blogs/                  # Uploaded featured images
├── assets/
│   ├── js/
│   │   ├── main.js             # Canvas particles, counters, reveal animations
│   │   ├── estimator.js        # Dynamic calculation & modal logic
│   │   └── contact.js          # AJAX submission & toast notifications
│   └── images/                 # Theme graphics & photos
├── index.php                   # Master homepage entry point
├── blog.php                    # Public blog archive
└── blog-post.php               # Single article reader
```
