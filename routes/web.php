<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TypeOfServiceController;
use App\Http\Controllers\TransOrderController;
use App\Http\Controllers\TransOrderDetailController;
use App\Http\Controllers\TransLaundryPickupController;

/*
|--------------------------------------------------------------------------
| Public Routes (Akses Tanpa Login)
|--------------------------------------------------------------------------
*/
Route::get('/', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Protected Routes (Harus Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Dashboard Utama (Bisa diakses semua level yang sudah login)
    Route::get('/dashboard', function () {
       $user = Auth::user();

       if($user->id_level == 1) {
        return redirect()->route('admin.dashboard');
       } else if($user->id_level == 2) {
        return redirect()->route('orders.index');
       } else if($user->id_level == 3) {
        return redirect()->route('pimpinan.dashboard');
       }
       return redirect('/');
    })->name('dashboard');

    /*
    |----------------------------------------------------------------------
    | Role: Administrator / Super Admin [cite: 14]
    | Fitur: Kelola Master Data (Customer, User, Jenis Service) [cite: 15]
    |----------------------------------------------------------------------
    */
    /*
    |----------------------------------------------------------------------
    | Role: Administrator / Super Admin
    |----------------------------------------------------------------------
    */
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', function () {
           
            $totalCustomer = \App\Models\Customer::count();
            $transaksiHariIni = \App\Models\TransOrder::whereDate('created_at', \Carbon\Carbon::today())->count();
            $belumDiambil = \App\Models\TransOrder::where('order_status', '!=', 2)->count();
            return view('admin.dashboard', compact('totalCustomer', 'transaksiHariIni', 'belumDiambil'));
        })->name('admin.dashboard');

        // CRUD Master Data
        Route::resource('users', UserController::class);
        Route::resource('customers', CustomerController::class);
        Route::resource('services', TypeOfServiceController::class);
    });

    /*
    |----------------------------------------------------------------------
    | Role: Operator [cite: 17]
    | Fitur: Transaksi Laundry dan Pengambilan [cite: 18, 19]
    |----------------------------------------------------------------------
    */
    Route::prefix('operator')->group(function () {
        // Transaksi Laundry
        Route::resource('orders', TransOrderController::class);
        Route::resource('order-details', TransOrderDetailController::class);
        
        // Transaksi Pengambilan [cite: 19]
        Route::resource('pickups', TransLaundryPickupController::class);
        Route::get('/pickups/update-status/{id}', [TransLaundryPickupController::class, 'updateStatus'])->name('pickups.updateStatus');
        Route::post('/pickups/bayar', [TransLaundryPickupController::class, 'bayar'])->name('pickups.bayar');
    });

    /*
    |----------------------------------------------------------------------
    | Role: Pimpinan [cite: 20]
    | Fitur: Melihat Laporan Penjualan [cite: 21]
    |----------------------------------------------------------------------
    */
    Route::prefix('pimpinan')->group(function () {
        Route::get('/dashboard', function () {
            return view('pimpinan.dashboard');
        })->name('pimpinan.dashboard');

        // Route Laporan
        Route::get('/laporan', [TransOrderController::class, 'report'])->name('laporan.penjualan');
    });

});