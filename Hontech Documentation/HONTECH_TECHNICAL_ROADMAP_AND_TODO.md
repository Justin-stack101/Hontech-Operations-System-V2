# Master TODO & Technical Roadmap: Hontech Auto Center
**System Stack:** Vanilla HTML5 / CSS3 / JavaScript + PHP 8+ Backend + MySQL Database (XAMPP Environment)

---

## 🎯 1. Proper Agent Skillset & Engineering Standards

- [x] **Git Repository Isolation**: Keep client-facing repository (`HontechPrototype_Homepage`) on pristine `main` branch; all architectural refactors housed in dedicated repository (`Hontech-Operations-System-V2`).
- [ ] **Secure Database Patterns (PDO)**:
  - Strict prepared statements (`PDO::prepare()`) for all `INSERT`, `UPDATE`, `DELETE`, and parameterized `SELECT` queries to prevent SQL Injection.
  - Exception mode enabled (`PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION`).
  - Strict UTF-8 collation (`utf8mb4_unicode_ci`).
- [ ] **Vanilla Code Principles (Zero External Build Tooling)**:
  - Standard HTML5 semantic tags (`<header>`, `<nav>`, `<main>`, `<section>`, `<article>`, `<footer>`).
  - Pure CSS3 custom properties / design tokens (`--color-primary`, `--radius-md`, `--shadow-lg`).
  - Vanilla ES6+ JavaScript (`fetch()`, `IntersectionObserver`, `async/await`, `addEventListener`).
- [ ] **XAMPP Compatibility Standard**:
  - Code runs out-of-the-box in `C:\xampp\htdocs\` with zero Node.js/Webpack build steps required.
  - Dynamic path resolution using `__DIR__` and relative base URLs.

---

## ⚙️ 2. Refactoring System Design (Vanilla + XAMPP + PHP + MySQL)

### Backend Architecture
- [x] **Centralized Database Connection**: `config/db.php` with connection pooling and graceful demo fallback.
- [x] **Modular Template System**: `includes/` broken into distinct reusable partials (`header.php`, `navbar.php`, `hero.php`, `estimator.php`, `blog_preview.php`, `contact.php`, `footer.php`).
- [x] **REST/AJAX API Handlers**:
  - `api/submit_contact.php`: Validates and records inquiries.
  - `api/submit_booking.php`: Processes appointment reservations with generated reference numbers.
  - `api/auth.php`: Secure bcrypt password verification and session initialization.
  - `api/blog_actions.php`: CRUD operations with image file upload validation.

### Database Architecture (`database/hontech_db.sql`)
- [x] `roles` & `departments` lookup tables.
- [x] `employees` table with bcrypt password hashing (`password_hash`).
- [x] `categories` & `posts` tables with URL slug indexing.
- [x] `inquiries` table with status workflow (`pending` ➔ `contacted` ➔ `resolved`).
- [x] `bookings` table with estimated pricing and date scheduling.
- [x] `job_orders` table linking technicians to repair tickets.
- [x] `audit_logs` table for administrative security tracing.

---

## 🚀 3. Future Plans & Advanced System Modules

### Phase 2: Operations & Workflow Automation
- [ ] **Customer Live Repair Tracker**:
  - A public lookup page where customers enter their **Plate Number** or **Booking Reference** to see live repair stages (*"Inspecting" ➔ "Parts Awaiting" ➔ "Under Repair" ➔ "Ready for Pickup"*).
- [ ] **Automated SMS & Email Notifications**:
  - Instant SMS / Email confirmation when appointment is approved.
  - Notification when vehicle PMS is 100% complete and ready for release.
- [ ] **Printable Job Orders & Invoices (PDF)**:
  - Generate official Hontech branded PDF estimates, service checklists, and billing invoices.

### Phase 3: Inventory & Shop Management
- [ ] **Spare Parts & Consumables Inventory**:
  - Track stock levels of engine oils, filters, brake pads, and fluids.
  - Auto-deduct stock upon technician marking a job order complete.
  - Low-stock alerts for shop managers.
- [ ] **Technician Mobile View (Tablet/Phone PWA)**:
  - Touch-friendly interface for mechanics inside service bays to tap checklist items and upload vehicle inspection photos.

---

## 🔍 4. Critical Items We Must Not Miss (Security & Operations)

### Security & Hardening
- [ ] **CSRF Protection**: Implement hidden `csrf_token` input on all forms validated against `$_SESSION['csrf_token']`.
- [ ] **Login Rate Limiting**: Limit failed login attempts (e.g. max 5 attempts per 15 minutes per IP address) to prevent brute-force attacks.
- [ ] **XSS Sanitization**: Enforce `htmlspecialchars($data, ENT_QUOTES, 'UTF-8')` across all dynamic outputs.
- [ ] **File Upload Validation**: Restrict uploads strictly to valid MIME types (`image/jpeg`, `image/png`, `image/webp`) with file size limits (max 5MB).

### Deployment & Maintenance
- [ ] **Automated Database Backups**: A lightweight PHP / Batch script that dumps `hontech_db` weekly into a secure archive.
- [ ] **Local SEO & Schema Markup**: Add Schema.org `AutoRepair` JSON-LD structured data on `index.php` for Google Maps search indexing in Metro Manila.
- [ ] **Apache `.htaccess` Rules**: Clean URL rewrites (e.g., `/blog/5-signs-brakes-fail` instead of `/blog-post.php?slug=...`) and caching headers.
