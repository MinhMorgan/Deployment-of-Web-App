# Web Programming & Applications — Midterm Project (Course Code: 503073)
## Topic 5: Deployment of Web Applications
### Project: Containerized Student Course Registration Web System with Docker, Cloudflare Ingress Tunnel, and Cloud Hosting

**Academic Year:** 2026 – 2027 (Semester 1)  
**Instructor:** Mr. Mai Van Manh  
**Institution:** Faculty of Information Technology — Ton Duc Thang University (TDTU)  

---

### Team Members & Contributions
| Student Name | Student ID | Role | Contribution |
| :--- | :--- | :--- | :--- |
| **Vu Hoang Phuc** | **524K0024** | Team Leader & Full-Stack Application Developer | 33.3% |
| **Pham Diep Quang Minh** | **524K0023** | DevOps, Docker & Cloudflare Tunnel Engineer | 33.3% |
| **Nguyen Ngoc Nhat Minh** | **524K0022** | Database Administrator & QA / Testing Lead | 33.3% |

---

## 1. System Requirements & Prerequisites

Before setting up and running the application, make sure your machine meets the minimum hardware and software prerequisites.

### Hardware Requirements
- **Processor:** 64-bit Dual-core CPU (Quad-core Intel Core i5 / AMD Ryzen 5 or Apple Silicon recommended).
- **RAM:** Minimum **4 GB** (8 GB recommended for smooth Docker virtualization).
- **Free Disk Space:** Minimum **5 GB** available storage for Docker base images, MariaDB data volumes, and application sources.

### Operating System Support
- **Windows:** Windows 10 (64-bit: Pro, Enterprise, or Education, version 19044+) or Windows 11 with WSL2 enabled.
- **macOS:** macOS Monterey 12.0 or higher (Intel or Apple Silicon M1/M2/M3/M4).
- **Linux:** Modern Linux distribution (Ubuntu 22.04 LTS+, Debian 12+, CentOS Stream 9+, Fedora 38+).

### Required Software
- **Docker Desktop / Docker Engine:** Version `24.0.0` or higher.
- **Docker Compose:** Version `v2.20.0` or higher (integrated into Docker CLI as `docker compose`).
- **Git:** Version `2.30.0` or higher (to clone and manage source code).
- **Web Browser:** Google Chrome, Mozilla Firefox, Microsoft Edge, or Safari.

### Required Network Ports
Ensure the following ports are open and not blocked by local background services:
| Port | Service | Description |
| :--- | :--- | :--- |
| `8080` | Web Application | Apache HTTP Server serving PHP application |
| `8081` | phpMyAdmin | Web-based MariaDB management dashboard |
| `3306` | MariaDB SQL | Database service endpoint |

---

## 2. Step-by-Step Installation & Dependency Setup Guide

Follow these step-by-step instructions to install all required dependencies and prepare the environment.

### Step 1: Install Core Dependencies

#### Option A: Docker & Docker Compose (Recommended)
1. **Windows / macOS:**
   - Download and install **Docker Desktop** from [docker.com](https://www.docker.com/products/docker-desktop/).
   - Follow the installation wizard and ensure **WSL 2 backend** is checked on Windows.
   - Start Docker Desktop and verify the installation in your terminal:
     ```bash
     docker --version
     docker compose version
     ```

2. **Linux (Ubuntu / Debian):**
   Install Docker Engine and the Docker Compose plugin via official package repositories:
   ```bash
   # Update package lists and install prerequisite utilities
   sudo apt-get update
   sudo apt-get install -y ca-certificates curl gnupg

   # Add Docker official GPG key
   sudo install -m 0755 -d /etc/apt/keyrings
   curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
   sudo chmod a+r /etc/apt/keyrings/docker.gpg

   # Set up repository
   echo \
     "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu \
     $(. /etc/os-release && echo "$VERSION_CODENAME") stable" | \
     sudo tee /etc/apt/sources.list.d/docker.list > /dev/null

   # Install Docker Engine, CLI, and Compose plugin
   sudo apt-get update
   sudo apt-get install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin

   # Verify installation
   docker --version
   docker compose version
   ```

#### Option B: Bare-Metal / Local XAMPP Setup (Alternative for Development)
If you prefer running without Docker:
- Download and install **XAMPP** (PHP 8.2+ with MariaDB) from [apachefriends.org](https://www.apachefriends.org/).
- Ensure the `pdo_mysql` extension is enabled in `php.ini`.

---

### Step 2: Clone or Download the Project Repository

Open your terminal or PowerShell and clone the project:
```bash
# Clone the repository
git clone https://github.com/phucvh726/Deployment-of-Web-App.git

# Navigate to the project root directory
cd Deployment-of-Web-App
```

---

### Step 3: Fetch & Build Container Dependencies

Pull all base images and build the custom Apache/PHP container:
```bash
# Pull upstream service images
docker pull mariadb:10.4
docker pull phpmyadmin:latest
docker pull cloudflare/cloudflared:latest

# Build the custom PHP Apache application container image
docker compose build
```

---

## 3. Project Execution Commands

All microservices are orchestrated with Docker Compose. Use the following commands to run, monitor, and manage the system.

### 1. Launch All Services
Run all containers in detached mode (background):
```bash
docker compose up -d
```
> **What happens during launch:**
> - Docker builds the Apache + PHP 8.2 container with PDO MySQL support.
> - Starts the MariaDB 10.4 database container and automatically mounts and executes `midterm.sql` on first boot.
> - Starts phpMyAdmin on port `8081`.
> - Launches Cloudflare Ingress Tunnel connecting the application to edge networks.

### 2. Check Container Health & Status
Verify that all 4 containers are running:
```bash
docker compose ps
```
**Expected output:**
```
NAME              IMAGE                          COMMAND                  SERVICE      STATUS      PORTS
tdtu_db           mariadb:10.4                   "docker-entrypoint.s…"   db           Up          0.0.0.0:3306->3306/tcp
tdtu_phpmyadmin   phpmyadmin:latest              "/docker-entrypoint.…"   phpmyadmin   Up          0.0.0.0:8081->80/tcp
tdtu_tunnel       cloudflare/cloudflared:latest  "cloudflared tunnel …"   tunnel       Up          
tdtu_web          deployment-of-web-app-web      "docker-php-entrypoi…"   web          Up          0.0.0.0:8080->80/tcp
```

### 3. View Real-Time Service Logs
To monitor logs across all services:
```bash
docker compose logs -f
```
Or monitor a specific service:
```bash
# Web application logs
docker compose logs -f web

# Database service logs
docker compose logs -f db

# Cloudflare tunnel logs
docker compose logs -f tunnel
```

### 4. Restart Services
Restart all services if configuration changes are made:
```bash
docker compose restart
```

### 5. Stop the Project
Stop all running containers without losing database data:
```bash
docker compose stop
```
Or remove containers and networks while keeping persistent database volumes intact:
```bash
docker compose down
```

### 6. Reset Database & Volumes (Fresh Start)
To completely wipe persistent volumes and re-import `midterm.sql` on the next run:
```bash
docker compose down -v
docker compose up -d
```

---

## 4. Accessing Application Endpoints

### A. Local Containerized Endpoints
- **Student Course Registration Website:**  
  👉 **http://localhost:8080/**
- **phpMyAdmin Database Management Console:**  
  👉 **http://localhost:8081/**  
  - **Server:** `db`  
  - **Username:** `root`  
  - **Password:** `rootpassword`

### B. Public Cloud Production Deployments
- **InfinityFree Production URL:**  
  👉 **https://studentcourse.infinityfreeapp.com/login.php?i=1**
- **Cloudflare Edge Ingress Tunnel:**  
  Automated edge tunnel exposing `web:80` with zero router port forwarding and full SSL/TLS termination.

---

## 5. Pre-Configured Test Accounts for Evaluation

| Account Type | Student ID / Username | Password / Access | Description |
| :--- | :--- | :--- | :--- |
| **Pre-seeded Student** | `524k0024` | *(Pre-configured test account)* | Seeded in `midterm.sql` with course enrollments |
| **New Student Account** | Any ID (e.g. `524K9999`) | Password (min 8 chars) | Create via `http://localhost:8080/signup.php` |
| **phpMyAdmin Console** | `root` | `rootpassword` | Direct MariaDB admin on `http://localhost:8081` |

---

## 6. Comprehensive Functional Testing Guide (Step-by-Step)

Follow this step-by-step testing manual to verify every feature of the Student Course Registration System.

```
+-----------------------------------------------------------------------------------+
|                              SYSTEM TESTING WORKFLOW                              |
|                                                                                   |
|  [ 1. Registration ]  -->  [ 2. Login ]  -->  [ 3. Browse Courses ]               |
|                                                      |                            |
|                                                      v                            |
|  [ 6. Logout ]        <--  [ 5. Drop Course ] <-- [ 4. Register Course ]          |
+-----------------------------------------------------------------------------------+
```

---

### Test Case 1: Student Account Registration (`signup.php`)
- **Objective:** Verify user input validation, password hashing, and prevention of duplicate student records.
- **URL:** `http://localhost:8080/signup.php`

#### Test Steps:
1. **Validation Checks (Negative Tests):**
   - Leave one or more fields blank and click **"Register"**.  
     👉 **Expected Result:** Error alert displays `"Please fill in all fields."`
   - Enter a password shorter than 8 characters (e.g., `12345`).  
     👉 **Expected Result:** Error alert displays `"Password must contain at least 8 characters."`
   - Enter non-matching passwords in **Password** and **Confirm Password**.  
     👉 **Expected Result:** Error alert displays `"Passwords do not match."`
   - Enter an invalid email format (e.g., `user@domain`).  
     👉 **Expected Result:** Error alert displays `"Invalid email address."`

2. **Successful Registration (Positive Test):**
   - Fill in valid data:
     - **Student ID:** `524K9999`
     - **Full Name:** `Nguyen Van Test`
     - **Email:** `test.student@tdtu.edu.vn`
     - **Password:** `Admin@12345`
     - **Confirm Password:** `Admin@12345`
   - Click **"Register"**.  
     👉 **Expected Result:** Success banner displays `"Account created successfully! You can now log in."`

3. **Duplicate Prevention (Negative Test):**
   - Attempt to register again using the same Student ID `524K9999` or email `test.student@tdtu.edu.vn`.  
     👉 **Expected Result:** Handled gracefully with error `"Student ID or email already exists."`

---

### Test Case 2: Authentication & Session Management (`login.php`)
- **Objective:** Verify credential verification, secure session initialization, and access protection.
- **URL:** `http://localhost:8080/login.php`

#### Test Steps:
1. **Invalid Login:**
   - Enter Student ID `524K9999` with a wrong password `wrongpassword` and click **"Login"**.  
     👉 **Expected Result:** An error message displays `"Invalid Student ID or password."`
2. **Valid Login:**
   - Enter Student ID `524K9999` and password `Admin@12345`.
   - Click **"Login"**.  
     👉 **Expected Result:** Successfully authenticated and redirected to the home dashboard (`index.php`), displaying the student's name in the greeting banner.
3. **Route Protection (Access Control):**
   - Open an incognito/private browser window.
   - Navigate directly to protected routes: `http://localhost:8080/courses.php`, `http://localhost:8080/registered.php`, or `http://localhost:8080/information.php`.  
     👉 **Expected Result:** Immediate redirection back to `login.php`. Direct access is blocked for unauthenticated sessions.

---

### Test Case 3: Course Catalog Browsing (`courses.php`)
- **Objective:** Verify dynamic course rendering from MariaDB, including capacity and schedules.
- **URL:** `http://localhost:8080/courses.php`

#### Test Steps:
1. Log in and click **"Registration"** on the navigation bar.
2. Verify the course catalog displays:
   - **Course Name** (e.g., Web Programming, Database Management, Software Engineering).
   - **Credits** (e.g., 3 or 4 credits).
   - **Study Schedule** (Start Week to End Week).
   - **Enrolled / Maximum Capacity** counter.
   - Action button: **"Register"**.

---

### Test Case 4: Course Registration / Enrollment
- **Objective:** Verify enrollment transaction integrity and duplicate registration prevention.
- **URL:** `http://localhost:8080/courses.php`

#### Test Steps:
1. **Successful Enrollment:**
   - Find course **"Database Management"** and click **"Register"**.  
     👉 **Expected Result:** The registration is saved in the database. A success alert or updated status indicates successful enrollment, and the registered count increments by 1.
2. **Duplicate Registration Prevention:**
   - Click **"Register"** on the exact same course (**"Database Management"**) again.  
     👉 **Expected Result:** Transaction is safely rolled back; displays `"You have already registered for this subject."`
3. **Capacity Limit Test:**
   - If a course reaches its maximum student quota (`MaxStudents`), attempting to register displays `"This subject is full."`

---

### Test Case 5: Registered Courses Management & Drop (`registered.php`)
- **Objective:** Verify course list inspection and course cancellation (drop) functionality.
- **URL:** `http://localhost:8080/registered.php`

#### Test Steps:
1. Click **"Registered Courses"** on the navigation bar.
2. Verify that enrolled courses appear with:
   - Subject Name
   - Credits
   - Week schedule
   - Registration timestamp
   - **"Cancel Registration"** button.
3. **Drop / Cancel Course:**
   - Click **"Cancel Registration"** on one of your registered courses.
   - Confirm the browser prompt (`"Cancel this registration?"`).  
     👉 **Expected Result:** Banner displays `"Registration cancelled successfully."` The course status in the database changes to `'Cancelled'` and disappears from active registered courses.
4. If all courses are dropped, verify the fallback message: `"You have not registered for any courses yet."` with a link to **Browse Available Courses**.

---

### Test Case 6: Student Profile Verification (`information.php`)
- **Objective:** Verify account details match authenticated session data.
- **URL:** `http://localhost:8080/information.php`

#### Test Steps:
1. Click **"Information"** on the navigation bar.
2. Verify the **Account Details** card correctly displays:
   - **Student ID:** `524K9999`
   - **Student Name:** `Nguyen Van Test`
   - **Email:** `test.student@tdtu.edu.vn`

---

### Test Case 7: Session Termination & Logout (`logout.php`)
- **Objective:** Verify complete session destruction on logout.
- **URL:** Triggered via **"Logout"** button

#### Test Steps:
1. Click the red **"Logout"** button on the right of the navigation bar.  
   👉 **Expected Result:** Session is cleared and the browser is redirected to `login.php`.
2. Click the browser's **Back** button.  
   👉 **Expected Result:** The application does not allow actions; accessing any protected page forces a redirect to `login.php`.

---

### Test Case 8: Database Verification via phpMyAdmin
- **Objective:** Verify persistent data storage, relationship integrity, and password hashing in MariaDB.
- **URL:** `http://localhost:8081/`

#### Test Steps:
1. Open `http://localhost:8081` in your browser.
2. Log in with Server: `db`, Username: `root`, Password: `rootpassword`.
3. Open the **`midterm`** database and inspect tables:
   - **`Accounts` table:** Confirm new record for `524K9999` exists and `PasswordHash` is hashed via BCRYPT (`$2y$10$...`), never plain text.
   - **`Registrations` table:** Confirm records link `AccountID` and `SubjectID` with correct `Status` (`Registered` or `Cancelled`).
   - **`Subjects` table:** Confirm all 10 subjects are correctly populated with credits, start/end weeks, and student limits.

---

## 7. Troubleshooting & Common Issues

| Issue | Cause | Resolution |
| :--- | :--- | :--- |
| **Port 8080 already in use** | Another web server (IIS, Apache, Nginx) is running | Stop the existing service or edit `docker-compose.yml` to change host port (e.g. `"8082:80"`). |
| **Port 3306 already in use** | Local MySQL / MariaDB service running | Stop local MySQL service (`net stop MySQL` in admin terminal) or modify host port in `docker-compose.yml`. |
| **Database connection failed** | MariaDB container is initializing | Wait 10-15 seconds for first-time DB initialization. Check logs via `docker compose logs db`. |
| **Docker daemon not running** | Docker Desktop is closed | Start Docker Desktop application and wait until the status displays "Engine running". |
| **Line ending issues on Linux/Mac** | Windows CRLF line breaks | Run `git config core.autocrlf input` and re-clone the repository. |

---

## 8. Submission Artifacts
- **Report Document:** `524K0024_524K0023_524K0022_Midterm_Report.docx` (Complete academic format adhering to Faculty guidelines).
- **Presentation Video:** *(Attached link in report submission - Maximum 20 minutes in English)*.
