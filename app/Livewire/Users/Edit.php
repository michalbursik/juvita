<?php

namespace App\Livewire\Users;

use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Edit extends Component
{
    public User $user;

    public $name;

    public $email;

    public $password;

    public $role;

    public $warehouseId;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$this->user->id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:admin,employee',
            'warehouseId' => 'required|exists:warehouses,id',
        ];
    }

    public function mount(User $user)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->warehouseId = $user->warehouse_id;
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.users.edit', [
            'warehouses' => Warehouse::orderBy('name')->get(),
        ]);
    }

    public function submit()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'warehouse_id' => $this->warehouseId,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        $this->user->update($data);

        return redirect()->route('users.index');
    }
}
