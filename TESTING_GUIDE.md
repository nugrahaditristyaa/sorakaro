# Quick Start Testing Guide

## Prerequisites
- Database migrated: `php artisan migrate` ✅ (Already done)
- At least one admin user exists with 'admin' role

## 1. Test User Registration with Gender/Age

### Steps:
```bash
# 1. Start the development server (if not running)
php artisan serve

# 2. Visit: http://localhost:8000/register
```

**Test Case:**
- Fill in Name: "Test User"
- Fill in Email: "test@example.com"
- Select Gender: Male or Female (required)
- Fill in Age: 25 (optional, 5-100)
- Fill in Password: "password123"
- Confirm Password: "password123"
- Click "Register"

**Expected Result:**
- User is created successfully
- Redirected to dashboard
- Navigation shows avatar emoji (👨 or 👩) next to user name

---

## 2. Test Profile Update

### Steps:
```bash
# Visit: http://localhost:8000/profile
```

**Test Case:**
- Change Gender from Male to Female (or vice versa)
- Update Age to a different value
- Click "Save"

**Expected Result:**
- Profile updated successfully
- Avatar emoji in navigation updates immediately
- Success message appears

---

## 3. Test Admin User Management

### Steps:
```bash
# 1. Log in as admin user
# 2. Visit: http://localhost:8000/admin
# 3. Click "Users" in the sidebar
```

### 3.1 Create New User

**Test Case:**
- Click "Create" button
- Fill in:
  - Name: "Jane Doe"
  - Email: "jane@example.com"
  - Gender: Female
  - Age: 30
  - Password: "password123"
  - Roles: Select "user"
- Click "Create"

**Expected Result:**
- User created successfully
- Appears in users table
- Gender shows as "👩 Female" badge
- Age shows as "30"
- Role shows as "user" badge

### 3.2 Edit User

**Test Case:**
- Click "Edit" on Jane Doe
- Change:
  - Age to 31
  - Add "admin" role
  - Leave password blank
- Click "Save"

**Expected Result:**
- User updated successfully
- Age updated to 31
- Roles now show both "user" and "admin" badges
- Password remains unchanged (not reset)

### 3.3 Test Self-Deletion Protection

**Test Case:**
- Find your own user account in the list
- Click "Delete" action
- Confirm deletion

**Expected Result:**
- Deletion is blocked
- Notification appears: "Cannot delete yourself"
- User account remains intact

### 3.4 Delete Another User

**Test Case:**
- Click "Delete" on Jane Doe
- Confirm deletion

**Expected Result:**
- User is deleted successfully
- Removed from users table

### 3.5 Test Filters

**Test Case 1 - Gender Filter:**
- Click "Gender" filter dropdown
- Select "Male"
- Apply filter

**Expected Result:**
- Only male users shown in table

**Test Case 2 - Role Filter:**
- Click "Roles" filter dropdown
- Select "admin"
- Apply filter

**Expected Result:**
- Only users with admin role shown

### 3.6 Test Search

**Test Case:**
- Type "test" in search box
- Press Enter

**Expected Result:**
- Only users with "test" in name or email shown

---

## 4. Test Avatar Display

### Desktop Navigation
**Test Case:**
- Log in as user with gender set
- Look at top-right navigation

**Expected Result:**
- Avatar emoji (👨 or 👩) appears before username
- Clicking dropdown shows profile menu

### Mobile Navigation
**Test Case:**
- Resize browser to mobile size (< 640px)
- Click hamburger menu
- Look at user profile section

**Expected Result:**
- Avatar emoji appears next to name and email
- Larger size (text-2xl) for better visibility

---

## 5. Edge Cases to Test

### 5.1 User Without Gender (Existing Users)
**Test Case:**
- If you have existing users from before migration
- Log in as that user
- Check navigation

**Expected Result:**
- Shows default avatar: 👤
- Profile form requires gender to be set on update

### 5.2 Age Validation
**Test Case:**
- Try to enter age < 5 (e.g., 3)
- Try to enter age > 100 (e.g., 150)

**Expected Result:**
- Validation error appears
- Form does not submit

### 5.3 Password Update in Admin
**Test Case 1 - Create with Password:**
- Create new user with password "test123"
- User should be able to log in with "test123"

**Test Case 2 - Edit without Password:**
- Edit user, leave password field blank
- Save
- User should still be able to log in with old password

**Test Case 3 - Edit with New Password:**
- Edit user, enter new password "newpass123"
- Save
- User should now log in with "newpass123"

---

## 6. Database Verification

### Check Users Table
```bash
php artisan tinker
```

```php
// Check all users with gender and age
User::all(['id', 'name', 'email', 'gender', 'age'])->toArray();

// Check user roles
User::with('roles')->find(1);

// Test avatar helper
$user = User::first();
$user->getAvatarIcon(); // Should return emoji
```

---

## Common Issues & Solutions

### Issue: "Column 'gender' cannot be null"
**Solution:** 
- Existing users need to update their profile
- Or update migration to allow null: `->nullable()`

### Issue: "Users menu not visible in admin"
**Solution:**
- Ensure you're logged in as admin
- Check role: `User::find(1)->roles->pluck('name');`
- Assign admin role: `User::find(1)->assignRole('admin');`

### Issue: "Avatar not showing"
**Solution:**
- Clear browser cache
- Check user has gender set
- Verify `getAvatarIcon()` method exists in User model

### Issue: "Cannot delete user - no notification"
**Solution:**
- Check browser console for errors
- Ensure Filament notifications are working
- Try clearing cache: `php artisan cache:clear`

---

## Success Criteria Checklist

- [ ] New users can register with gender (required) and age (optional)
- [ ] Avatar emoji appears in navigation based on gender
- [ ] Users can update gender and age in profile
- [ ] Avatar updates when gender changes
- [ ] Admin can access Users menu in Filament
- [ ] Admin can create users with all fields
- [ ] Admin can edit users (password optional on edit)
- [ ] Admin can assign/remove roles
- [ ] Admin can delete other users
- [ ] Admin cannot delete themselves (protection works)
- [ ] Gender filter works
- [ ] Role filter works
- [ ] Search works (name, email)
- [ ] Gender displays as badge with emoji
- [ ] Age displays correctly (or "—" if null)
- [ ] Roles display as badges

---

## Performance Check

```bash
# Check for any errors
php artisan route:list --path=admin

# Clear all caches
php artisan optimize:clear

# Run tests (if you have any)
php artisan test
```

---

## Next Steps After Testing

1. ✅ Verify all test cases pass
2. ✅ Fix any issues found
3. ✅ Update existing users to set their gender
4. ✅ Consider making gender nullable if needed
5. ✅ Add more roles if needed
6. ✅ Customize Filament theme (optional)
7. ✅ Add user activity logging (optional)
8. ✅ Set up automated tests (optional)

---

## Need Help?

Check the detailed documentation:
- `IMPLEMENTATION_GUIDE.md` - Full implementation details
- `IMPLEMENTATION_SUMMARY.md` - Quick reference summary
