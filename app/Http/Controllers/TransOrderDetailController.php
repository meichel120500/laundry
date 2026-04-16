<?php

namespace App\Http\Controllers;

use App\Models\TransOrderDetail;
use Illuminate\Http\Request;

class TransOrderDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orderDetails = TransOrderDetail::with(['order.customer', 'service'])->latest()->get();
        return view('operator.order_details.index', compact('orderDetails'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(TransOrderDetail $transOrderDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TransOrderDetail $transOrderDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TransOrderDetail $transOrderDetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TransOrderDetail $transOrderDetail)
    {
        //
    }
}
