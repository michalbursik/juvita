<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Login extends Component
{
    public string $email = '';

    public string $password = '';

    public string $error = '';

    protected array $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.auth.login');
    }

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            session()->regenerate();

            if (Auth::user()->role === 'admin') {
                return redirect()->intended('/warehouses');
            }

            return redirect()->intended('/warehouses/'.Auth::user()->warehouse_id);
        }

        $this->error = 'Neplatné přihlašovací údaje.';
    }
}
