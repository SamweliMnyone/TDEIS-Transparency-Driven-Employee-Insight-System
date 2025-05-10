<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HRController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PMController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ContributionController;
use App\Http\Controllers\RoleController;


// Public Routes (Only for guests)
Route::middleware(['guest.user'])->group(function () {
    Route::get('/', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login.authenticate');
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'store'])->name('register.store');
    Route::get('/forgot-password', [AuthController::class, 'forgot'])->name('forgot');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});


// Admin Routes – Only users with 'ADMIN' role can access
Route::middleware(['auth.user', 'session.timeout', 'no-cache', 'role:ADMIN'])->prefix('admin')->group(function () {

    // Dashboard & Profile
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/dashboard/profile', [AdminController::class, 'profile'])->name('admin.dashboard.profile');
    Route::put('/dashboard/profile/update', [AdminController::class, 'updateProfile'])->name('admin.profile.update');
    Route::put('/dashboard/profile/update-password', [AdminController::class, 'updatePassword'])->name('admin.profile.update-password');
    Route::delete('/dashboard/profile/delete-account', [AdminController::class, 'deleteAccount'])->name('admin.profile.delete-account');

    // Manage Users
    Route::get('/manage/{type}', [AdminController::class, 'manage_users'])->name('manage.users');
    Route::post('/user/create', [AdminController::class, 'createUser'])->name('user.create');
    Route::put('/user/update/{id}', [AdminController::class, 'updateUser'])->name('user.update');
    Route::delete('/user/delete/{id}', [AdminController::class, 'deleteUser'])->name('user.delete');

    // Permissions
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::put('/permissions/{user}/update', [PermissionController::class, 'update'])->name('permissions.update');

    // Roles
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::post('/roles/store', [RoleController::class, 'store'])->name('roles.store');
    Route::put('/roles/{role}/update', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{role}/destroy', [RoleController::class, 'destroy'])->name('roles.destroy');
});



// HR Routes
Route::middleware(['auth.user', 'session.timeout', 'no-cache', 'role:HR'])->prefix('hr')->group(function () {
    // Dashboard
    Route::get('/dashboard', [HRController::class, 'dashboard'])->name('hr.dashboard');
    Route::get('/employee&skills', [HRController::class, 'employeeskills'])->name('pm.employee&skills');
    Route::get('/dashboard/profile', [HRController::class, 'profile'])->name('hr.dashboard.profile');
    Route::put('/dashboard/profile/update', [HRController::class, 'updateProfile'])->name('hr.profile.update');
    Route::put('/dashboard/profile/update-password', [HRController::class, 'updatePassword'])->name('hr.profile.update-password');
    Route::delete('/dashboard/profile/delete-account', [HRController::class, 'deleteAccount'])->name('hr.profile.delete-account');



    Route::get('/assignments', [HRController::class, 'assignments2'])->name('hr.assignments');

    // Add these to your existing PM routes group
    Route::put('/assignments/{assignment}', [HRController::class, 'updateAssignment'])->name('hr.assignments.update');
    Route::delete('/assignments/{assignment}', [HRController::class, 'destroyAssignment'])->name('hr.assignments.destroy');


// Reports
Route::match(['get', 'post'], '/hr/reports/generate', [HRController::class, 'generateReport'])->name('hr.report.generate');

});

// Project Manager Routes
Route::middleware(['auth.user', 'session.timeout', 'no-cache', 'role:PM'])->prefix('pm')->group(function () {
    Route::get('/dashboard', [PMController::class, 'dashboard'])->name('pm.dashboard');
    Route::get('/dashboard/profile', [PMController::class, 'profile'])->name('pm.dashboard.profile');
    Route::put('/dashboard/profile/update', [PMController::class, 'updateProfile'])->name('pm.profile.update');
    Route::put('/dashboard/profile/update-password', [PMController::class, 'updatePassword'])->name('pm.profile.update-password');
    Route::delete('/dashboard/profile/delete-account', [PMController::class, 'deleteAccount'])->name('pm.profile.delete-account');

    Route::get('/projects', [PMController::class, 'projects'])->name('pm.projects');
    Route::post('/projects', [PMController::class, 'storeProject'])->name('pm.projects.store');
    Route::put('/projects/{project}', [PMController::class, 'updateProject'])->name('pm.projects.update');
    Route::delete('/projects/{project}', [PMController::class, 'destroyProject'])->name('pm.projects.destroy');
    Route::get('/check-flash-messages', [PMController::class, 'checkFlashMessages'])->name('check.flash');

    Route::get('/pm/projects', [PMController::class, 'projects'])->name('projects');
    Route::post('/pm/project/{project}/assign', [PMController::class, 'assignEmployeeToProject'])->name('assign.employee');
    Route::get('/pm/assignments', [PMController::class, 'assignments'])->name('pm.assignments');

    // Add these to your existing PM routes group
    Route::put('/pm/assignments/{assignment}', [PMController::class, 'updateAssignment'])->name('pm.assignments.update');
    Route::delete('/pm/assignments/{assignment}', [PMController::class, 'destroyAssignment'])->name('pm.assignments.destroy');




});

// Employee Routes
Route::middleware(['auth.user', 'session.timeout', 'no-cache', 'role:Employee'])->prefix('employee')->group(function () {
    Route::get('/dashboard', [EmployeeController::class, 'dashboard'])->name('employee.dashboard');
    Route::get('/dashboard/profile', [EmployeeController::class, 'profile'])->name('employee.dashboard.profile');
    Route::put('/dashboard/profile/update', [EmployeeController::class, 'updateProfile'])->name('employee.profile.update');
    Route::put('/dashboard/profile/update-password', [EmployeeController::class, 'updatePassword'])->name('employee.profile.update-password');
    Route::delete('/dashboard/profile/delete-account', [EmployeeController::class, 'deleteAccount'])->name('employee.profile.delete-account');
    Route::get('/skills', [EmployeeController::class, 'skills'])->name('employee.skills');
    Route::post('/skills', [EmployeeController::class, 'storeSkill'])->name('employee.skills.store');
    Route::put('/skills/{skill}', [EmployeeController::class, 'updateSkill'])->name('employee.skills.update');
    Route::delete('/skills/{skill}', [EmployeeController::class, 'destroySkill'])->name('employee.skills.destroy');


    Route::get('/employee/assignments', [EmployeeController::class, 'myAssignments'])->name('employee.assignments');
Route::get('/employee/assignments/{assignment}/download/{type}', [EmployeeController::class, 'downloadNotification'])
    ->name('employee.assignments.download-notification')
    ->where('type', 'approval|rejection');


    // Resource route for contributions
    Route::resource('contributions', ContributionController::class);

    // If you want named routes that match your original HTML
    Route::get('add-contribution', [ContributionController::class, 'create'])->name('employee.contributions.create');

    Route::get('view-contributions', [ContributionController::class, 'index'])->name('employee.contributions.index');
});

// Common logout route for all authenticated users
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware(['auth.user', 'no-cache'])
    ->name('logout');
