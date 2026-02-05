# EduAid API Documentation

## Base URL
```
http://localhost:8000/api
```

## Authentication

All protected endpoints require authentication via Bearer token.

### Register
```http
POST /api/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

### Login
```http
POST /api/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password123"
}
```

Response:
```json
{
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "role": {
      "id": 5,
      "name": "Applicant / Scholar",
      "slug": "applicant"
    }
  },
  "token": "1|xxxxxxxxxxxx"
}
```

### Logout
```http
POST /api/logout
Authorization: Bearer {token}
```

### Get Current User
```http
GET /api/me
Authorization: Bearer {token}
```

## Scholarships

### List Scholarships
```http
GET /api/scholarships?is_active=1&type=merit&search=keyword
Authorization: Bearer {token}
```

### Get Scholarship
```http
GET /api/scholarships/{id}
Authorization: Bearer {token}
```

### Create Scholarship (Admin/Staff)
```http
POST /api/scholarships
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Merit Scholarship 2024",
  "description": "Scholarship for outstanding students",
  "type": "merit",
  "budget": 1000000,
  "allowance_per_month": 5000,
  "max_slots": 50,
  "application_start": "2024-01-01",
  "application_end": "2024-12-31",
  "is_active": true
}
```

### Update Scholarship (Admin/Staff)
```http
PUT /api/scholarships/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Updated Name",
  "is_active": false
}
```

### Delete Scholarship (Admin)
```http
DELETE /api/scholarships/{id}
Authorization: Bearer {token}
```

## Applications

### List Applications
```http
GET /api/applications?status=pending&scholarship_id=1
Authorization: Bearer {token}
```

### Submit Application
```http
POST /api/applications
Authorization: Bearer {token}
Content-Type: multipart/form-data

{
  "scholarship_id": 1,
  "documents": [
    {
      "requirement_id": 1,
      "file": [File]
    }
  ]
}
```

### Get Application
```http
GET /api/applications/{id}
Authorization: Bearer {token}
```

### Update Application Status (Admin/Staff)
```http
PUT /api/applications/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "status": "approved",
  "remarks": "Application approved"
}
```

### Verify Document (Admin/Staff)
```http
POST /api/applications/{application_id}/documents/{document_id}/verify
Authorization: Bearer {token}
Content-Type: application/json

{
  "is_verified": true,
  "verification_notes": "Document verified"
}
```

## Scholars

### List Scholars
```http
GET /api/scholars?status=active&scholarship_id=1
Authorization: Bearer {token}
```

### Create Scholar (Admin/Staff)
```http
POST /api/scholars
Authorization: Bearer {token}
Content-Type: application/json

{
  "application_id": 1
}
```

### Get Scholar
```http
GET /api/scholars/{id}
Authorization: Bearer {token}
```

### Update Scholar (Admin/Staff)
```http
PUT /api/scholars/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "status": "suspended",
  "suspension_reason": "Low grades"
}
```

### Calculate GWA
```http
POST /api/scholars/{id}/calculate-gwa
Authorization: Bearer {token}
```

## Disbursements

### List Disbursements
```http
GET /api/disbursements?scholar_id=1&status=pending&type=allowance
Authorization: Bearer {token}
```

### Create Disbursement (Admin/Accounting)
```http
POST /api/disbursements
Authorization: Bearer {token}
Content-Type: application/json

{
  "scholar_id": 1,
  "amount": 5000,
  "type": "allowance",
  "scheduled_date": "2024-02-01",
  "remarks": "Monthly allowance"
}
```

### Release Disbursement (Admin/Accounting)
```http
POST /api/disbursements/{id}/release
Authorization: Bearer {token}
```

## Grades

### List Grades
```http
GET /api/grades?scholar_id=1&semester=1st&school_year=2023-2024
Authorization: Bearer {token}
```

### Add Grade (Admin/Staff)
```http
POST /api/grades
Authorization: Bearer {token}
Content-Type: application/json

{
  "scholar_id": 1,
  "subject_code": "CS101",
  "subject_name": "Introduction to Computer Science",
  "grade": 3.5,
  "units": 3,
  "semester": "1st",
  "school_year": "2023-2024"
}
```

## Reports

### Dashboard Statistics
```http
GET /api/reports/dashboard
Authorization: Bearer {token}
```

### Applicants Report
```http
GET /api/reports/applicants?scholarship_id=1&status=approved&date_from=2024-01-01&date_to=2024-12-31
Authorization: Bearer {token}
```

### Scholars Report
```http
GET /api/reports/scholars?scholarship_id=1&status=active
Authorization: Bearer {token}
```

### Financial Report
```http
GET /api/reports/financial?date_from=2024-01-01&date_to=2024-12-31
Authorization: Bearer {token}
```

### Statistics
```http
GET /api/reports/statistics
Authorization: Bearer {token}
```

## Notifications

### List Notifications
```http
GET /api/notifications
Authorization: Bearer {token}
```

### Mark as Read
```http
PUT /api/notifications/{id}/read
Authorization: Bearer {token}
```

### Mark All as Read
```http
PUT /api/notifications/read-all
Authorization: Bearer {token}
```

### Unread Count
```http
GET /api/notifications/unread-count
Authorization: Bearer {token}
```

## Audit Logs (Admin Only)

### List Audit Logs
```http
GET /api/audit-logs?user_id=1&model_type=Application&action=created
Authorization: Bearer {token}
```

## Error Responses

### 401 Unauthorized
```json
{
  "message": "Unauthenticated"
}
```

### 403 Forbidden
```json
{
  "message": "Insufficient permissions"
}
```

### 422 Validation Error
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email has already been taken."]
  }
}
```

### 500 Server Error
```json
{
  "message": "Server Error"
}
```

## Rate Limiting

API requests are rate-limited to 60 requests per minute per user.

## Pagination

List endpoints support pagination:
- `page`: Page number (default: 1)
- `per_page`: Items per page (default: 15)

Response format:
```json
{
  "data": [...],
  "current_page": 1,
  "per_page": 15,
  "total": 100,
  "last_page": 7
}
```
