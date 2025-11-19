# INSTALLATION GUIDE
# Car Parking Management System

## Quick Start Guide

Follow these steps to install and run the Car Parking Management System:

### Prerequisites
✓ XAMPP/WAMP/LAMP installed
✓ PHP 7.4 or higher
✓ MySQL 5.7 or higher
✓ Web browser

---

## STEP-BY-STEP INSTALLATION

### STEP 1: Start Web Server
1. Start XAMPP Control Panel
2. Start "Apache" service
3. Start "MySQL" service
4. Wait until both show "Running" status

### STEP 2: Copy Project Files
Copy the "car-parking-system" folder to:
- For XAMPP: C:\xampp\htdocs\
- For WAMP: C:\wamp64\www\

Final path should be: C:\xampp\htdocs\car-parking-system\

### STEP 3: Create Database
1. Open browser and go to: http://localhost/phpmyadmin
2. Click "New" to create a new database
3. Database name: car_parking_system
4. Collation: utf8_general_ci
5. Click "Create"

### STEP 4: Import Database
1. Click on "car_parking_system" database from the left panel
2. Click on "Import" tab at the top
3. Click "Choose File" button
4. Navigate to: car-parking-system\database\car_parking_system.sql
5. Click "Go" button at the bottom
6. Wait for success message

### STEP 5: Configure Application (Optional)
Only if you changed database credentials:

1. Open: car-parking-system\config\config.php
2. Update these lines if needed:
   ```php
   define('DB_HOST', 'localhost');     // Usually localhost
   define('DB_USER', 'root');           // Usually root
   define('DB_PASS', '');               // Usually empty
   define('DB_NAME', 'car_parking_system');
   ```

### STEP 6: Access Application
1. Open browser
2. Go to: http://localhost/car-parking-system/
3. You should see the login page

### STEP 7: Login
Use these default credentials:
- Username: admin
- Password: admin123

---

## VERIFICATION CHECKLIST

After installation, verify:

[ ] Database "car_parking_system" is created
[ ] All tables are imported (8 tables total)
[ ] Login page loads without errors
[ ] Can login with admin credentials
[ ] Dashboard displays correctly
[ ] Can navigate through menu items

---

## COMMON ISSUES & SOLUTIONS

### Issue 1: "Access Denied" Error
**Solution:**
- Check database credentials in config/config.php
- Default MySQL username is "root"
- Default password is empty (no password)

### Issue 2: "Page Not Found" Error
**Solution:**
- Verify folder location: C:\xampp\htdocs\car-parking-system\
- Check URL: http://localhost/car-parking-system/
- Make sure Apache is running

### Issue 3: "Database Not Found" Error
**Solution:**
- Create database named "car_parking_system"
- Import the SQL file from database folder
- Refresh the page

### Issue 4: Blank White Page
**Solution:**
- Check if Apache and MySQL are running
- Enable error display in PHP
- Check Apache error logs

### Issue 5: Cannot Import SQL File
**Solution:**
- Check file size limit in phpMyAdmin
- Make sure database is selected before importing
- Try importing via command line

---

## TESTING THE SYSTEM

After successful installation, test these features:

1. **Login System**
   - Login with admin credentials
   - Logout and login again

2. **Parking Slots**
   - Go to "Parking Slots"
   - View existing slots
   - Try adding a new slot

3. **New Booking**
   - Go to "New Booking"
   - Fill in vehicle details
   - Create a test booking

4. **View Bookings**
   - Go to "All Bookings"
   - See the created booking
   - Try searching for it

5. **Exit Vehicle**
   - Click "Exit" on active booking
   - Process the exit
   - Check if charge is calculated

6. **Reports**
   - Go to "Reports"
   - Check today's statistics

---

## DEFAULT DATA

After installation, the system includes:

**Users:**
- Admin user (username: admin, password: admin123)

**Parking Slots:**
- 15 sample parking slots (A-001 to D-002)
- Mix of two-wheeler and four-wheeler slots
- All initially available

**Vehicle Categories:**
- Bicycle: ₹5/hour
- Motorcycle: ₹10/hour
- Car: ₹20/hour
- SUV: ₹30/hour

---

## SECURITY RECOMMENDATIONS

After installation:

1. **Change Default Password**
   - Go to Users section
   - Change admin password

2. **Remove Default Data** (Optional)
   - Delete sample parking slots
   - Add your actual slots

3. **Update Configuration**
   - Set correct site URL
   - Configure timezone
   - Set parking rates

---

## FOLDER PERMISSIONS

Ensure proper permissions:
- Read permission for all files
- Write permission for upload folders (if added)
- Execute permission for PHP files

---

## TROUBLESHOOTING COMMANDS

### Check PHP Version
```bash
php -v
```

### Check MySQL Service
```bash
mysql --version
```

### Test Database Connection
Try accessing: http://localhost/phpmyadmin

---

## NEED HELP?

If you encounter issues:

1. Check Apache error logs:
   - XAMPP: C:\xampp\apache\logs\error.log

2. Check PHP errors:
   - Enable error display in php.ini

3. Check MySQL logs:
   - XAMPP: C:\xampp\mysql\data\

4. Verify file paths are correct

5. Ensure all services are running

---

## SYSTEM REQUIREMENTS MET?

✓ PHP 7.4+ installed
✓ MySQL 5.7+ installed
✓ Apache web server running
✓ At least 50MB disk space
✓ Modern web browser

---

## NEXT STEPS

After successful installation:

1. Read the README.md file
2. Explore the admin dashboard
3. Add your parking slots
4. Create test bookings
5. Customize settings
6. Add staff users

---

## CONTACT & SUPPORT

For issues not covered in this guide:
- Check README.md for detailed documentation
- Review code comments in PHP files
- Check database structure

---

**Installation Complete! Enjoy using the Car Parking Management System!**

Last Updated: 2025
Version: 1.0.0
