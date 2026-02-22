<?php

namespace App\Livewire\Checks;

use App\Models\Check;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Show extends Component
{
    public Check $check;

    public function mount(Check $check)
    {
        $this->check = $check->load(['user', 'warehouse', 'products']);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.checks.show');
    }
}
