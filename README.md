# ECE5303 — Project #5: Chained Vulnerabilities (Bonus)
**Arab Academy for Science, Technology and Maritime Transport (AAST-MT)**  
College of Engineering & Technology — Computer Engineering Department  
Course: ECE5303 Cybersecurity | Instructor: Eng. Eman Magdy  
Team Lead: Youssef Ahmed | Reg#: 221010280

---

## Table of Contents

1. [Project Overview](#1-project-overview)
2. [Attack Chain Summary](#2-attack-chain-summary)
3. [Team Split](#3-team-split)
4. [Environment Setup — XAMPP](#4-environment-setup--xampp)
5. [Database Setup](#5-database-setup)
6. [Project Folder Setup](#6-project-folder-setup)
7. [Pair 1 File Reference](#7-pair-1-file-reference)
8. [Pair 2 Task List](#8-pair-2-task-list)
9. [Full Demo Flow](#9-full-demo-flow)
10. [Report Requirements](#10-report-requirements)

---

## 1. Project Overview

This project demonstrates a realistic **three-stage chained attack** on a vulnerable web application:

| Stage | Vulnerability | Goal |
|-------|--------------|------|
| 1 | SQL Injection (SQLi) | Extract admin credentials from the database |
| 2 | Reflected XSS | Deliver a malicious payload to the admin's browser |
| 3 | CSRF | Silently promote the attacker's account to Admin |

The project is demonstrated in **two states**:
- **Vulnerable** — the full attack chain executes successfully
- **Mitigated** — every layer is defended and the chain fails

---

## 2. Attack Chain Summary

```
[Attacker] --> SQLi on search.php --> Extracts admin info from DB
           --> Crafts malicious URL with XSS payload --> Sends to admin
           --> Admin clicks link --> XSS fires in admin's browser
           --> XSS triggers CSRF request to admin.php
           --> admin.php promotes "attacker" account to Admin
           --> Attacker now has full Admin access
```

---

## 3. Team Split

| Pair | Owns |
|------|------|
| Pair 1 ✅ | DB setup, login.php, search.php, search_safe.php, profile.php, admin.php, style.css |
| Pair 2 🔄 | attacker.php, XSS payload, full chain test, XSS mitigation, CSRF mitigation |

---

## 4. Environment Setup — XAMPP

### What is XAMPP and why do we need it?

XAMPP is a free local server package that installs three things you need to run this project:

| Component | What it does | Why you need it |
|-----------|-------------|-----------------|
| **Apache** | A web server that runs PHP files | Without it, .php files just open as text — not executed |
| **MySQL** | A database engine | Stores usernames, passwords, roles |
| **phpMyAdmin** | A browser UI to manage MySQL | Lets you create databases and tables without writing SQL manually |

Without XAMPP, none of the PHP pages will work.

---

### Step 1 — Download XAMPP

Go to the official site and download the Linux version:  
🔗 https://www.apachefriends.org/download.html

Choose: **XAMPP for Linux** (latest version)  
File will be named something like: `xampp-linux-x64-8.x.x-installer.run`

---

### Step 2 — Install XAMPP

Open a terminal and run these commands one by one:

```bash
# Give the installer permission to run
chmod +x xampp-linux-x64-8.x.x-installer.run

# Run the installer (requires sudo)
sudo ./xampp-linux-x64-8.x.x-installer.run
```

Follow the on-screen installer steps. Accept all defaults.  
XAMPP will be installed at: `/opt/lampp/`

---

### Step 3 — Start XAMPP

```bash
sudo /opt/lampp/lampp start
```

You should see:
```
Starting XAMPP for Linux...
XAMPP: Starting Apache...OK
XAMPP: Starting MySQL...OK
```

If you see OK for both Apache and MySQL — you are ready.

---

### Step 4 — Verify It Works

Open your browser and go to:
```
http://localhost
```

You should see the XAMPP welcome page.  
Then go to:
```
http://localhost/phpmyadmin
```

You should see the phpMyAdmin database interface.

---

### Step 5 — Auto-start XAMPP on Boot (Optional but Recommended)

So you don't have to start it manually every time:

```bash
sudo /opt/lampp/lampp enablessl
sudo systemctl enable lampp
```

---

## 5. Database Setup

### Why do we need a database?

The web application stores user accounts (username, password, role) in a MySQL database. The PHP files connect to this database to check login credentials and to update user roles during the CSRF attack.

If the database doesn't exist or the table structure doesn't match exactly, **none of the PHP files will work**.

---

### Step 1 — Open phpMyAdmin

In your browser go to:
```
http://localhost/phpmyadmin
```

---

### Step 2 — Create the Database

1. Click **"New"** in the left sidebar
2. In the "Database name" field type exactly:
```
cyberlab_db
```
3. Leave collation as default
4. Click **"Create"**

---

### Step 3 — Create the Users Table

1. Click on `cyberlab_db` in the left sidebar
2. Click the **"SQL"** tab at the top
3. Paste this exact SQL and click **"Go"**:

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    passwd VARCHAR(50) NOT NULL,
    role VARCHAR(20) NOT NULL
);
```

---

### Step 4 — Insert the Required Accounts

Still in the SQL tab, paste and run:

```sql
INSERT INTO users (username, passwd, role) VALUES
('admin', 'admin123', 'admin'),
('attacker', 'attacker123', 'user');
```

> You may choose different passwords — just make sure every team member knows them.

---

### Step 5 — Verify the Table

Click on `cyberlab_db` → `users` → **"Browse"**  
You should see two rows:

| id | username | passwd | role |
|----|----------|--------|------|
| 1 | admin | admin123 | admin |
| 2 | attacker | attacker123 | user |

---

## 6. Project Folder Setup

### Where do PHP files live in XAMPP?

Apache (the web server inside XAMPP) only serves files placed inside:
```
/opt/lampp/htdocs/
```

Any file outside this folder cannot be accessed via the browser.

---

### Step 1 — Create the Project Folder

```bash
sudo mkdir /opt/lampp/htdocs/cyberlab
```

---

### Step 2 — Set Permissions

So you can create and edit files without sudo every time:

```bash
sudo chmod -R 777 /opt/lampp/htdocs/cyberlab
```

---

### Step 3 — Clone This Repository Into the Folder

```bash
cd /opt/lampp/htdocs/cyberlab
git clone https://github.com/YOUR_REPO_URL .
```

> Replace `YOUR_REPO_URL` with the actual repo URL.  
> The dot `.` at the end clones directly into the current folder.

---

### Step 4 — Verify Files Are in Place

```bash
ls /opt/lampp/htdocs/cyberlab/
```

You should see:
```
login.php
search.php
search_safe.php
profile.php
admin.php
style.css
README.md
PAIR2_HANDOFF_MEMORY.md
```

---

### Step 5 — Test the App in Browser

Open:
```
http://localhost/cyberlab/login.php
```

Log in with:
- Username: `admin` / Password: `admin123` → should go to admin.php
- Username: `attacker` / Password: `attacker123` → should go to profile.php

If both work — the setup is complete.

---

### Step 6 — Set Up the Attacker Server (Pair 2 Only)

The attacker page must run on a **different port** to simulate a different origin.  
Create a separate folder and start a PHP dev server on port 8080:

```bash
sudo mkdir /opt/lampp/htdocs/attacker
cd /opt/lampp/htdocs/attacker
php -S localhost:8080
```

Place `attacker.php` inside `/opt/lampp/htdocs/attacker/`  
Access it at: `http://localhost:8080/attacker.php`

> Keep this terminal open while testing the attack chain.

---

## 7. Pair 1 File Reference

All files below are complete and confirmed working.

### login.php
- Authenticates users against `cyberlab_db`
- Admin → redirected to `admin.php`
- Regular user → redirected to `profile.php`
- Vulnerability: raw SQL query — SQLi auth bypass possible

### search.php
- Takes GET parameter `?query=`
- Runs raw SQL: `SELECT * FROM users WHERE username='$input'`
- Vulnerability: SQL Injection — Union-based, Error-based both work
- Also: echoes DB results without encoding → XSS-ready output

### search_safe.php
- Same as search.php but uses prepared statements
- Mitigation: `mysqli_prepare` + `mysqli_stmt_bind_param`
- SQLi confirmed dead — all injection payloads return empty

### profile.php
- Landing page for regular users after login
- Reflects `?username=` GET parameter directly into HTML with no encoding
- **XSS Entry Point**: `<?php echo $row; ?>` — no htmlspecialchars
- Contains two navigation buttons: Hack Search → search.php, Safe Search → search_safe.php

### admin.php
- Accessible only to users with `$_SESSION["role"] == "admin"`
- Lists all users in a table with a Promote button per user
- Promote action: POST to admin.php with `username=TARGET`
- **CSRF Vulnerability**: no token, no origin check, no SameSite cookie
- Raw SQL UPDATE — also vulnerable to SQLi in username field

### style.css
- Dark cybersecurity theme shared by all pages
- Fonts: Rajdhani + Share Tech Mono (loaded from Google Fonts)
- Key classes: `.card`, `.page`, `.search-wrapper`, `.search-box`, `.search-input`, `.search-btn`, `.results-table`, `.error`, `.success`

---

## 8. Pair 2 Task List

> Read `PAIR2_HANDOFF_MEMORY.md` in this repo for full technical details.

### Task P2-01 — attacker.php
Build a page on port 8080 that silently fires a CSRF POST to `admin.php`  
promoting the "attacker" account to admin on page load.

### Task P2-02 — XSS Payload
Craft a malicious URL targeting:
```
http://localhost/cyberlab/profile.php?username=<PAYLOAD>
```
The payload must redirect or fetch attacker.php when executed in the admin's browser.

### Task P2-03 — Full Chain Test
Execute and document the complete attack chain end-to-end:
```
Login as attacker → SQLi recon → Craft XSS URL → Admin clicks →
XSS fires → CSRF triggers → attacker promoted → Admin access confirmed
```
Take a screenshot at every step.

### Task P2-04 — Mitigations
**XSS fix in profile.php:**
```php
// Replace:
<?php echo $row; ?>
// With:
<?php echo htmlspecialchars($row, ENT_QUOTES, 'UTF-8'); ?>
```
Also add at top of profile.php:
```php
header("Content-Security-Policy: default-src 'self'");
```

**CSRF fix in admin.php:**
1. Generate token: `$_SESSION["csrf_token"] = bin2hex(random_bytes(32))`
2. Embed as hidden input in every promote form
3. Validate on POST: check `$_POST["csrf_token"] === $_SESSION["csrf_token"]`
4. Set SameSite cookie on session start

---

## 9. Full Demo Flow

### Vulnerable Version
| Step | Action | Expected Result |
|------|--------|----------------|
| 1 | Login as attacker | Lands on profile.php |
| 2 | Click Hack Search | Opens search.php |
| 3 | Type `' OR '1'='1'-- -` | All users + passwords shown |
| 4 | Open XSS URL in admin's browser | Script executes |
| 5 | attacker.php loads silently | CSRF POST fires |
| 6 | Check DB / admin panel | attacker role = admin |
| 7 | Login as attacker again | Admin panel accessible |

### Mitigated Version
| Step | Action | Expected Result |
|------|--------|----------------|
| 1 | Open XSS URL | Raw text shown — no script execution |
| 2 | Send CSRF POST manually | Token mismatch — action blocked |
| 3 | SQLi in search_safe.php | Empty result — no data extracted |

---

## 10. Report Requirements

**Filename:** `221010280.docx`  
**Submit via:** Google Classroom  
**Due:** Before Week 14 Discussion

Required sections:
- Cover page: all member names + reg#s, class ID, TA name
- Architecture diagram
- Tools used and why
- Step-by-step attack chain with screenshots
- Explanation of each vulnerability
- Explanation of each mitigation
- Defense-in-Depth conclusion
- Appendix: all screenshots labeled

---

*ECE5303 Project #5 — Chained Vulnerabilities | AAST-MT 2026*
