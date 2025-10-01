<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Services\AuthService;
use Illuminate\Support\Facades\Http;

class Login extends Component
{
    public $email = '';
    public $password = '';

    private AuthService $authService;

    public function boot(AuthService $authService)
    {
        $this->authService = app(AuthService::class);
    }

    
    public function login()
    {
        $credentials = [
            'email'    => $this->email,
            'password' => $this->password,
        ];

        $result = $this->authService->attemptLogin($credentials);

        if (! $result) {
            $this->addError('email', 'Invalid credentials.');
            return;
        }

        // Store JWT in session
        session(['jwt' => $result['access_token']]);

        return redirect()->route('dashboard');
    }
    
    public function render()
    {
        return view('livewire.auth.login');
    }
}
