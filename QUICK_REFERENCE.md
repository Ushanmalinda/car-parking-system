# QUICK REFERENCE GUIDE
## Car Parking Management System

---

## 🚀 QUICK START (3 STEPS)

### 1️⃣ Database Setup
```
- Open: http://localhost/phpmyadmin
- Create database: car_parking_system
- Import file: database/car_parking_system.sql
```

### 2️⃣ Access System
```
URL: http://localhost/car-parking-system/
Username: admin
Password: admin123
```

### 3️⃣ Start Using
```
- Add parking slots
- Create bookings
- Process exits
```

---

## 📋 FILE REFERENCE

### 🔐 Authentication
- `index.php` - Login page
- `logout.php` - Logout handler

### ⚙️ Configuration
- `config/config.php` - Main settings
- `config/database.php` - DB connection

### 📊 Admin Pages
- `admin/dashboard.php` - Main dashboard
- `admin/parking-slots.php` - Manage slots
- `admin/new-booking.php` - Create booking
- `admin/bookings.php` - All bookings
- `admin/exit-vehicle.php` - Process exit
- `admin/vehicle-categories.php` - Categories
- `admin/reports.php` - Reports
- `admin/users.php` - User management
- `admin/settings.php` - System settings

### 🎨 Assets
- `assets/css/style.css` - Styling
- `assets/js/main.js` - JavaScript

### 🗄️ Database
- `database/car_parking_system.sql` - Schema

---

## 🎯 COMMON TASKS

### Add Parking Slot
1. Go to "Parking Slots"
2. Click "Add New Slot"
3. Fill: Number, Floor, Type
4. Click "Add Slot"

### Create Booking
1. Go to "New Booking"
2. Fill owner & vehicle details
3. Select vehicle type & slot
4. Click "Create Booking"

### Process Exit
1. Go to "All Bookings"
2. Find active booking
3. Click "Exit"
4. Select payment method
5. Click "Process Exit"

### Generate Report
1. Go to "Reports"
2. Select date range
3. View statistics

---

## 🔧 CONFIGURATION

### Parking Rates
File: `config/config.php`
```php
define('TWO_WHEELER_RATE', 10);
define('FOUR_WHEELER_RATE', 20);
```

### Database Settings
File: `config/config.php`
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'car_parking_system');
```

### Site URL
File: `config/config.php`
```php
define('SITE_URL', 'http://localhost/car-parking-system/');
```

---

## 🗄️ DATABASE TABLES

1. `users` - System users
2. `parking_slots` - Slot inventory
3. `vehicle_categories` - Categories & rates
4. `parking_bookings` - Booking records
5. `payments` - Payment transactions
6. `system_settings` - Settings
7. `activity_logs` - Activity logs

---

## 🎨 CSS CLASSES

### Buttons
- `.btn-primary` - Blue button
- `.btn-secondary` - Gray button
- `.btn-success` - Green button
- `.btn-danger` - Red button
- `.btn-sm` - Small button

### Badges
- `.badge-available` - Green badge
- `.badge-occupied` - Red badge
- `.badge-parked` - Yellow badge
- `.badge-exited` - Green badge

### Cards
- `.stat-card` - Statistics card
- `.detail-card` - Detail card
- `.form-container` - Form wrapper

---

## 📱 RESPONSIVE

- Desktop: Full layout
- Tablet: Adapted layout  
- Mobile: Stacked layout

---

## 🔒 SECURITY

### Password Hashing
```php
password_hash($password, PASSWORD_DEFAULT);
password_verify($password, $hash);
```

### SQL Injection Prevention
```php
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
```

### XSS Protection
```php
echo htmlspecialchars($data);
```

---

## 🐛 TROUBLESHOOTING

### Cannot Login
- Check database imported
- Verify credentials
- Clear browser cache

### Database Error
- Check DB name matches
- Verify user permissions
- Test connection

### Blank Page
- Enable error display
- Check Apache logs
- Verify file paths

### Slot Not Updating
- Check foreign keys
- Verify booking status
- Refresh page

---

## 📞 QUICK COMMANDS

### Restart Apache (XAMPP)
```
xampp-control.exe
Click "Stop" then "Start" for Apache
```

### Check MySQL Status
```
Open phpMyAdmin
http://localhost/phpmyadmin
```

### Clear Session
```
Close browser completely
Clear cookies
Login again
```

---

## 💡 TIPS & TRICKS

1. **Keyboard Shortcuts**
   - Ctrl/Cmd + K: Focus search
   - ESC: Close modals

2. **Quick Navigation**
   - Use sidebar menu
   - Click logo to go home

3. **Printing**
   - Print button on booking details
   - Browser print (Ctrl+P)

4. **Search**
   - Type in search box
   - Results filter automatically

5. **Modals**
   - Click outside to close
   - ESC key to close

---

## 📊 SAMPLE DATA

### Test Booking
```
Owner: John Doe
Phone: 9876543210
Vehicle: KA01AB1234
Type: Four Wheeler
Slot: B-001
```

### Test Exit
```
Entry: 10:00 AM
Exit: 12:30 PM
Duration: 2.5 hours → 3 hours
Rate: ₹20/hour
Total: ₹60
```

---

## 🔄 BACKUP

### Database Backup
```
1. Open phpMyAdmin
2. Select car_parking_system
3. Click "Export"
4. Choose "Quick" or "Custom"
5. Click "Go"
```

### Files Backup
```
Copy entire "car-parking-system" folder
to safe location
```

---

## 📈 STATISTICS

View in Dashboard:
- Total Slots
- Available Slots
- Occupied Slots
- Today's Bookings
- Today's Revenue
- Active Bookings

---

## 🎯 USER ROLES

### Admin
- Full access
- All features
- User management

### Staff
- View dashboard
- Limited features
- No admin access

---

## 📝 STATUS VALUES

### Booking Status
- `parked` - Vehicle inside
- `exited` - Vehicle left
- `cancelled` - Booking cancelled

### Slot Status
- `available` - Ready to use
- `occupied` - Vehicle parked
- `maintenance` - Under repair

### Payment Status
- `pending` - Not paid
- `paid` - Payment received
- `refunded` - Money returned

---

## 🚦 WORKFLOW

```
1. Vehicle Arrives
   ↓
2. Create Booking
   ↓
3. Assign Slot
   ↓
4. Mark as Parked
   ↓
5. Vehicle Exits
   ↓
6. Calculate Charges
   ↓
7. Process Payment
   ↓
8. Release Slot
```

---

## 📞 SUPPORT CHECKLIST

Before asking for help:
☐ Read documentation
☐ Check installation guide
☐ Verify database imported
☐ Test with default credentials
☐ Check error logs
☐ Clear browser cache
☐ Restart services

---

**Last Updated:** 2025
**Version:** 1.0.0

---

**Need More Help?**
- See README.md for full documentation
- See INSTALLATION.md for setup guide
- See PROJECT_SUMMARY.md for overview
