<?php

namespace App\Http\Controllers;

use App\Models\category_post;
use App\Http\Requests\Storecategory_postRequest;
use App\Http\Requests\Updatecategory_postRequest;

class CategoryPostController extends Controller
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
    public function store(Storecategory_postRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(category_post $category_post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(category_post $category_post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Updatecategory_postRequest $request, category_post $category_post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(category_post $category_post)
    {
        //
    }
}
