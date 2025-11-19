# 🛠️ Support & Contact System - Setup Guide

## Overview
A comprehensive support and bug reporting system that allows customers to contact you via Email, WhatsApp, and Phone when they find bugs or need help.

## Features

### ✨ Key Features
1. **System Admin Role** - Special admin with full control
2. **Support Contact Management** - Configure your email, phone, and WhatsApp
3. **Bug Reporting System** - Customers can report bugs with detailed forms
4. **Auto Email Templates** - Gmail opens with pre-filled bug report template
5. **WhatsApp Integration** - Direct WhatsApp chat link
6. **Phone Support** - Click-to-call functionality
7. **Bug Tracking Dashboard** - Track and manage all reported bugs
8. **Priority Management** - Low, Medium, High, Critical priorities
9. **Status Tracking** - New, In Progress, Resolved, Closed

---

## 🚀 Installation

### Step 1: Run SQL Script
```sql
-- Import this file in phpMyAdmin
database/add_support_system.sql
```

This will:
- ✅ Add `system_admin` role to users table
- ✅ Create `support_contacts` table
- ✅ Create `bug_reports` table
- ✅ Add default system admin user
- ✅ Add support settings

### Step 2: Login as System Admin
```
Username: sysadmin
Password: sysadmin123
```

**⚠️ IMPORTANT:** Change this password immediately after first login!

### Step 3: Configure Support Contact
1. Login as system admin
2. Go to **Support Settings** (in sidebar)
3. Update your information:
   - Support Name
   - Company Name
   - Support Email
   - Support Phone
   - WhatsApp Number
4. Click **Save**

---

## 👥 User Roles

### System Admin (You)
- **Access Level**: Highest
- **Username**: `sysadmin` (default)
- **Can Do**:
  - ✅ Configure support contact details
  - ✅ View and manage all bug reports
  - ✅ Delete bug reports
  - ✅ All regular admin features
  - ✅ Settings management

### Regular Admin (Staff)
- **Access Level**: Medium
- **Can Do**:
  - ✅ View bug reports
  - ✅ Update bug status
  - ✅ Add admin notes
  - ❌ Cannot change support settings
  - ❌ Cannot delete bug reports

### Customers (End Users)
- **Can Do**:
  - ✅ Report bugs
  - ✅ Contact support via Email/WhatsApp/Phone
  - ✅ Fill bug report forms
  - ❌ Cannot see other users' reports

---

## 📧 Email Template

When customer clicks "Send Email", Gmail opens with:

**Subject**: Bug Report - Parking System

**Body**:
```
Hi [Your Name],

I found an issue with the parking system:

Bug Title: [Describe the issue briefly]

Description:
[Provide detailed description]

Page/Section: [Where did you find the bug?]

Steps to Reproduce:
1. 
2. 
3. 

Expected Result: [What should happen?]
Actual Result: [What actually happened?]

Thanks!
```

---

## 💬 WhatsApp Integration

When customer clicks "Chat on WhatsApp":
```
Opens WhatsApp with pre-filled message:
"Hi [Your Name], I found a bug in the parking system. Can you help?"
```

**Format**: `https://wa.me/[YourNumber]?text=[Message]`

**Example**: 
- Your WhatsApp: +919876543210
- URL: `https://wa.me/919876543210?text=Hi%20Support...`

---

## 📱 Phone Support

Click-to-call functionality:
```html
<a href="tel:+1234567890">Call Now</a>
```

Works on:
- ✅ Mobile devices
- ✅ Desktop with VoIP apps
- ✅ Skype integration

---

## 🐛 Bug Report Form

### Customer Fills:
1. **Your Information**
   - Name
   - Email (required)
   - Phone (optional)

2. **Bug Details**
   - Bug Title (required)
   - Detailed Description (required)
   - Bug Type (Bug/Error/Feature/Question/Other)
   - Priority (Low/Medium/High/Critical)
   - Page URL (auto-filled)

3. **Auto-Captured**
   - Browser information
   - Timestamp
   - User ID (if logged in)

---

## 📊 Bug Reports Dashboard

### Access
```
Admin Panel → Bug Reports
```

### Features
- ✅ View all reported bugs
- ✅ Filter by status (New/In Progress/Resolved/Closed)
- ✅ Filter by priority (Low/Medium/High/Critical)
- ✅ Update bug status
- ✅ Add admin notes
- ✅ Track resolution
- ✅ Statistics dashboard

### Statistics Shown
- Total Reports
- New Reports
- In Progress
- Resolved
- Critical (unresolved)

---

## ⚙️ Support Settings Page

### Access
```
Admin Panel → Support Settings
(Only visible to System Admin)
```

### Configure
1. **Support Name** - Your name or team name
2. **Company Name** - Your company (optional)
3. **Support Email** - Where bugs are sent
4. **Support Phone** - Your contact number
5. **WhatsApp Number** - With country code
6. **Enable/Disable** - Toggle support availability

### Live Preview
Shows how your contact details will appear to customers.

---

## 🌐 Customer Access

### Contact Support Page
```
URL: http://localhost/car-parking-system/contact-support.php
```

### What Customers See
1. **Three Contact Cards**
   - 📧 Email Support
   - 💬 WhatsApp
   - 📱 Phone

2. **Bug Report Form**
   - Detailed bug submission
   - All fields visible
   - Easy to fill

3. **Footer Link**
   - "Contact Support" in footer
   - Available on all pages

---

## 📝 Usage Examples

### Example 1: Customer Found a Bug

**Customer Actions:**
1. Clicks "Contact Support" in footer
2. Sees your contact options
3. Clicks "Send Email"
4. Gmail opens with template
5. Fills details and sends

**OR**

1. Fills bug report form
2. Submits directly
3. Gets confirmation message

**Your Side:**
1. Receive email notification
2. Check "Bug Reports" in admin
3. See full details
4. Update status to "In Progress"
5. Add admin notes
6. Mark as "Resolved" when fixed

### Example 2: Critical Issue

**Customer:**
1. Goes to Contact Support
2. Fills form with "Critical" priority
3. Submits

**You:**
1. See red "Critical" badge in dashboard
2. Get email notification
3. Contact customer via WhatsApp
4. Fix issue quickly
5. Update status to "Resolved"

---

## 🔧 Configuration Files

### Database Tables

**support_contacts**
```sql
- id
- support_email
- support_phone
- whatsapp_number
- support_name
- company_name
- is_active
- updated_by
- updated_at
```

**bug_reports**
```sql
- id
- user_id (optional)
- user_name
- user_email
- user_phone
- bug_title
- bug_description
- bug_type
- priority
- status
- page_url
- browser_info
- admin_notes
- resolved_by
- resolved_at
- created_at
- updated_at
```

---

## 🎨 Customization

### Change Email Template
Edit: `contact-support.php` line with `mailto:` link

### Change WhatsApp Message
Edit: `contact-support.php` WhatsApp URL text parameter

### Add More Bug Types
Edit: `contact-support.php` and `bug-reports.php` select options

### Change Colors/Styling
Modify inline styles in `contact-support.php`

---

## 🔒 Security Features

1. **SQL Injection Protection** - Prepared statements
2. **XSS Protection** - htmlspecialchars() on all outputs
3. **Input Sanitization** - sanitize() function
4. **Role-Based Access** - System Admin only for settings
5. **Activity Logging** - All actions logged
6. **Email Validation** - Valid email required

---

## 📱 Mobile Responsive

All pages are mobile-friendly:
- ✅ Touch-friendly buttons
- ✅ Responsive grid layouts
- ✅ Easy form filling
- ✅ Click-to-call works
- ✅ WhatsApp opens in app

---

## 🧪 Testing Checklist

### As System Admin
- [ ] Login with sysadmin/sysadmin123
- [ ] Access Support Settings
- [ ] Update contact information
- [ ] Save and verify

### As Customer
- [ ] Visit contact-support.php
- [ ] See three contact cards
- [ ] Click Email - Gmail opens
- [ ] Click WhatsApp - Opens chat
- [ ] Fill bug report form
- [ ] Submit successfully

### As Admin
- [ ] View bug reports dashboard
- [ ] See statistics
- [ ] Filter by status/priority
- [ ] Update bug status
- [ ] Add admin notes
- [ ] Mark as resolved

---

## 🚨 Troubleshooting

### Support Settings Not Visible?
- Check if logged in as system_admin
- Regular admins cannot see this page

### Email Link Not Working?
- Check if default email client is set
- Try manually copying email address

### WhatsApp Not Opening?
- Ensure WhatsApp is installed
- Check number format (+countrycode without spaces)
- Try on mobile device

### Bug Reports Not Saving?
- Check database connection
- Verify bug_reports table exists
- Check PHP error logs

---

## 📞 Contact Information Format

### Phone Numbers
✅ **Correct Format:**
```
+1234567890
+91 9876543210
+971-50-1234567
```

❌ **Incorrect:**
```
1234567890 (missing +)
(123) 456-7890 (for WhatsApp)
```

### Email
✅ **Valid:**
```
support@company.com
help@parking.com
admin@system.com
```

---

## 🎯 Best Practices

1. **Check Bug Reports Daily**
   - Review new submissions
   - Respond quickly to critical issues
   - Update status regularly

2. **Keep Contact Info Updated**
   - Change if phone number changes
   - Update email if needed
   - Test WhatsApp link periodically

3. **Respond to Customers**
   - Acknowledge bug reports
   - Provide updates
   - Close resolved issues

4. **Use Admin Notes**
   - Document fixes
   - Note solutions
   - Track changes

5. **Change Default Password**
   - System admin default password
   - Use strong passwords
   - Update regularly

---

## 📚 File Structure

```
admin/
├── support-settings.php (System Admin only)
└── bug-reports.php (All admins)

database/
└── add_support_system.sql (Installation)

contact-support.php (Public access)
```

---

## 🎉 Success!

Your parking system now has:
- ✅ Professional support system
- ✅ Easy customer contact
- ✅ Bug tracking dashboard
- ✅ Email/WhatsApp/Phone support
- ✅ System admin control

**Ready to support your customers!** 🚀

---

**Need Help?**
Use the contact support page you just created! 😄
