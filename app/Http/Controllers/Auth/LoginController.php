<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * AuthService instance
     */
    protected AuthService $authService;

    /**
     * Create a new controller instance.
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
        // Middleware is handled in routes/web.php for Laravel 11
    }

    /**
     * Show the login form
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Handle login attempt
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        try {
            $user = $this->authService->login(
                $request->only('username', 'password'),
                $request->boolean('remember')
            );

            // Redirect based on user role
            return $this->redirectByRole($user);

        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput($request->only('username', 'remember'));
        }
    }

    /**
     * Handle logout
     */
    public function logout(): RedirectResponse
    {
        $this->authService->logout();

        return redirect()->route('login')
            ->with('success', 'Anda berhasil logout.');
    }

    /**
     * Redirect user based on their role
     */
    protected function redirectByRole($user): RedirectResponse
    {
        $message = "Selamat datang, {$user->name}!";

        // Authorization - Redirect based on role
        if ($user->isAdmin()) {
            return redirect()->route('dashboard.admin')->with('success', $message);
        }

        if ($user->isGudang()) {
            return redirect()->route('dashboard.gudang')->with('success', $message);
        }

        if ($user->isPemilik()) {
            return redirect()->route('dashboard.pemilik')->with('success', $message);
        }

        // Default fallback
        return redirect()->route('dashboard')->with('success', $message);
    }
}
