<?php

namespace App\Livewire\Overviews;

use App\Models\Movement;
use App\Models\Product;
use App\Models\Warehouse;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Index extends Component
{
    public $dateFrom;

    public $dateTo;

    public $data = [];

    public function mount()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $this->dateFrom = now()->subDays(7)->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
        $this->loadData();
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'dateFrom' || $propertyName === 'dateTo') {
            $this->loadData();
        }
    }

    public function loadData()
    {
        $movements = Movement::whereBetween('created_at', [
            Carbon::parse($this->dateFrom)->startOfDay(),
            Carbon::parse($this->dateTo)->endOfDay(),
        ])->get();

        $this->data = $this->calculateMovements($movements);
    }

    private function calculateMovements($movements): array
    {
        $data = [];
        $warehouses = Warehouse::all();
        $products = Product::all();

        foreach ($products as $product) {
            $data[$product->id] = [
                'product_name' => $product->name,
                'warehouses' => [],
            ];

            foreach ($warehouses as $warehouse) {
                $data[$product->id]['warehouses'][$warehouse->id] = [
                    'warehouse_name' => $warehouse->name,
                    'price_levels' => [],
                ];
            }
        }

        foreach ($movements as $movement) {
            $priceKey = (string) $movement->price;

            if ($movement->receipt_warehouse_id) {
                if (! isset($data[$movement->product_id]['warehouses'][$movement->receipt_warehouse_id]['price_levels'][$priceKey])) {
                    $data[$movement->product_id]['warehouses'][$movement->receipt_warehouse_id]['price_levels'][$priceKey] = [
                        'amount' => 0,
                        'price' => $movement->price,
                    ];
                }
                $data[$movement->product_id]['warehouses'][$movement->receipt_warehouse_id]['price_levels'][$priceKey]['amount'] += $movement->amount;
            }

            if ($movement->issue_warehouse_id) {
                if (! isset($data[$movement->product_id]['warehouses'][$movement->issue_warehouse_id]['price_levels'][$priceKey])) {
                    $data[$movement->product_id]['warehouses'][$movement->issue_warehouse_id]['price_levels'][$priceKey] = [
                        'amount' => 0,
                        'price' => $movement->price,
                    ];
                }
                $data[$movement->product_id]['warehouses'][$movement->issue_warehouse_id]['price_levels'][$priceKey]['amount'] -= $movement->amount;
            }
        }

        return $data;
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.overviews.index');
    }
}
