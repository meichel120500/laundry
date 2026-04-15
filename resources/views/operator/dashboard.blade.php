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
                    <div class="row text-center mt-4">
                        <div class="col-md-4">
                            <div class="p-3 bg-primary text-white rounded">
                                <h5>Total Customers</h5>
                                <h2>120</h2>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-primary text-white rounded">
                                <h5>Transaksi Hari Ini</h5>
                                <h2>15</h2>
                             </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-primary text-white rounded">
                                <h5>Pakaian Belum Ambil</h5>
                                <h2>5</h2>
                            </div>
                        </div>
                    </div>       
                </div>
            </div>
        </div>
    </div>
</div>
@endsection