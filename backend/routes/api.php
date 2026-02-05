<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\ScholarshipController;
use App\Http\Controllers\RequirementController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ScreeningController;
use App\Http\Controllers\ScholarController;
use App\Http\Controllers\DisbursementController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Applicants
    Route::get('/applicants', [ApplicantController::class, 'index']);
    Route::post('/applicants', [ApplicantController::class, 'store']);
    Route::get('/applicants/{applicant}', [ApplicantController::class, 'show']);
    Route::put('/applicants/{applicant}', [ApplicantController::class, 'update']);

    // Scholarships
    Route::get('/scholarships', [ScholarshipController::class, 'index']);
    Route::post('/scholarships', [ScholarshipController::class, 'store'])->middleware('role:admin,staff');
    Route::get('/scholarships/{scholarship}', [ScholarshipController::class, 'show']);
    Route::put('/scholarships/{scholarship}', [ScholarshipController::class, 'update'])->middleware('role:admin,staff');
    Route::delete('/scholarships/{scholarship}', [ScholarshipController::class, 'destroy'])->middleware('role:admin');

    // Requirements
    Route::get('/requirements', [RequirementController::class, 'index']);
    Route::post('/requirements', [RequirementController::class, 'store'])->middleware('role:admin,staff');
    Route::put('/requirements/{requirement}', [RequirementController::class, 'update'])->middleware('role:admin,staff');
    Route::delete('/requirements/{requirement}', [RequirementController::class, 'destroy'])->middleware('role:admin,staff');

    // Applications
    Route::get('/applications', [ApplicationController::class, 'index']);
    Route::post('/applications', [ApplicationController::class, 'store']);
    Route::get('/applications/{application}', [ApplicationController::class, 'show']);
    Route::put('/applications/{application}', [ApplicationController::class, 'update'])->middleware('role:admin,staff');
    Route::post('/applications/{application}/documents/{document}/verify', [ApplicationController::class, 'verifyDocument'])->middleware('role:admin,staff');

    // Screenings
    Route::get('/screenings', [ScreeningController::class, 'index']);
    Route::post('/screenings', [ScreeningController::class, 'store'])->middleware('role:admin,committee');
    Route::get('/screenings/{screening}', [ScreeningController::class, 'show']);
    Route::post('/screenings/{screening}/applications/{application}/score', [ScreeningController::class, 'scoreApplication'])->middleware('role:admin,committee');
    Route::post('/screenings/{screening}/rank', [ScreeningController::class, 'rankApplications'])->middleware('role:admin,committee');

    // Scholars
    Route::get('/scholars', [ScholarController::class, 'index']);
    Route::post('/scholars', [ScholarController::class, 'store'])->middleware('role:admin,staff');
    Route::get('/scholars/{scholar}', [ScholarController::class, 'show']);
    Route::put('/scholars/{scholar}', [ScholarController::class, 'update'])->middleware('role:admin,staff');
    Route::post('/scholars/{scholar}/calculate-gwa', [ScholarController::class, 'calculateGWA']);

    // Disbursements
    Route::get('/disbursements', [DisbursementController::class, 'index']);
    Route::post('/disbursements', [DisbursementController::class, 'store'])->middleware('role:admin,accounting');
    Route::get('/disbursements/{disbursement}', [DisbursementController::class, 'show']);
    Route::put('/disbursements/{disbursement}', [DisbursementController::class, 'update'])->middleware('role:admin,accounting');
    Route::post('/disbursements/{disbursement}/release', [DisbursementController::class, 'release'])->middleware('role:admin,accounting');

    // Grades
    Route::get('/grades', [GradeController::class, 'index']);
    Route::post('/grades', [GradeController::class, 'store'])->middleware('role:admin,staff');
    Route::put('/grades/{grade}', [GradeController::class, 'update'])->middleware('role:admin,staff');
    Route::delete('/grades/{grade}', [GradeController::class, 'destroy'])->middleware('role:admin,staff');

    // Reports
    Route::get('/reports/dashboard', [ReportController::class, 'dashboard']);
    Route::get('/reports/applicants', [ReportController::class, 'applicantsReport']);
    Route::get('/reports/scholars', [ReportController::class, 'scholarsReport']);
    Route::get('/reports/financial', [ReportController::class, 'financialReport']);
    Route::get('/reports/statistics', [ReportController::class, 'statistics']);

    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index']);
    Route::put('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead']);
    Route::put('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead']);
    Route::get('/notifications/unread-count', [\App\Http\Controllers\NotificationController::class, 'unreadCount']);

    // Audit Logs (Admin only)
    Route::get('/audit-logs', [\App\Http\Controllers\AuditLogController::class, 'index'])->middleware('role:admin');
    Route::get('/audit-logs/{auditLog}', [\App\Http\Controllers\AuditLogController::class, 'show'])->middleware('role:admin');

    // Users (Admin only)
    Route::get('/users', [UserController::class, 'index'])->middleware('role:admin');
    Route::post('/users', [UserController::class, 'store'])->middleware('role:admin');
    Route::get('/users/{user}', [UserController::class, 'show'])->middleware('role:admin');
    Route::put('/users/{user}', [UserController::class, 'update'])->middleware('role:admin');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->middleware('role:admin');
});
