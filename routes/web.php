<?php

use App\Http\Controllers\ChatAnalyticsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrganizationAdminController;
use App\Http\Controllers\UserOrganizationAdminController;
use App\Http\Controllers\AISettingsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AIGuideController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard.analytics');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard/analytics', [ChatAnalyticsController::class, 'index'])
        ->name('dashboard.analytics');

    Route::post('/dashboard/analytics/ask', [ChatAnalyticsController::class, 'ask'])
        ->name('dashboard.analytics.ask')
        ->middleware(['check.org']);

    Route::post('/dashboard/analytics/export', [ChatAnalyticsController::class, 'export'])
        ->name('dashboard.analytics.export')
        ->middleware(['check.org']);

    Route::resource('admin/organizations', OrganizationAdminController::class)
        ->names('admin.organizations')
        ->except(['show'])
        ;

    Route::post('admin/organizations/{organization}/test-connection', [OrganizationAdminController::class, 'testConnection'])
        ->name('admin.organizations.test-connection');

    Route::get('admin/users', [UserOrganizationAdminController::class, 'index'])
        ->name('admin.users.index');

    Route::post('admin/users', [UserOrganizationAdminController::class, 'store'])
        ->name('admin.users.store');

    Route::patch('admin/users/{user}/organization', [UserOrganizationAdminController::class, 'updateOrganization'])
        ->name('admin.users.organization.update');

    Route::patch('admin/users/{user}', [UserOrganizationAdminController::class, 'update'])
        ->name('admin.users.update');

    Route::delete('admin/users/{user}', [UserOrganizationAdminController::class, 'destroy'])
        ->name('admin.users.destroy');

    Route::get('admin/ai-settings', [AISettingsController::class, 'edit'])
        ->name('admin.ai-settings.edit');
    Route::patch('admin/ai-settings', [AISettingsController::class, 'update'])
        ->name('admin.ai-settings.update');

    Route::get('admin/ai-guide', [AIGuideController::class, 'edit'])
        ->name('admin.ai-guide.edit');
    Route::patch('admin/ai-guide', [AIGuideController::class, 'update'])
        ->name('admin.ai-guide.update');
});

require __DIR__.'/auth.php';
