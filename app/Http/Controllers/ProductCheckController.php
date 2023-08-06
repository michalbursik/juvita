<?php

namespace App\Http\Controllers;

use App\Models\Check;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductCheckController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $warehouse_uuid = $request->input('warehouse_uuid');
        $check_uuid = $request->input('check_uuid');

        $check = Check::uuid($check_uuid);

        $productChecks = $check->productChecks()
            ->whereHas('check', function ($query) use ($warehouse_uuid) {
                $query->where('warehouse_uuid', $warehouse_uuid);
            })
            ->get();

        return responder()->success($productChecks)->respond();
    }
}
