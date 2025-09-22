<?php

use App\Http\Controllers\BorrowController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ItemController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UsersController;

Route::get('/', function () {
    return view('index');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('users')->name('users.')->group(function () {
    Route::get('students', [UsersController::class, 'students'])->name('students');
    Route::get('teachers', [UsersController::class, 'teachers'])->name('teachers');

    Route::resource('/', UsersController::class)
        ->parameters(['' => 'user'])
        ->names([
            'index'   => 'index',
            'create'  => 'create',
            'store'   => 'store',
            'show'    => 'show',
            'edit'    => 'edit',
            'update'  => 'update',
            'destroy' => 'destroy',
        ]);
});

Route::prefix('borrows')->name('borrows.')->group(function () {
    Route::resource('/', BorrowController::class)
    ->parameters(['' => 'borrows'])
    ->names([
        'index'   => 'index',
        'create'  => 'create',
        'store'   => 'store',
        'edit'    => 'edit',
        'update'  => 'update',
        'destroy' => 'destroy',
    ]);
    
    Route::put('{id}/accepted', [BorrowController::class, 'accepted'])->name('accepted');
    Route::put('{id}/declined', [BorrowController::class, 'declined'])->name('declined');
    Route::put('{id}/finished', [BorrowController::class, 'finished'])->name('finished');

    Route::get('export/excel', [BorrowController::class, 'exportExcel'])->name('export.excel');
    Route::get('export/pdf', [BorrowController::class, 'exportPdf'])->name('export.pdf');
});

Route::prefix('items')->name('items.')->group(function () {
    Route::resource('/', ItemController::class)
    ->parameters(['' => 'items'])
    ->names([
        'index'   => 'index',
        'create'  => 'create',
        'store'   => 'store',
        'edit'    => 'edit',
        'update'  => 'update',
        'destroy' => 'destroy',
    ]);

    Route::get('export/excel', [ItemController::class, 'exportExcel'])->name('export.excel');
    Route::get('export/pdf', [ItemController::class, 'exportPdf'])->name('export.pdf');
});

Route::prefix('maintains')->name('maintains.')->group(function () {
    Route::resource('/', MaintenanceController::class)
    ->parameters(['' => 'maintains'])
    ->names([
        'index'   => 'index',
        'create'  => 'create',
        'store'   => 'store',
        'edit'    => 'edit',
        'update'  => 'update',
        'destroy' => 'destroy',
    ]);

    Route::put('{id}/finished', [MaintenanceController::class, 'finished'])->name('finished');

    Route::get('export/excel', [MaintenanceController::class, 'exportExcel'])->name('export.excel');
    Route::get('export/pdf', [MaintenanceController::class, 'exportPdf'])->name('export.pdf');
});

Route::prefix('/')->group(function () {
    Route::resource('roles', RolesController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('locations', LocationController::class);
});



require __DIR__.'/auth.php';