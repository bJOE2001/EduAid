# EduAid Setup Guide

## Quick Start

### Prerequisites
- PHP 8.2+
- Composer
- Node.js 18+
- MySQL 8.0+
- XAMPP (for Windows) or equivalent

### Backend Setup

1. **Navigate to backend directory:**
   ```bash
   cd backend
   ```

2. **Install dependencies:**
   ```bash
   composer install
   ```

3. **Configure environment:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Update `.env` file with your database credentials:**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=eduaid
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Run migrations and seeders:**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Create storage link:**
   ```bash
   php artisan storage:link
   ```

7. **Start the server:**
   ```bash
   php artisan serve
   ```

   Backend will run on `http://localhost:8000`

### Frontend Setup

1. **Navigate to frontend directory:**
   ```bash
   cd frontend
   ```

2. **Install dependencies:**
   ```bash
   npm install
   ```

3. **Start development server:**
   ```bash
   npm run dev
   ```

   Frontend will run on `http://localhost:9000`

## Default Login Credentials

After running seeders:
- **Email**: admin@eduaid.gov
- **Password**: password
- **Role**: Super Admin

## Database Structure

The system includes the following main tables:
- `users` - System users
- `roles` - User roles and permissions
- `applicants` - Applicant profiles
- `scholarships` - Scholarship programs
- `applications` - Scholarship applications
- `scholars` - Approved scholars
- `disbursements` - Fund disbursements
- `grades` - Academic grades
- `screenings` - Screening sessions
- `screening_scores` - Application scores

## API Endpoints

All API endpoints are prefixed with `/api` and require authentication (except login/register).

### Authentication
- `POST /api/register` - Register new user
- `POST /api/login` - Login
- `POST /api/logout` - Logout
- `GET /api/me` - Get current user

### Scholarships
- `GET /api/scholarships` - List all scholarships
- `POST /api/scholarships` - Create scholarship (Admin/Staff)
- `GET /api/scholarships/{id}` - Get scholarship details
- `PUT /api/scholarships/{id}` - Update scholarship (Admin/Staff)
- `DELETE /api/scholarships/{id}` - Delete scholarship (Admin)

### Applications
- `GET /api/applications` - List applications
- `POST /api/applications` - Submit application
- `GET /api/applications/{id}` - Get application details
- `PUT /api/applications/{id}` - Update application status (Admin/Staff)

### Reports
- `GET /api/reports/dashboard` - Dashboard statistics
- `GET /api/reports/applicants` - Applicants report
- `GET /api/reports/scholars` - Scholars report
- `GET /api/reports/financial` - Financial report

## User Roles

1. **Super Admin** (`admin`) - Full system access
2. **Scholarship Admin/Staff** (`staff`) - Manage scholarships and applications
3. **Screening Committee** (`committee`) - Screen and score applications
4. **Accounting Officer** (`accounting`) - Manage disbursements
5. **Applicant/Scholar** (`applicant`) - Apply and manage profile
6. **Government Official** (`viewer`) - View reports only

## Development Notes

### Backend
- Uses Laravel 12 with Sanctum for API authentication
- Role-based access control via middleware
- Soft deletes enabled on most models
- File uploads stored in `storage/app/public`

### Frontend
- Vue 3 with Composition API
- Quasar Framework for UI components
- Pinia for state management
- Axios for API calls
- Router guards for authentication

## Troubleshooting

### Backend Issues

1. **Migration errors:**
   - Ensure database exists
   - Check `.env` database credentials
   - Run `php artisan migrate:fresh` (WARNING: deletes all data)

2. **Permission errors:**
   - Ensure `storage` and `bootstrap/cache` are writable
   - Run `chmod -R 775 storage bootstrap/cache` (Linux/Mac)

3. **CORS errors:**
   - Check `config/cors.php` settings
   - Ensure frontend URL is in allowed origins

### Frontend Issues

1. **API connection errors:**
   - Verify backend is running on `http://localhost:8000`
   - Check `quasar.config.js` API_URL configuration
   - Check browser console for CORS errors

2. **Build errors:**
   - Delete `node_modules` and `package-lock.json`
   - Run `npm install` again
   - Check Node.js version (requires 18+)

## Next Steps

1. Complete remaining frontend pages (see TODO list)
2. Add email notifications
3. Implement file upload validation
4. Add audit logging
5. Create comprehensive tests
6. Set up production environment

## Support

For issues or questions, please refer to the main README.md or create an issue in the repository.
