<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FirstLoginPasswordController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DealerDirectoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyRequestController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\WebPushController;
use App\Http\Middleware\CanReceiveNotifications;
use App\Http\Middleware\EnsurePasswordChanged;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

Route::get('/notification-worker.js', fn () => response()->file(public_path('notification-worker.js'), [
    'Content-Type' => 'application/javascript',
    'Cache-Control' => 'no-cache',
]));

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::middleware(['auth', EnsurePasswordChanged::class])->group(function () {
    Route::get('/change-password', [FirstLoginPasswordController::class, 'edit'])->name('password.change.edit');
    Route::put('/change-password', [FirstLoginPasswordController::class, 'update'])->name('password.change.update');
    Route::middleware(CanReceiveNotifications::class)->group(function () {
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::patch('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.read-all');
        Route::patch('/notifications/{notification}/read', [NotificationController::class, 'read'])->name('notifications.read');
        Route::get('/notifications/{notification}/open', [NotificationController::class, 'open'])->name('notifications.open');
        Route::post('/notifications/push/subscription', [WebPushController::class, 'store'])->name('notifications.push.store');
        Route::delete('/notifications/push/subscription', [WebPushController::class, 'destroy'])->name('notifications.push.destroy');
    });
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/requests', [PropertyRequestController::class, 'index'])->name('requests.index');
    Route::get('/requests/create', [PropertyRequestController::class, 'create'])->name('requests.create');
    Route::get('/requests/export', [PropertyRequestController::class, 'export'])->name('requests.export');
    Route::post('/requests', [PropertyRequestController::class, 'store'])->name('requests.store');
    Route::get('/inspection-request-template', [PropertyRequestController::class, 'inspectionRequestTemplate'])->name('requests.inspection.template');
    Route::get('/requests/{propertyRequest}', [PropertyRequestController::class, 'show'])->name('requests.show');
    Route::patch('/requests/{propertyRequest}/schedule', [PropertyRequestController::class, 'updateSchedule'])->name('requests.schedule');
    Route::patch('/requests/{propertyRequest}/assign', [PropertyRequestController::class, 'assignToMe'])->name('requests.assign');
    Route::patch('/requests/{propertyRequest}/status', [PropertyRequestController::class, 'updateStatus'])->name('requests.status');
    Route::patch('/requests/{propertyRequest}/priority', [PropertyRequestController::class, 'updatePriority'])->name('requests.priority');
    Route::patch('/requests/{propertyRequest}/acknowledge', [PropertyRequestController::class, 'acknowledge'])->name('requests.acknowledge');
    Route::patch('/requests/{propertyRequest}/inspection-assignment', [PropertyRequestController::class, 'assignInspection'])->name('requests.inspection.assign');
    Route::patch('/requests/{propertyRequest}/inspection-date', [PropertyRequestController::class, 'saveInspectionDate'])->name('requests.inspection.date');
    Route::patch('/requests/{propertyRequest}/inspection-complete', [PropertyRequestController::class, 'completeInspection'])->name('requests.inspection.complete');
    Route::patch('/requests/{propertyRequest}/work-order-date', [PropertyRequestController::class, 'saveWorkOrderDate'])->name('requests.work-order.date');
    Route::post('/requests/{propertyRequest}/work-order-complete', [PropertyRequestController::class, 'completeWorkOrder'])->name('requests.work-order.complete');
    Route::post('/requests/{propertyRequest}/service-report-start', [PropertyRequestController::class, 'startServiceReport'])->name('requests.service-report.start');
    Route::post('/requests/{propertyRequest}/service-report-upload', [PropertyRequestController::class, 'uploadServiceReport'])->name('requests.service-report.upload');
    Route::post('/requests/{propertyRequest}/service-report-complete', [PropertyRequestController::class, 'completeServiceReport'])->name('requests.service-report.complete');
    Route::post('/requests/{propertyRequest}/notify-lead', [PropertyRequestController::class, 'notifyDialLead'])->name('requests.notify-lead');
    Route::post('/requests/{propertyRequest}/finish', [PropertyRequestController::class, 'finishRequest'])->name('requests.finish');
    Route::patch('/requests/{propertyRequest}/workflow/{stage}/undo', [PropertyRequestController::class, 'undoWorkflowStage'])
        ->where('stage', 'inspection|work-order|service-report|completion')
        ->name('requests.workflow.undo');
    Route::delete('/requests/{propertyRequest}', [PropertyRequestController::class, 'destroyRequest'])->name('requests.destroy');
    Route::get('/attachments/{attachment}', [PropertyRequestController::class, 'attachment'])->name('attachments.show');

    Route::get('/dealer-directory', [DealerDirectoryController::class, 'index'])->name('dealers.index');
    Route::get('/dealer-directory/create', [DealerDirectoryController::class, 'create'])->name('dealers.create');
    Route::post('/dealer-directory', [DealerDirectoryController::class, 'store'])->name('dealers.store');
    Route::get('/dealer-directory/import', [DealerDirectoryController::class, 'importForm'])->name('dealers.import');
    Route::post('/dealer-directory/import', [DealerDirectoryController::class, 'import'])->name('dealers.import.store');
    Route::get('/dealer-directory/export', [DealerDirectoryController::class, 'export'])->name('dealers.export');
    Route::get('/dealer-directory/template', [DealerDirectoryController::class, 'template'])->name('dealers.template');
    Route::get('/dealer-directory/print', [DealerDirectoryController::class, 'printable'])->name('dealers.print');
    Route::get('/dealer-directory/{dealer}/edit', [DealerDirectoryController::class, 'edit'])->name('dealers.edit');
    Route::put('/dealer-directory/{dealer}', [DealerDirectoryController::class, 'update'])->name('dealers.update');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/print', [ReportController::class, 'printable'])->name('reports.print');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/admin/areas-branches', [AdminController::class, 'areas'])->name('admin.areas');
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::get('/admin/roles', [AdminController::class, 'roles'])->name('admin.roles');
    Route::get('/admin/audit-logs', [AdminController::class, 'auditLogs'])->name('admin.audit');
    Route::get('/admin/system-settings', [AdminController::class, 'settings'])->name('admin.settings');
    Route::get('/admin/reset-requests', [AdminController::class, 'showResetRequests'])->name('admin.reset-requests.show');
    Route::delete('/admin/reset-requests', [AdminController::class, 'resetRequests'])->name('admin.reset-requests.destroy');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
