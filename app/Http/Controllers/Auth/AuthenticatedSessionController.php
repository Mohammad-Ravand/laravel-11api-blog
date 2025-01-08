<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\ResponseResource;
use App\Models\User;
use App\Traits\AuthenticatesWithSanctum;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Throwable;

class AuthenticatedSessionController extends Controller
{
    use AuthenticatesWithSanctum;
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return new ResponseResource([
                    'error' => true,
                    'message' => 'Invalid Email Or Password',
                    'errors' => [],  // Replace with actual error details
                ]);
            }

            $credentials = $this->login($request->email,$request->password);
            return new ResponseResource([
                'message' => 'Logged in successfully',
                'data' => $credentials
            ]);

        } catch (Throwable $th) {
            // Example of error response
            return new ResponseResource([
                'error' => true,
                'message' => 'An error occurred',
                'errors' => [],  // Replace with actual error details
            ]);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    }
}
