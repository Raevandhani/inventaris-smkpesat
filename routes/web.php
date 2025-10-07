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

Route::get('/verify', function () {
    return view('verify');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy'); 
});

Route::middleware('auth')->group(function () {
    Route::resource('users', UsersController::class)->except(['show']);
});

Route::prefix('borrows')->name('borrows.')->middleware(['auth'])->group(function () {
    Route::get('/', [BorrowController::class, 'index'])
        ->name('index')
        ->middleware('can:borrow.view');

    Route::post('/', [BorrowController::class, 'store'])
        ->name('store')
        ->middleware('can:borrow.request');

    Route::put('/{borrows}', [BorrowController::class, 'update'])
        ->name('update')
        ->middleware('can:borrow.manage');

    Route::delete('/{borrows}', [BorrowController::class, 'destroy'])
        ->name('destroy')
        ->middleware('can:borrow.request');

    Route::put('{id}/accepted', [BorrowController::class, 'accepted'])
        ->name('accepted')
        ->middleware('can:borrow.manage');

    Route::put('{id}/declined', [BorrowController::class, 'declined'])
        ->name('declined')
        ->middleware('can:borrow.manage');

    Route::put('{id}/finished', [BorrowController::class, 'finished'])
        ->name('finished')
        ->middleware('can:borrow.manage');

    Route::get('export/excel', [BorrowController::class, 'exportExcel'])
        ->name('export.excel')
        ->middleware('can:borrow.manage');

    Route::get('export/pdf', [BorrowController::class, 'exportPdf'])
        ->name('export.pdf')
        ->middleware('can:borrow.manage');
});

Route::prefix('items')->name('items.')->middleware(['auth'])->group(function () {
    Route::get('/', [ItemController::class, 'index'])
        ->name('index')
        ->middleware('can:items.view');

    Route::post('/', [ItemController::class, 'store'])
        ->name('store')
        ->middleware('can:items.manage');

    Route::put('/{items}', [ItemController::class, 'update'])
        ->name('update')
        ->middleware('can:items.manage');

    Route::delete('/{items}', [ItemController::class, 'destroy'])
        ->name('destroy')
        ->middleware('can:items.manage');

    Route::get('export/excel', [ItemController::class, 'exportExcel'])
        ->name('export.excel')
        ->middleware('can:items.manage');

    Route::get('export/pdf', [ItemController::class, 'exportPdf'])
        ->name('export.pdf')
        ->middleware('can:items.manage');
});

Route::prefix('maintains')->name('maintains.')->middleware(['auth'])->group(function () {
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
    
    Route::put('{id}/finished', [MaintenanceController::class, 'finished'])
        ->name('finished');
    
    Route::get('export/excel', [MaintenanceController::class, 'exportExcel'])
        ->name('export.excel');
    
    Route::get('export/pdf', [MaintenanceController::class, 'exportPdf'])
        ->name('export.pdf');
});


Route::prefix('/')->middleware(['auth'])->group(function () {
    Route::resource('roles', RolesController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('locations', LocationController::class);
});

require __DIR__.'/auth.php';