# TGuide Website - Enhanced Authentication & Security

## Overview
This document outlines the security improvements implemented to protect the TGuide website and enforce user login requirements.

---

## 🔐 Authentication Improvements Made

### 1. **Session-Based Authentication**
- **Auth Check File** (`auth_check.php`): Central authentication validation for all user-facing pages
- Automatically redirects unauthenticated users to login page
- Implements session timeout (30 minutes of inactivity)
- Secure cookie parameters with HTTPOnly flag

### 2. **Protected Pages**
The following pages now require user login:
- `homepage.php` - Landing page after login
- `mainpage.php` - Main destination listings
- `destination.php` - Detailed destination information
- `destination-details.php` - Specific destination details
- `gallery.php` - Gallery view (converted from HTML)
- `feedback.php` - Feedback submission
- `search.php` - Search functionality

### 3. **Enhanced Sign-In (`signin.php`)**
✅ **Security Features:**
- CSRF (Cross-Site Request Forgery) token protection
- Rate limiting: Maximum 5 failed attempts per 15 minutes
- Session regeneration on successful login
- Password verification using `password_verify()`
- Input validation and HTML escaping
- Secure error messages (no information leakage)
- Session timeout tracking
- Redirect support (remembers where user was going)

✅ **User Experience:**
- Clear error/success messages
- Password visibility toggle
- Automatic form persistence

### 4. **Enhanced Registration (`register.php`)**
✅ **Security Features:**
- CSRF token protection
- Session start and redirect if already logged in
- Strong password requirements (minimum 8 characters)
- Password confirmation matching
- Email uniqueness validation
- Enhanced password hashing with BCRYPT (cost: 12)
- Input sanitization

✅ **Validation:**
- Full name length minimum (2 characters)
- Email format validation with regex
- Password strength requirements

### 5. **Improved Logout (`logout.php`)**
- Complete session variable clearing
- Proper session cookie destruction
- Secure redirect to signin page
- Session state confirmation

---

## 🛡️ Admin Panel Security

### Admin Authentication (`admin/admin-login.php`)
- CSRF token protection
- Rate limiting for failed attempts
- Session regeneration
- Admin session tracking

### Admin Protected Pages
All admin files now require authentication:
- `admin/admin-dashboard.php` - Dashboard with statistics
- `admin/add-destination.php` - Add new destination
- `admin/edit-destination.php` - Edit existing destination
- `admin/delete-destination.php` - Delete destination
- `admin/destination-crud.php` - Destination management
- `admin/users-crud.php` - User management

### Admin Auth Check (`admin/admin_auth_check.php`)
- Dedicated authentication check for admin pages
- Session timeout (30 minutes)
- Secure cookie parameters

### Admin Logout (`admin/admin-logout.php`)
- Proper session cleanup
- Session cookie destruction

---

## 📋 How to Use

### For Users
1. **First Time**: Go to `signin.php` to create account or click "Sign up" link
2. **Sign Up**: Fill registration form with:
   - Full Name (2+ characters)
   - Email (valid format)
   - Password (minimum 8 characters)
   - Confirm Password
3. **Sign In**: Use email and password credentials
4. **Access Pages**: All pages automatically check if logged in
5. **Logout**: Click "Logout" button to safely end session

### For Admins
1. Navigate to `admin/admin-login.php`
2. Enter admin credentials
3. Manage destinations, view users, and feedback from dashboard
4. Click "Logout" to end admin session

---

## 🚨 Security Features Summary

| Feature | Status | Details |
|---------|--------|---------|
| CSRF Protection | ✅ | Token validation on all forms |
| Rate Limiting | ✅ | 5 attempts per 15 minutes |
| Session Timeout | ✅ | 30 minutes inactivity |
| Password Hashing | ✅ | BCRYPT with cost 12 |
| Input Validation | ✅ | Email, length, format checks |
| SQL Injection Prevention | ✅ | Prepared statements used |
| XSS Prevention | ✅ | HTML escaping on output |
| HTTPOnly Cookies | ✅ | Session cookies secure |
| Session Regeneration | ✅ | On login/logout |

---

## 🔄 Session Management

### Session Variables Set on Login
```php
$_SESSION['user_id']       // Unique user identifier
$_SESSION['user_name']     // User's full name
$_SESSION['user_email']    // User's email
$_SESSION['last_activity'] // Last activity timestamp
$_SESSION['login_time']    // Login timestamp
$_SESSION['csrf_token']    // CSRF protection token
```

### Session Variables for Admins
```php
$_SESSION['admin']         // Admin username
$_SESSION['admin_id']      // Admin ID
$_SESSION['last_activity'] // Last activity timestamp
$_SESSION['login_time']    // Login timestamp
```

---

## 🔧 Configuration

### Session Timeout
Default: **30 minutes** of inactivity
- Location: `auth_check.php` line 20 and `admin/admin_auth_check.php`
- To modify: Change `$timeout_duration` value (in seconds)

### Rate Limiting
Default: **5 failed attempts per 15 minutes**
- Location: `signin.php` line 8-9 and `admin/admin-login.php` line 7-8
- To modify: Change `$max_attempts` and `$lockout_time` values

### Cookie Security
Default: HTTPOnly enabled, secure flag disabled (enable for HTTPS)
- Location: `auth_check.php` lines 29-34
- Set `'secure' => true` when using HTTPS

---

## 📝 Database Considerations

### Users Table Requirements
```sql
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    fullname VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Admins Table Requirements
```sql
CREATE TABLE admins (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

**Note**: Admin passwords should be updated to use BCRYPT hashing instead of SHA256 for better security.

---

## ⚠️ Recommendations for Future Improvements

1. **Two-Factor Authentication (2FA)** - Add email or SMS verification
2. **Password Reset** - Implement secure password recovery flow
3. **Activity Logging** - Log all admin actions for audit trail
4. **HTTPS Enforcement** - Require HTTPS for all pages
5. **Password Expiration** - Force password change periodically
6. **Admin Password Hashing** - Update to BCRYPT instead of SHA256
7. **Email Verification** - Verify email during registration
8. **Account Lockout** - Lock suspicious accounts
9. **Login History** - Track login attempts and IP addresses
10. **Automated Backups** - Regular database backups

---

## 🔗 File References

### Core Authentication Files
- `auth_check.php` - User authentication check
- `admin/admin_auth_check.php` - Admin authentication check
- `signin.php` - User login page
- `register.php` - User registration page
- `logout.php` - User logout handler
- `admin/admin-login.php` - Admin login page
- `admin/admin-logout.php` - Admin logout handler

### Protected User Pages
- `homepage.php` - Home page
- `mainpage.php` - Main page
- `destination.php` - Destinations
- `destination-details.php` - Destination details
- `gallery.php` - Gallery
- `feedback.php` - Feedback
- `search.php` - Search

### Protected Admin Pages
- `admin/admin-dashboard.php` - Admin dashboard
- `admin/add-destination.php` - Add destination
- `admin/edit-destination.php` - Edit destination
- `admin/delete-destination.php` - Delete destination
- `admin/destination-crud.php` - Destination CRUD
- `admin/users-crud.php` - Users CRUD

---

## ✅ Testing Checklist

- [ ] Test user registration with valid/invalid inputs
- [ ] Test login with correct/incorrect credentials
- [ ] Test 5-attempt lockout mechanism
- [ ] Test session timeout after 30 minutes inactivity
- [ ] Test CSRF token validation
- [ ] Test logout functionality
- [ ] Test page access without login (should redirect)
- [ ] Test admin login and dashboard
- [ ] Test admin page access restrictions
- [ ] Test admin logout
- [ ] Verify email validation rules
- [ ] Verify password requirements
- [ ] Test redirect after login

---

**Last Updated**: January 15, 2026
**Version**: 1.0 - Enhanced Security
