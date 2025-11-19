# Login Issue - Troubleshooting Guide

## Problem
Getting "Invalid username or password" error when trying to login.

## Common Causes & Solutions

### 1. **Password Hash Mismatch** (Most Common)
The password hash in the database might be incorrect or corrupted.

**Solution:**
1. Open your browser and navigate to: `http://localhost/car-parking-system/fix_password.php`
2. This will automatically fix the admin password
3. Try logging in again with:
   - **Username:** admin
   - **Password:** admin123

### 2. **Database Not Imported**
The database tables might not exist.

**Solution:**
1. Open phpMyAdmin (http://localhost/phpmyadmin)
2. Create database `car_parking_system` if it doesn't exist
3. Import the file: `database/car_parking_system.sql`
4. Run `fix_password.php` to ensure correct password

### 3. **Database Connection Issues**
Check if database credentials are correct.

**Solution:**
1. Open `config/config.php`
2. Verify these settings:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');  // Change if you have a password
   define('DB_NAME', 'car_parking_system');
   ```

### 4. **Session Issues**
PHP sessions might not be working properly.

**Solution:**
1. Make sure PHP session support is enabled
2. Clear browser cookies
3. Try in incognito/private mode

## Diagnostic Tools

I've created helpful diagnostic tools for you:

### 1. **diagnose.php** - Complete System Check
- Navigate to: `http://localhost/car-parking-system/diagnose.php`
- This will check:
  - Database connection
  - Table existence
  - Admin user status
  - Password verification
  - PHP configuration

### 2. **fix_password.php** - Password Fixer
- Navigate to: `http://localhost/car-parking-system/fix_password.php`
- Automatically fixes the admin password
- Creates admin user if it doesn't exist

### 3. **test_password.php** - Password Tester
- Navigate to: `http://localhost/car-parking-system/test_password.php`
- Tests password hashing and verification
- Shows detailed debugging information

## Step-by-Step Fix Process

1. **First, run the diagnostic:**
   ```
   http://localhost/car-parking-system/diagnose.php
   ```

2. **If it shows password issues, run the fix:**
   ```
   http://localhost/car-parking-system/fix_password.php
   ```

3. **Try logging in again:**
   ```
   http://localhost/car-parking-system/index.php
   Username: admin
   Password: admin123
   ```

## Manual Database Fix (Alternative Method)

If the automated scripts don't work, you can manually fix the password in phpMyAdmin:

1. Open phpMyAdmin
2. Select `car_parking_system` database
3. Click on `users` table
4. Click "SQL" tab
5. Run this query:
   ```sql
   UPDATE users 
   SET password = '$2y$10$rBBXHwOZN4FLhHYfVQg7E.gVzqAzP7JKcSVMqwqmQWZ4sQQX8d/TW' 
   WHERE username = 'admin';
   ```
6. Try logging in with admin/admin123

## Still Not Working?

If none of the above works:

1. Check PHP error logs
2. Enable error reporting by adding to top of `index.php`:
   ```php
   error_reporting(E_ALL);
   ini_set('display_errors', 1);
   ```
3. Check browser console for JavaScript errors (F12)
4. Make sure Apache and MySQL services are running
5. Try accessing phpMyAdmin to verify MySQL is working

## Default Credentials

After fixing, use these credentials:
- **Username:** admin
- **Password:** admin123

## Quick Check Commands

In phpMyAdmin SQL tab, run these to verify:

```sql
-- Check if database exists
SHOW DATABASES LIKE 'car_parking_system';

-- Check if users table exists
SHOW TABLES LIKE 'users';

-- Check admin user
SELECT id, username, email, role, status FROM users WHERE username = 'admin';

-- Count total users
SELECT COUNT(*) FROM users;
```

## Prevention

To avoid this issue in future:
1. Always keep a backup of your database
2. Don't manually edit password hashes
3. Use the provided admin panel to manage users
4. Document any password changes

---

**Need more help?** Run `diagnose.php` and share the output for detailed troubleshooting.
