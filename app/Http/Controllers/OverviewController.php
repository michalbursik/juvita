<?php

namespace App\Http\Controllers;

use App\Models\Movement;
use App\Models\Product;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class OverviewController extends Controller
{
    public function index(Request $request)
    {
        $date_from = Carbon::parse($request->get('date_from')) ?? now()->subDays(7);
        $date_to = Carbon::parse($request->get('date_to')) ?? now();

        $query = Movement::query();

        $query->whereBetween('created_at', [$date_from, $date_to]);

        $movements = $query->get();

        $data = $this->calculateMovements($movements);

        return response()->json($data);
    }

    private function calculateMovements(Collection $movements): array
    {
        $data = [];

        $warehouses = Warehouse::where('active', true)->get();
        $products = Product::all();

        foreach ($products as $product) {
            $data[$product->id] = [
                'product_name' => $product->name,
                'warehouses' => [

                ],
            ];

            foreach ($warehouses as $warehouse) {
                $data[$product->id]['warehouses'][$warehouse->id] = [
                    'warehouse_name' => $warehouse->name,
                    'price_levels' => [
                    ],
                ];
            }
        }

        $movements->each(function (Movement $movement) use (&$data) {
            if ($movement->receipt_warehouse_id) {
                if (isset($data[$movement->product_id]['warehouses'][$movement->receipt_warehouse_id]['price_levels'][$movement->price])) {
                    $data[$movement->product_id]['warehouses'][$movement->receipt_warehouse_id]['price_levels'][$movement->price]['amount'] += $movement->amount;
                } else {
                    $data[$movement->product_id]['warehouses'][$movement->receipt_warehouse_id]['price_levels'][$movement->price] = [
                        'amount' => $movement->amount,
                        'price' => $movement->price,
                    ];
                }
            }

            if ($movement->issue_warehouse_id) {
                if (isset($data[$movement->product_id]['warehouses'][$movement->issue_warehouse_id]['price_levels'][$movement->price])) {
                    $data[$movement->product_id]['warehouses'][$movement->issue_warehouse_id]['price_levels'][$movement->price]['amount'] -= $movement->amount;
                } else {
                    $data[$movement->product_id]['warehouses'][$movement->issue_warehouse_id]['price_levels'][$movement->price] = [
                        'amount' => $movement->amount,
                        'price' => $movement->price,
                    ];
                }
            }
        });

        return $data;
    }
}
