<?php

namespace App\Livewire\Users;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Create extends Component
{
    public $name;

    public $email;

    public $password;

    public $role = UserRole::EMPLOYEE->value;

    public $warehouseId;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
        'role' => 'required',
        'warehouseId' => 'required|exists:warehouses,id',
    ];

    public function mount()
    {
        if (auth()->user()->role->isEmployee()) {
            abort(403);
        }

        $firstWarehouse = Warehouse::first();
        if ($firstWarehouse) {
            $this->warehouseId = $firstWarehouse->id;
        }
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.users.create', [
            'warehouses' => Warehouse::orderBy('name')->get(),
        ]);
    }

    public function submit()
    {
        $this->validate();

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $this->role,
            'warehouse_id' => $this->warehouseId,
        ]);

        return redirect()->route('users.index');
    }
}
