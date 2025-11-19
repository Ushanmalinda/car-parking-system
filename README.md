# Car Parking Management System

A comprehensive PHP-based car parking management system with admin dashboard, booking management, and reporting features.

## Features

- **User Authentication**: Secure login system for admin and staff
- **Dashboard**: Real-time statistics and overview
- **Parking Slot Management**: Add, edit, and manage parking slots
- **Booking Management**: Create and manage vehicle parking bookings
- **Vehicle Exit**: Process vehicle exit with automatic charge calculation
- **Vehicle Categories**: Manage different vehicle types and rates
- **Reports**: Generate revenue and booking reports
- **User Management**: Add and manage system users
- **Responsive Design**: Works on desktop and mobile devices

## System Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- Web browser (Chrome, Firefox, Safari, Edge)

## Installation Instructions

### Step 1: Extract Files
Extract the project files to your web server directory:
- XAMPP: `C:/xampp/htdocs/car-parking-system`
- WAMP: `C:/wamp64/www/car-parking-system`
- Other: Your web server's document root

### Step 2: Create Database
1. Open phpMyAdmin (http://localhost/phpmyadmin)
2. Create a new database named `car_parking_system`
3. Import the database schema:
   - Click on the `car_parking_system` database
   - Go to the "Import" tab
   - Choose the file: `database/car_parking_system.sql`
   - Click "Go" to import

### Step 3: Configure Database Connection
1. Open `config/config.php`
2. Update the database credentials if needed:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'car_parking_system');
   ```

### Step 4: Update Site URL
In `config/config.php`, update the SITE_URL:
```php
define('SITE_URL', 'http://localhost/car-parking-system/');
```

### Step 5: Set Permissions
Ensure the web server has read/write permissions for the project directory.

## Default Login Credentials

**Admin Account:**
- Username: `admin`
- Password: `admin123`

**Important:** Change the default password after first login!

## Project Structure

```
car-parking-system/
├── admin/                      # Admin panel files
│   ├── dashboard.php          # Admin dashboard
│   ├── parking-slots.php      # Slot management
│   ├── new-booking.php        # Create new booking
│   ├── bookings.php           # View all bookings
│   ├── exit-vehicle.php       # Process vehicle exit
│   ├── view-booking.php       # Booking details
│   ├── vehicle-categories.php # Category management
│   ├── reports.php            # Reports & analytics
│   ├── users.php              # User management
│   └── sidebar.php            # Navigation sidebar
├── assets/                    # Static assets
│   ├── css/
│   │   └── style.css         # Main stylesheet
│   ├── js/
│   │   └── main.js           # JavaScript functions
│   └── images/               # Image files
├── config/                    # Configuration files
│   ├── config.php            # Main configuration
│   └── database.php          # Database connection
├── database/                  # Database files
│   └── car_parking_system.sql # Database schema
├── includes/                  # Reusable PHP files
│   ├── functions.php         # Helper functions
│   ├── header.php           # Page header
│   └── footer.php           # Page footer
├── user/                      # User panel files
├── index.php                 # Login page
├── logout.php                # Logout handler
└── README.md                 # This file
```

## Usage Guide

### 1. Login
- Navigate to `http://localhost/car-parking-system/`
- Enter admin credentials
- Click "Login"

### 2. Manage Parking Slots
- Go to "Parking Slots" from the sidebar
- Click "Add New Slot" to create slots
- Edit or delete existing slots

### 3. Create Booking
- Go to "New Booking"
- Fill in vehicle and owner details
- Select parking slot
- Submit to create booking

### 4. Process Exit
- Go to "All Bookings"
- Find the active booking
- Click "Exit" button
- Select payment method
- Confirm to generate bill

### 5. View Reports
- Go to "Reports & Analytics"
- Select date range
- View revenue and booking statistics

### 6. Manage Users
- Go to "Users"
- Add new staff/admin users
- Activate/deactivate users

## Parking Charges

Default rates (configurable in `config/config.php`):
- Two Wheeler: ₹10 per hour
- Four Wheeler: ₹20 per hour

Minimum charge: 1 hour
Partial hours are rounded up to the next full hour.

## Features in Detail

### Parking Slot Management
- Add multiple parking slots
- Organize by floor and slot number
- Set vehicle type (two-wheeler/four-wheeler)
- Track slot status (available/occupied/maintenance)

### Booking System
- Record vehicle and owner information
- Assign parking slot automatically
- Track entry time
- Calculate parking duration
- Generate unique booking numbers

### Exit & Billing
- Automatic charge calculation based on duration
- Multiple payment methods (Cash, Card, UPI, Online)
- Print receipt option
- Update slot availability automatically

### Reports
- Daily revenue reports
- Booking statistics
- Vehicle type analysis
- Date-range filtering
- Export to CSV (optional enhancement)

## Customization

### Change Parking Rates
Edit `config/config.php`:
```php
define('TWO_WHEELER_RATE', 10);  // Rate per hour
define('FOUR_WHEELER_RATE', 20); // Rate per hour
```

### Add More Vehicle Categories
Go to "Vehicle Categories" in admin panel and add new categories with custom rates.

### Modify Appearance
Edit `assets/css/style.css` to customize colors, fonts, and layout.

## Security Features

- Password hashing using PHP's `password_hash()`
- SQL injection prevention with prepared statements
- XSS protection with input sanitization
- Session management
- Activity logging

## Troubleshooting

### Database Connection Error
- Check database credentials in `config/config.php`
- Ensure MySQL service is running
- Verify database name is correct

### Login Not Working
- Clear browser cache and cookies
- Check if database was imported correctly
- Verify user exists in `users` table

### Page Not Found
- Check SITE_URL in `config/config.php`
- Ensure mod_rewrite is enabled (Apache)
- Verify file paths are correct

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Opera (latest)

## License

This project is open-source and available for educational purposes.

## Support

For issues or questions, please check:
- Database import errors
- File permissions
- PHP version compatibility
- Web server configuration

## Future Enhancements

- QR code generation for bookings
- SMS/Email notifications
- Monthly pass system
- Online payment gateway integration
- Mobile app
- CCTV integration
- Automated barrier control

## Credits

Developed using:
- PHP
- MySQL
- HTML5
- CSS3
- JavaScript

---

**Last Updated:** 2025
**Version:** 1.0.0
