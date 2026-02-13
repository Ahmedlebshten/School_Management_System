# School Management System

A modern, refactored PHP-based school management system for viewing student information, marks, and results. Fully containerized with Docker and optimized for production deployment.

## Recent Updates (v2.0)

### 🐳 Docker & Deployment
- **Optimized Docker image**: Reduced size from 979MB → 834MB (14.8% reduction)
- **Production-ready Dockerfile**: Layer optimization, minimal base, all PHP extensions working
- **Docker development setup**: Hot-reload `docker-compose.override.yml` for live code changes
- **Image available on Docker Hub**: `ahmedlebshten/school_management_system:image-tag`

### 💾 Database & Data Fixes
- **Fixed single-row display bug**: Changed from filtered WHERE queries to fetching all student marks
- **Explicit data loops**: All mark rows now correctly displayed and processed
- **Verified data integrity**: Database contains 5 marks per student, all displayed correctly

### 🔧 Code Improvements
- **Direct PDO connections**: Simplified data access pattern for better performance
- **Enhanced StudentData class**: Better structure with explicit data validation
- **Refactored result download**: Excel export now includes all marks with proper totals
- **Clean authentication**: Session-based login with proper error handling

## Features

- Student login with ID and class verification
- Student dashboard with personal information and marks
- Download student results
- Contact form integration with EmailJS
- Responsive design
- Session-based authentication
- Database-driven student data management
- Clean, maintainable code architecture

## Project Structure

```
├── app/
│   ├── bootstrap.php          # Application bootstrap
│   ├── config/
│   │   └── Database.php       # Database connection class
│   ├── classes/
│   │   └── Auth.php           # Authentication class
│   └── templates/
│       └── nav.html           # Navigation template
├── public/                     # Web entry point
│   ├── index.php              # Student dashboard
│   ├── login.php              # Login page
│   ├── home.php               # Home page
│   ├── contact.php            # Contact page
│   ├── logout.php             # Logout handler
│   └── download_result.php    # Result download
├── assets/                     # Static files
│   ├── style.css              # Global styles
│   ├── style-index.css        # Dashboard styles
│   ├── style-login.css        # Login styles
│   ├── style-home.css         # Home styles
│   ├── style-contact.css      # Contact styles
│   └── script.js              # JavaScript
├── vendor/                     # Composer dependencies
├── composer.json              # Composer configuration
├── .env.example               # Environment template
└── README.md                  # Documentation
```

## Setup Requirements

- PHP 7.4 or higher
- MySQL/MariaDB
- Composer
- Web server (Apache/Nginx)

## Installation Steps

1. **Install dependencies**

   ```bash
   composer install
   ```

2. **Create environment file**

   ```bash
   cp .env.example .env
   ```

3. **Configure database in `.env`**

   ```
   DB_HOST=localhost
   DB_NAME=school_management
   DB_USER=root
   DB_PASS=your_password
   DB_CHARSET=utf8mb4
   ```

4. **Set up database tables** as per schema below

5. **Configure web server** to point to the `public/` directory

## Database Schema

### student_data table

```sql
CREATE TABLE student_data (
    id INT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    class VARCHAR(50) NOT NULL
);
```

### Class-specific tables (first, second, third, fourth)

```sql
CREATE TABLE first (
    id INT,
    subject VARCHAR(100),
    marks INT,
    FOREIGN KEY (id) REFERENCES student_data(id)
);
```

## Usage

Access the application at: `http://localhost/School_Management_System/public/home.php`

### Student Login

1. Go to Login page
2. Enter Student ID
3. Select your Class
4. View marks and student information
5. Download results

## Architecture Improvements

This refactored version includes:

- ✅ **PSR-4 Autoloading** - Organized namespace structure
- ✅ **Database Abstraction** - Centralized connection management
- ✅ **Authentication Class** - Reusable auth logic
- ✅ **Bootstrap Pattern** - Single entry point for dependencies
- ✅ **Separation of Concerns** - Clear folder organization
- ✅ **Security** - Parameterized queries, input validation
- ✅ **Better Documentation** - Inline comments and README
- ✅ **Code Quality** - Consistent formatting and naming

## Future Enhancements

- PDF result generation (TCPDF/DOMPDF)
- Admin panel for managing students
- Role-based access control
- Email notifications
- API endpoints for mobile apps
- Database migrations
- Advanced search and filtering

## Security Considerations

- Always use parameterized queries (prepared statements)
- Keep `.env` file out of version control
- Validate all user inputs server-side
- Use HTTPS in production
- Implement rate limiting on login
- Properly hash sensitive data

## Refactoring Summary

**Removed:**

- Redundant code duplication
- Unused dependencies
- Inline SQL connections
- Poor file organization

**Added:**

- Database abstraction layer
- Authentication helper class
- PSR-4 compliant autoloading
- Comprehensive error handling
- Global stylesheet (style.css)
- Improved JavaScript structure
- Environment configuration
- Detailed documentation

**Improved:**

- Code maintainability
- Security practices
- Project scalability
- Developer experience
- Code reusability

---

_A practical application of Backend Development concepts using PHP and MySQL._

- It demonstrates secure database handling by separating sensitive information into a .env file.
- It follows organized project structure separating assets, source code, and environment configurations.

Requirements:

- PHP 7.4 or higher
- MySQL Server
- Composer
- Git (optional)
- Local server environment (XAMPP, MAMP)

Technologies Used:

- Native PHP
- MySQL
- Composer
- HTML
- CSS
- Dotenv (for environment variable management)
