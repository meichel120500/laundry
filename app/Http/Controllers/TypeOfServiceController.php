<?php

namespace App\Http\Controllers;

use App\Models\TypeOfService;
use Illuminate\Http\Request;

class TypeOfServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = TypeOfService::all();
        return view('admin.services.index', compact('services'));
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
        $request->validate([
            'service_name' => 'required',
            'price' => 'required|numeric',
        ]);

        TypeOfService::create($request->all());
        return redirect()->route('services.index')->with('success', 'Layanan berhasil ditambah');
    }
    /**
     * Display the specified resource.
     */
    public function show(TypeOfService $typeOfService)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TypeOfService $typeOfService)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TypeOfService $service)
    {
        $service->update($request->all());
        return redirect()->route('services.index')->with('success', 'Layanan berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TypeOfService $service)
    {
        $service->delete();
        return redirect()->route('services.index')->with('success', 'Layanan berhasil dihapus');
    }
}
