<?php

namespace App\Http\Controllers;

use App\Models\Component;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreComponentRequest;
use App\Http\Requests\UpdateComponentRequest;
use App\Imports\ImportComponents;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Request;
use Maatwebsite\Excel\Facades\Excel;

class ComponentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StoreComponentRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Component $component)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Component $component)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateComponentRequest $request, Component $component)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Component $component)
    {
        //
    }

    public function import_excel(Request $request)
	{
		// menangkap file excel
		$file = $request->file('file');

		// membuat nama file unik
		$nama_file = rand().$file->getClientOriginalName();

		// upload ke folder file_siswa di dalam folder public
		$file->move('file_siswa',$nama_file);

		// import data
		Excel::import(new ImportComponents, public_path('/file_component/'.$nama_file));
	}
}
