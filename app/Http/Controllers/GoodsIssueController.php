<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGoodsIssueRequest;
use App\Http\Requests\UpdateGoodsIssueRequest;
use App\Models\GoodsIssue;

class GoodsIssueController extends Controller
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
     * @param  \App\Http\Requests\StoreGoodsIssueRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGoodsIssueRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GoodsIssue  $goodsIssue
     * @return \Illuminate\Http\Response
     */
    public function show(GoodsIssue $goodsIssue)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\GoodsIssue  $goodsIssue
     * @return \Illuminate\Http\Response
     */
    public function edit(GoodsIssue $goodsIssue)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateGoodsIssueRequest  $request
     * @param  \App\Models\GoodsIssue  $goodsIssue
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGoodsIssueRequest $request, GoodsIssue $goodsIssue)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GoodsIssue  $goodsIssue
     * @return \Illuminate\Http\Response
     */
    public function destroy(GoodsIssue $goodsIssue)
    {
        //
    }
}
