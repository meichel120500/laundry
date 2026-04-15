@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-body">
            <h3>Halo, {{ Auth::user()->name }}!</h3>
            <p>Anda login sebagai: <strong>Administrator</strong></p>
            <hr>
            <div class="row text-center mt-4">
                <div class="col-md-4 mb-3">
                    <div class="p-4 bg-primary text-white rounded shadow-sm">
                        <h6>Total Customers</h6>
                        <h2 class="display-4 fw-bold">{{ $totalCustomer }}</h2>
                    </div>
                </div>
                
                <div class="col-md-4 mb-3">
                    <div class="p-4 bg-success text-white rounded shadow-sm">
                        <h6>Transaksi Hari Ini</h6>
                        <h2 class="display-4 fw-bold">{{ $transaksiHariIni }}</h2>
                    </div>
                </div>
                
                <div class="col-md-4 mb-3">
                    <div class="p-4 bg-danger text-white rounded shadow-sm">
                        <h6>Pakaian Belum Diambil</h6>
                        <h2 class="display-4 fw-bold">{{ $belumDiambil }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection