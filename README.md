# 🚗 Car Parking Management System

A comprehensive web-based parking management system built with PHP and MySQL. This system allows administrators to manage parking slots, bookings, vehicle categories, and customer support tickets efficiently.

## ✨ Features

### 👨‍💼 Admin Features
- **Dashboard** - Overview of parking statistics, revenue, and occupancy
- **Booking Management** - Create, view, and manage parking bookings
- **Parking Slot Management** - Add, edit, and monitor parking slot availability
- **Vehicle Categories** - Manage different vehicle types (Two-wheeler, Four-wheeler, etc.)
- **User Management** - Manage customer and staff accounts
- **Exit Vehicle** - Process vehicle exits and calculate parking fees
- **Reports** - Generate detailed reports on bookings, revenue, and usage
- **Support System** - Handle customer bug reports and support requests
- **Settings** - Configure system settings and preferences
- **Multi-Currency Support** - Support for multiple currencies

### 👤 User Features
- **User Dashboard** - View personal booking history and status
- **Book Parking Slot** - Reserve parking slots online
- **Contact Support** - Submit bug reports and support tickets
- **Account Management** - Manage personal profile and settings

### 🔐 Security Features
- Secure login system with password hashing
- Session management with timeout
- Role-based access control (System Admin, Admin, User)
- Activity logging for audit trails
- SQL injection protection with prepared statements

## 🛠️ Technologies Used

- **Backend**: PHP 7.4+
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript
- **Server**: Apache (XAMPP/WAMP)
- **Security**: Password hashing, Prepared statements

## 📋 Prerequisites

Before you begin, ensure you have the following installed:
- XAMPP/WAMP/LAMP (Apache, PHP 7.4+, MySQL)
- Web browser (Chrome, Firefox, Edge, etc.)
- Git (for version control)

## 🚀 Installation

### 1. Clone the Repository
```bash
git clone https://github.com/Ushanmalinda/car-parking-system.git
cd car-parking-system
```

### 2. Move to Web Server Directory
Copy the project folder to your web server's document root:
- **XAMPP**: `C:\xampp\htdocs\`
- **WAMP**: `C:\wamp64\www\`
- **LAMP**: `/var/www/html/`

### 3. Database Setup

#### Option 1: Using phpMyAdmin
1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Create a new database named `car_parking_system`
3. Import the SQL file: `database/car_parking_system.sql`
4. (Optional) Import additional SQL files if needed:
   - `database/add_currency_support.sql`
   - `database/add_support_system.sql`

#### Option 2: Using MySQL Command Line
```bash
mysql -u root -p
CREATE DATABASE car_parking_system;
USE car_parking_system;
SOURCE database/car_parking_system.sql;
exit;
```

### 4. Configure Database Connection
Edit `config/config.php` and update database credentials if needed:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); // Your MySQL password
define('DB_NAME', 'car_parking_system');
```

### 5. Access the Application
Open your web browser and navigate to:
```
http://localhost/car-parking-system/
```

## 🔑 Default Login Credentials

### Admin Account
- **Username**: `admin`
- **Password**: `admin123`

### System Admin Account (if applicable)
- **Username**: `sysadmin`
- **Password**: `sysadmin123`

⚠️ **Important**: Change these default passwords immediately after first login!

## 📁 Project Structure

```
car-parking-system/
├── admin/                      # Admin panel files
│   ├── bookings.php           # Manage bookings
│   ├── dashboard.php          # Admin dashboard
│   ├── exit-vehicle.php       # Vehicle exit processing
│   ├── new-booking.php        # Create new booking
│   ├── parking-slots.php      # Manage parking slots
│   ├── reports.php            # Generate reports
│   ├── settings.php           # System settings
│   ├── users.php              # User management
│   ├── vehicle-categories.php # Vehicle category management
│   └── ...
├── assets/                     # Static assets
│   ├── css/                   # Stylesheets
│   ├── images/                # Images
│   └── js/                    # JavaScript files
├── config/                     # Configuration files
│   ├── config.php             # Main configuration
│   └── database.php           # Database connection class
├── database/                   # SQL files
│   └── car_parking_system.sql # Database schema
├── includes/                   # Reusable PHP includes
│   ├── footer.php             # Footer template
│   ├── functions.php          # Helper functions
│   └── header.php             # Header template
├── user/                       # User panel files
│   └── dashboard.php          # User dashboard
├── index.php                   # Login page
├── logout.php                  # Logout handler
├── contact-support.php         # Support contact page
└── README.md                   # This file
```

## 💡 Usage

### For Administrators:
1. Login with admin credentials
2. Navigate to **Dashboard** to view system overview
3. Manage **Parking Slots** - Add, edit, or delete parking spaces
4. Create **Bookings** for customers
5. Process **Vehicle Exits** and calculate fees
6. View **Reports** for business insights
7. Manage **Users** and **Vehicle Categories**

### For Users:
1. Register or receive credentials from admin
2. Login to user dashboard
3. Book parking slots online
4. View booking history
5. Contact support for assistance

## 🔧 Configuration

### Parking Rates
Edit `config/config.php` to modify parking rates:
```php
define('TWO_WHEELER_RATE', 10);  // Per hour
define('FOUR_WHEELER_RATE', 20); // Per hour
```

### Session Timeout
Adjust session timeout in `config/config.php`:
```php
define('SESSION_TIMEOUT', 3600); // 1 hour in seconds
```

### Timezone
Change timezone in `config/config.php`:
```php
date_default_timezone_set('Asia/Kolkata');
```

## 🐛 Troubleshooting

### Database Connection Issues
- Verify MySQL service is running
- Check database credentials in `config/config.php`
- Ensure database `car_parking_system` exists

### Login Problems
- Clear browser cache and cookies
- Check if user account status is 'active' in database
- Verify password matches default credentials

### Permission Issues
- Ensure web server has read/write permissions on project folder
- Check file permissions (755 for directories, 644 for files)

## 🤝 Contributing

Contributions are welcome! Please follow these steps:
1. Fork the repository
2. Create a new branch (`git checkout -b feature/YourFeature`)
3. Commit your changes (`git commit -m 'Add some feature'`)
4. Push to the branch (`git push origin feature/YourFeature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 👨‍💻 Developer

**Ushan Malinda**
- GitHub: [@Ushanmalinda](https://github.com/Ushanmalinda)

## 📧 Support

For support, email your queries or open an issue in the GitHub repository.

## 🙏 Acknowledgments

- Thanks to all contributors who help improve this system
- Built with dedication to streamline parking management

---

**Made with ❤️ for efficient parking management**
