# MySQL Workbench Setup Guide for EduAid

## Prerequisites
- MySQL Server installed and running
- MySQL Workbench installed
- XAMPP (optional, if using XAMPP's MySQL)

## Step 1: Create Database in MySQL Workbench

1. **Open MySQL Workbench**
   - Launch MySQL Workbench application
   - Connect to your MySQL server (usually `localhost` or `127.0.0.1`)

2. **Create New Schema**
   - Click on the "Create a new schema" button (or right-click in Schemas panel)
   - Name: `eduaid`
   - Charset: `utf8mb4`
   - Collation: `utf8mb4_unicode_ci`
   - Click "Apply"

   **OR** run this SQL command:
   ```sql
   CREATE DATABASE eduaid CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

3. **Verify Database Creation**
   - You should see `eduaid` in your Schemas list
   - Right-click on it and select "Set as Default Schema"

## Step 2: Configure Laravel .env File

1. **Navigate to backend directory:**
   ```bash
   cd backend
   ```

2. **Copy .env.example to .env (if not already done):**
   ```bash
   copy .env.example .env
   # On Windows PowerShell:
   Copy-Item .env.example .env
   ```

3. **Edit .env file and update database settings:**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=eduaid
   DB_USERNAME=root
   DB_PASSWORD=
   ```

   **Important Notes:**
   - If using XAMPP: `DB_USERNAME=root` and `DB_PASSWORD=` (empty)
   - If using standalone MySQL: Use your MySQL Workbench connection credentials
   - If your MySQL is on a different port, update `DB_PORT`
   - If using a remote MySQL server, update `DB_HOST`

## Step 3: Test Database Connection

1. **Test connection from Laravel:**
   ```bash
   php artisan migrate:status
   ```
   
   If you see no errors, the connection is successful!

2. **If connection fails, check:**
   - MySQL server is running
   - Database `eduaid` exists
   - Username and password are correct
   - Port number matches your MySQL configuration
   - Firewall is not blocking the connection

## Step 4: Run Migrations

1. **Run all migrations:**
   ```bash
   php artisan migrate
   ```

2. **Seed the database:**
   ```bash
   php artisan db:seed
   ```

3. **Verify in MySQL Workbench:**
   - Refresh the `eduaid` schema
   - You should see all tables created:
     - users
     - roles
     - applicants
     - scholarships
     - applications
     - scholars
     - disbursements
     - grades
     - etc.

## Step 5: Verify Default Admin User

After seeding, verify the admin user was created:

```sql
SELECT u.id, u.name, u.email, r.name as role_name 
FROM users u 
LEFT JOIN roles r ON u.role_id = r.id 
WHERE u.email = 'admin@eduaid.gov';
```

You should see:
- Email: admin@eduaid.gov
- Role: Super Admin

## Common Issues and Solutions

### Issue 1: "Access denied for user"
**Solution:**
- Check username and password in .env
- Verify MySQL user has proper permissions
- Try creating a new MySQL user:
  ```sql
  CREATE USER 'eduaid_user'@'localhost' IDENTIFIED BY 'your_password';
  GRANT ALL PRIVILEGES ON eduaid.* TO 'eduaid_user'@'localhost';
  FLUSH PRIVILEGES;
  ```
- Then update .env with new credentials

### Issue 2: "Unknown database 'eduaid'"
**Solution:**
- Make sure you created the database in MySQL Workbench
- Check database name spelling in .env matches exactly

### Issue 3: "Connection refused"
**Solution:**
- Check if MySQL service is running
- Verify port 3306 is correct (check MySQL Workbench connection settings)
- Check firewall settings

### Issue 4: "SQLSTATE[HY000] [2002] No connection could be made"
**Solution:**
- MySQL server might not be running
- Check MySQL service status
- Try restarting MySQL service

## MySQL Workbench Connection Settings Reference

When creating a connection in MySQL Workbench, note these settings:
- **Connection Name:** (any name you prefer)
- **Hostname:** 127.0.0.1 or localhost
- **Port:** 3306 (default)
- **Username:** root (or your MySQL username)
- **Password:** (your MySQL password, or empty for XAMPP default)

Use these same values in your Laravel .env file.

## Next Steps

After successful database setup:
1. Start Laravel server: `php artisan serve`
2. Start frontend: `cd ../frontend && npm run dev`
3. Access the application and login with:
   - Email: admin@eduaid.gov
   - Password: password

## Useful MySQL Workbench Queries

**View all tables:**
```sql
SHOW TABLES;
```

**View table structure:**
```sql
DESCRIBE users;
```

**Count records in a table:**
```sql
SELECT COUNT(*) FROM users;
```

**View all roles:**
```sql
SELECT * FROM roles;
```

**View all users with roles:**
```sql
SELECT u.id, u.name, u.email, r.name as role 
FROM users u 
LEFT JOIN roles r ON u.role_id = r.id;
```
