<?php

namespace App\Livewire\Movements;

use App\Models\Movement;
use App\Models\Product;
use App\Models\User;
use App\Models\Warehouse;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $productId = '';

    public $type = '';

    public $userId = '';

    public $issueWarehouseId = '';

    public $receiptWarehouseId = '';

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        if (auth()->user()->role === 'employee') {
            $this->receiptWarehouseId = auth()->user()->warehouse_id;
        }
    }

    public function updating()
    {
        $this->resetPage();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $query = Movement::with(['product', 'user', 'issueWarehouse', 'receiptWarehouse'])
            ->orderByDesc('created_at');

        if ($this->productId) {
            $query->where('product_id', $this->productId);
        }
        if ($this->type) {
            $query->where('type', $this->type);
        }
        if ($this->userId) {
            $query->where('user_id', $this->userId);
        }
        if ($this->issueWarehouseId) {
            $query->where('issue_warehouse_id', $this->issueWarehouseId);
        }
        if ($this->receiptWarehouseId) {
            $query->where('receipt_warehouse_id', $this->receiptWarehouseId);
        }

        return view('livewire.movements.index', [
            'movements' => $query->paginate(20),
            'products' => Product::orderBy('name')->get(),
            'users' => User::orderBy('name')->get(),
            'warehouses' => Warehouse::orderBy('name')->get(),
            'types' => [
                'receipt' => 'Příjemka',
                'issue' => 'Výdejka',
                'transmission' => 'Převodka',
                'check' => 'Kontrola',
            ],
        ]);
    }
}
