# Sorakaro - User Profile & Admin Management Implementation

## Overview
This document outlines the implementation of extended user profiles and admin user management features for the Sorakaro quiz application.

## Features Implemented

### Feature A: Extended User Profile

#### 1. Database Changes
- **Migration**: `2026_01_15_172726_add_gender_and_age_to_users_table.php`
- Added columns to `users` table:
  - `gender` (enum: 'male' | 'female', nullable)
  - `age` (unsigned integer, nullable, range: 5-100)

#### 2. User Model Updates
- **File**: `app/Models/User.php`
- Added `gender` and `age` to `$fillable` array
- Added helper method `getAvatarIcon()` that returns:
  - 👨 for male users
  - 👩 for female users
  - 👤 for users without gender set

#### 3. Profile Form Updates
- **File**: `resources/views/profile/partials/update-profile-information-form.blade.php`
- Added gender selection (radio buttons with emoji icons)
- Added age input field (number input, optional, 5-100 range)

#### 4. Validation Updates
- **File**: `app/Http/Requests/ProfileUpdateRequest.php`
- Gender: required, must be 'male' or 'female'
- Age: optional, integer, min: 5, max: 100

#### 5. Navigation Updates
- **File**: `resources/views/layouts/navigation.blade.php`
- Desktop navigation: Shows gender-based avatar emoji next to user name
- Mobile navigation: Shows gender-based avatar in user profile section

### Feature B: Admin User Management

#### 1. Filament UserResource
- **File**: `app/Filament/Resources/UserResource.php`
- Complete CRUD interface for user management
- Navigation icon: 👥 (users icon)
- Navigation sort: 10 (appears after quiz resources)

#### 2. Form Features
**User Information Section:**
- Name (required)
- Email (required, unique validation)
- Gender (required, dropdown with emoji icons)
- Age (optional, 5-100 range)

**Security Section:**
- Password field
  - Required on create
  - Optional on edit (leave blank to keep current)
  - Automatically hashed using `Hash::make()`

**Roles & Permissions Section:**
- Multi-select dropdown for role assignment
- Integrates with Spatie Permission package
- Preloaded options for better UX

#### 3. Table Features
**Columns:**
- Name (searchable, sortable)
- Email (searchable, sortable)
- Gender (badge with emoji and color coding)
- Age (sortable, shows "—" if not set)
- Roles (badges, comma-separated)
- Created At (toggleable, hidden by default)

**Filters:**
- Filter by gender (male/female)
- Filter by roles (multi-select)

**Actions:**
- Edit user
- Delete user (with self-deletion protection)

#### 4. Security Features
- **Self-Deletion Protection**: Admins cannot delete their own account
  - Implemented in both single delete and bulk delete actions
  - Shows notification if attempted
- **Role-Based Access**: Only users with 'admin' role can access UserResource
  - Configured in `app/Providers/Filament/AdminPanelProvider.php`

## Usage Instructions

### For Users

#### Updating Profile
1. Navigate to Profile page (click your name in top-right menu)
2. Update your information:
   - Name and Email (required)
   - Gender (required): Select Male or Female
   - Age (optional): Enter age between 5-100
3. Click "Save"
4. Your avatar emoji will update in the navigation based on your gender selection

### For Administrators

#### Managing Users
1. Log in to admin panel at `/admin`
2. Click "Users" in the navigation menu
3. View all users with their information

#### Creating a New User
1. Click "Create" button
2. Fill in all required fields:
   - Name, Email, Gender
   - Password (required for new users)
   - Age (optional)
3. Assign roles (admin, user, or both)
4. Click "Create"

#### Editing a User
1. Click "Edit" action on any user row
2. Modify fields as needed
3. Leave password blank to keep current password
4. Update roles if needed
5. Click "Save"

#### Deleting a User
1. Click "Delete" action on any user row
2. Confirm deletion
3. Note: You cannot delete your own account

## Technical Details

### Gender Avatar Icons
The avatar system uses emoji for simplicity and cross-platform compatibility:
- Male: 👨 (U+1F468)
- Female: 👩 (U+1F469)
- Default: 👤 (U+1F464)

### Database Schema
```sql
-- users table additions
ALTER TABLE users ADD COLUMN gender ENUM('male', 'female') NULL AFTER email;
ALTER TABLE users ADD COLUMN age INT UNSIGNED NULL AFTER gender;
```

### Validation Rules
```php
// Profile Update
'gender' => ['required', 'in:male,female'],
'age' => ['nullable', 'integer', 'min:5', 'max:100'],

// Filament UserResource
- Gender: required, select from ['male', 'female']
- Age: nullable, numeric, min: 5, max: 100
- Password: required on create, optional on edit, auto-hashed
```

### Role Management
Uses Spatie Laravel Permission package:
- Roles are assigned via many-to-many relationship
- Multiple roles can be assigned to a single user
- Role changes are synced automatically

## Files Modified/Created

### Created Files
1. `database/migrations/2026_01_15_172726_add_gender_and_age_to_users_table.php`
2. `app/Filament/Resources/UserResource.php`
3. `app/Filament/Resources/UserResource/Pages/CreateUser.php`
4. `app/Filament/Resources/UserResource/Pages/EditUser.php`
5. `app/Filament/Resources/UserResource/Pages/ListUsers.php`

### Modified Files
1. `app/Models/User.php`
2. `app/Http/Requests/ProfileUpdateRequest.php`
3. `resources/views/profile/partials/update-profile-information-form.blade.php`
4. `resources/views/layouts/navigation.blade.php`

## Testing Checklist

### User Profile
- [ ] User can select gender (male/female)
- [ ] User can enter age (validates 5-100 range)
- [ ] Avatar icon updates based on gender selection
- [ ] Avatar appears in desktop navigation
- [ ] Avatar appears in mobile navigation
- [ ] Form validation works correctly
- [ ] Profile updates save successfully

### Admin User Management
- [ ] Admin can access Users menu in Filament
- [ ] Admin can view list of all users
- [ ] Admin can create new user with all fields
- [ ] Admin can edit existing user
- [ ] Password field works correctly (required on create, optional on edit)
- [ ] Admin can assign/remove roles
- [ ] Admin can delete other users
- [ ] Admin cannot delete themselves (protection works)
- [ ] Filters work (gender, roles)
- [ ] Search works (name, email)
- [ ] Gender badges display correctly with emojis

## Future Enhancements (Optional)
- Add profile picture upload
- Add more gender options (non-binary, prefer not to say)
- Add user activity logs
- Add email verification management
- Add user suspension/ban functionality
- Add bulk role assignment
- Add export users to CSV
