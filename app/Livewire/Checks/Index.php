<?php

namespace App\Livewire\Checks;

use App\Models\Check;
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
        return view('livewire.checks.index', [
            'checks' => Check::with(['user', 'warehouse'])
                ->orderByDesc('created_at')
                ->paginate(10),
        ]);
    }
}
