<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePriceLevelRequest;
use App\Http\Requests\UpdatePriceLevelRequest;
use App\Models\PriceLevel;

class PriceLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePriceLevelRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePriceLevelRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PriceLevel  $priceLevel
     * @return \Illuminate\Http\Response
     */
    public function show(PriceLevel $priceLevel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PriceLevel  $priceLevel
     * @return \Illuminate\Http\Response
     */
    public function edit(PriceLevel $priceLevel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePriceLevelRequest  $request
     * @param  \App\Models\PriceLevel  $priceLevel
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePriceLevelRequest $request, PriceLevel $priceLevel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PriceLevel  $priceLevel
     * @return \Illuminate\Http\Response
     */
    public function destroy(PriceLevel $priceLevel)
    {
        //
    }
}
