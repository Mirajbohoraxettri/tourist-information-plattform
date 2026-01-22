# Quick Reference - TGuide Authentication

## 🔐 LOGIN FLOW

```
User visits any page
    ↓
Auth_check.php runs
    ↓
Session exists? → YES → Allow access
    ↓ NO
Redirect to signin.php
    ↓
User enters credentials
    ↓
Password correct? → YES → Create session & redirect
    ↓ NO
Show error (attempt counter)
```

---

## 📝 HOW TO PROTECT A NEW PAGE

1. Add this line at the very top of your PHP file (before any HTML):
```php
<?php
require_once 'auth_check.php';
// Rest of your code...
?>
```

2. That's it! The page is now protected.

---

## 🛡️ SECURITY FEATURES AT A GLANCE

### During Login
- ✅ Checks CSRF token
- ✅ Validates email format
- ✅ Verifies password with hash
- ✅ Tracks failed attempts
- ✅ Locks account after 5 failed attempts (15 min)
- ✅ Regenerates session ID
- ✅ Records login time and last activity

### While Logged In
- ✅ Checks session every 30 minutes
- ✅ Logs out if inactive > 30 minutes
- ✅ Updates last activity timestamp
- ✅ Maintains secure HTTPOnly cookies
- ✅ Validates all form submissions with CSRF tokens

### During Logout
- ✅ Clears all session variables
- ✅ Destroys session cookie
- ✅ Redirects to signin page

---

## 🚨 ERROR MESSAGES & MEANINGS

| Error | Meaning | Action |
|-------|---------|--------|
| "Too many login attempts" | Tried login 5+ times | Wait 15 minutes |
| "Invalid email format" | Email not valid | Use proper email |
| "Incorrect password" | Wrong password | Try again |
| "No account found" | Email not registered | Register first |
| "Passwords do not match" | Confirm password differs | Re-enter same password |
| "Email already registered" | Email exists | Use different email |
| "Session has expired" | Inactive > 30 minutes | Login again |
| "Security token failed" | Page refreshed oddly | Try again |

---

## 📂 FILE LOCATIONS

| File | Purpose | Protection |
|------|---------|-----------|
| signin.php | User login page | ❌ Public |
| register.php | User registration | ❌ Public |
| homepage.php | Landing page | ✅ Protected |
| mainpage.php | Destinations list | ✅ Protected |
| gallery.php | Gallery view | ✅ Protected |
| feedback.php | Feedback form | ✅ Protected |
| logout.php | Logout handler | ✅ Protected |
| admin-login.php | Admin login | ❌ Public |
| admin-dashboard.php | Admin area | ✅ Admin Protected |

---

## 🔑 SESSION VARIABLES

After login, users have:
```php
$_SESSION['user_id']       // User ID from database
$_SESSION['user_name']     // User's full name
$_SESSION['user_email']    // User's email
$_SESSION['last_activity'] // Timestamp of last action
$_SESSION['login_time']    // Timestamp of login
$_SESSION['csrf_token']    // Security token
```

---

## 🧪 TEST CASES

### Test 1: Access Without Login
```
1. Clear browser cookies
2. Try to visit mainpage.php
3. Should redirect to signin.php ✓
```

### Test 2: Register & Login
```
1. Go to register.php
2. Fill form (Name, Email, Password)
3. Click Sign up
4. Go to signin.php
5. Login with registered email & password
6. Should see homepage.php ✓
```

### Test 3: Brute Force Protection
```
1. Try to login with wrong password 5 times
2. 6th attempt should show lockout message ✓
3. Wait 15 minutes or try different user
```

### Test 4: Session Timeout
```
1. Login to website
2. Wait 30 minutes without activity
3. Try to access any page
4. Should redirect to signin.php ✓
```

### Test 5: Logout
```
1. Login to website
2. Click Logout button
3. Session should be cleared
4. Try to access protected page
5. Should redirect to signin.php ✓
```

---

## ⚡ QUICK COMMANDS

### To check if user is logged in (in your code):
```php
if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
    // User is logged in
} else {
    // User is not logged in
}
```

### To get logged-in user's name:
```php
echo $_SESSION['user_name'];
```

### To get logged-in user's email:
```php
echo $_SESSION['user_email'];
```

### To logout programmatically:
```php
header('Location: logout.php');
exit;
```

---

## 📊 PASSWORD REQUIREMENTS

- ✅ Minimum 8 characters
- ✅ Can contain letters, numbers, special characters
- ✅ Case-sensitive
- ✅ Example valid password: `MyPass123!`

---

## 📧 EMAIL REQUIREMENTS

- ✅ Must be valid email format (user@domain.com)
- ✅ Must be unique (not already registered)
- ✅ Can contain letters, numbers, dots, hyphens
- ✅ Example valid email: `john.doe@example.com`

---

## 🔧 CONFIGURATION REFERENCE

| Setting | Location | Current Value | Description |
|---------|----------|----------------|-------------|
| Timeout | auth_check.php:20 | 1800 seconds | Session inactivity timeout |
| Max Attempts | signin.php:8 | 5 attempts | Failed login attempts before lockout |
| Lockout Time | signin.php:9 | 900 seconds | Duration of login lockout |
| Password Cost | register.php:45 | 12 | BCRYPT hashing strength |

---

## 🎯 COMMON TASKS

### Change Session Timeout
Edit `auth_check.php` line 20:
```php
$timeout_duration = 3600; // 1 hour instead of 30 min
```

### Change Lockout Duration
Edit `signin.php` line 9:
```php
$lockout_time = 1800; // 30 minutes instead of 15 min
```

### Change Password Requirements
Edit `register.php` line 41:
```php
elseif (strlen($password) < 10) { // Require 10 chars instead of 8
```

### Add User to Session Display
Edit `homepage.php` (or any page) to show:
```php
<?php echo htmlspecialchars($_SESSION['user_name']); ?>
```

---

## ✅ VERIFICATION CHECKLIST

- [ ] Cannot access mainpage.php without login
- [ ] Cannot access gallery.php without login
- [ ] Cannot access feedback.php without login
- [ ] Registration form works with valid data
- [ ] Login works with correct credentials
- [ ] Wrong password shows error
- [ ] 5 failed attempts trigger lockout
- [ ] Logout clears session
- [ ] Logout redirects to signin.php
- [ ] Admin login works separately
- [ ] Admin pages require admin login
- [ ] CSRF tokens on all forms
- [ ] Error messages don't leak info

---

**Last Updated**: January 15, 2026
**Status**: ✅ Active & Secure
