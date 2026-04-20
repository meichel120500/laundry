<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TypeOfServiceController;
use App\Http\Controllers\TransOrderController;
use App\Http\Controllers\TransOrderDetailController;
use App\Http\Controllers\TransLaundryPickupController;


Route::get('/', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


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

    
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', function () {
           
            $totalCustomer = \App\Models\Customer::count();
            $transaksiHariIni = \App\Models\TransOrder::whereDate('created_at', \Carbon\Carbon::today())->count();
            $belumDiambil = \App\Models\TransOrder::where('order_status', 0)->count();
            return view('admin.dashboard', compact('totalCustomer', 'transaksiHariIni', 'belumDiambil'));
        })->name('admin.dashboard');

        // CRUD Master Data
        Route::resource('users', UserController::class);
        Route::resource('customers', CustomerController::class);
        Route::resource('services', TypeOfServiceController::class);

    });

   
    Route::prefix('operator')->group(function () {
        // Transaksi Laundry
        Route::resource('orders', TransOrderController::class);
        Route::post('orders/{id}/add-detail', [TransOrderController::class, 'addDetail'])->name('orders.addDetail');
        Route::resource('order-details', TransOrderDetailController::class);
        
        // Transaksi Pengambilan [cite: 19]
        Route::resource('pickups', TransLaundryPickupController::class);
        Route::get('/pickups/update-status/{id}', [TransLaundryPickupController::class, 'updateStatus'])->name('pickups.updateStatus');
        Route::post('/pickups/bayar', [TransLaundryPickupController::class, 'bayar'])->name('pickups.bayar');

        // Customer Feature for Operator
        Route::get('customers', [CustomerController::class, 'index'])->name('operator.customers.index');
        Route::post('customers/ajax', [CustomerController::class, 'storeAjax'])->name('operator.customers.storeAjax');
    });

    
    Route::prefix('pimpinan')->group(function () {
        Route::get('/dashboard', function () {
            $totalCustomer = \App\Models\Customer::count();
            $transaksiHariIni = \App\Models\TransOrder::whereDate('created_at', \Carbon\Carbon::today())->count();

            return view('pimpinan.dashboard', compact('totalCustomer', 'transaksiHariIni'));
        })->name('pimpinan.dashboard');

        // Route Laporan
        Route::get('/laporan', [TransOrderController::class, 'report'])->name('laporan.penjualan');
    });

});