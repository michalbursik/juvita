<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    #[Layout('layouts.app')]
    public function render()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        return view('livewire.users.index', [
            'users' => User::with('warehouse')->orderBy('name')->paginate(20),
        ]);
    }

    public function deleteUser($id)
    {
        if ($id === auth()->id()) {
            return;
        }
        $user = User::findOrFail($id);
        $user->delete();
    }
}
