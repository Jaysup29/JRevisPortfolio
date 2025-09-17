<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Http;

class Login extends Component
{
    public $email = '';
    public $password = '';

    public function login()
    {
        $credentials = [
            'email' => $this->email,
            'password' => $this->password,
        ];

        if (!$token = JWTAuth::attempt($credentials)) {
            $this->addError('email', 'Invalid credentials.');
            return;
        }

        // Store the token in session for now
        session(['jwt' => $token]);

        // ✅ Redirect to dashboard
        return redirect()->route('dashboard');
    }
    
    public function render()
    {
        return view('livewire.auth.login');
    }
}
