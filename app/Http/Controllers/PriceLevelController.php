<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePriceLevelRequest;
use App\Http\Requests\UpdatePriceLevelRequest;
use App\Models\ProductWarehouse;
use Illuminate\Http\Request;

class PriceLevelController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductWarehouse::query();

        $query->when($request->input('warehouse_id'), function ($query) use ($request) {
                $query->where('warehouse_id', $request->input('warehouse_id'));
        });

        $query->when($request->input('product_id'), function ($query) use ($request) {
            $query->where('product_id', $request->input('product_id'));
        });

        $priceLevels = $query
            ->orderByDesc('created_at')
            ->paginate(null, ['*'], 'currentPage');

        return responder()->success($priceLevels)->respond();
    }
}
