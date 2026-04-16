@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3>Halo, {{ Auth::user()->name }}!</h3>
                    <p>Anda login sebagai: <strong>{{ Auth::user()->level->level_name }}</strong></p>
                    <hr>
                    <div class="row text-center mt-4 justify-content-center">
                        <div class="col-md-5">
                            <div class="p-4 bg-primary text-white rounded shadow-sm">
                                <h5><i class="bi bi-people"></i> Total Customers</h5>
                                <h2>{{ $totalCustomer }}</h2>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="p-4 bg-success text-white rounded shadow-sm">
                                <h5><i class="bi bi-cart-check"></i> Transaksi Hari Ini</h5>
                                <h2>{{ $transaksiHariIni }}</h2>
                             </div>
                        </div>
                    </div>       
                </div>
            </div>
        </div>
    </div>
</div>
@endsection