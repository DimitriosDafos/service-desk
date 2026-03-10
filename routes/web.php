<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Tenancy\AdminTenantController;
use App\Http\Controllers\Tenancy\TenantController;
use App\Http\Controllers\Tenancy\GroupController;
use App\Http\Controllers\Tenancy\QueueController;
use App\Http\Controllers\Tenancy\UserController;
use App\Http\Controllers\Ticketing\TicketController;
use App\Http\Controllers\KnowledgeBase\ArticleController;
use App\Http\Controllers\CMDB\AssetController;
use App\Http\Controllers\SLA\SlaPolicyController;
use App\Http\Controllers\Reporting\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('tenants', AdminTenantController::class)->middleware('can:system-admin');
    });

    Route::prefix('tenants')->name('tenants.')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('groups', GroupController::class);
        Route::post('groups/{group}/add-user', [GroupController::class, 'addUser'])->name('groups.add-user');
        Route::post('groups/{group}/remove-user', [GroupController::class, 'removeUser'])->name('groups.remove-user');

        Route::resource('queues', QueueController::class);
    });

    Route::resource('tickets', TicketController::class);
    Route::post('tickets/{ticket}/comments', [TicketController::class, 'addComment'])->name('tickets.comments');

    Route::prefix('knowledge-base')->name('knowledge-base.')->group(function () {
        Route::resource('articles', ArticleController::class);
    });

    Route::prefix('assets')->name('assets.')->group(function () {
        Route::resource('assets', AssetController::class);
    });

    Route::prefix('sla')->name('sla.')->group(function () {
        Route::resource('policies', SlaPolicyController::class);
    });
});

require __DIR__.'/auth.php';
