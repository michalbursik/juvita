<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGoodsReceiptRequest;
use App\Http\Requests\UpdateGoodsReceiptRequest;
use App\Models\GoodsReceipt;

class GoodsReceiptController extends Controller
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
     * @param  \App\Http\Requests\StoreGoodsReceiptRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGoodsReceiptRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GoodsReceipt  $goodsReceipt
     * @return \Illuminate\Http\Response
     */
    public function show(GoodsReceipt $goodsReceipt)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\GoodsReceipt  $goodsReceipt
     * @return \Illuminate\Http\Response
     */
    public function edit(GoodsReceipt $goodsReceipt)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateGoodsReceiptRequest  $request
     * @param  \App\Models\GoodsReceipt  $goodsReceipt
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGoodsReceiptRequest $request, GoodsReceipt $goodsReceipt)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GoodsReceipt  $goodsReceipt
     * @return \Illuminate\Http\Response
     */
    public function destroy(GoodsReceipt $goodsReceipt)
    {
        //
    }
}
