<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

trait AuthenticatesWithSanctum
{
    /**
     * Login user and generate Sanctum token.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    public function login(string $email,string $password): Collection
    {


        // Attempt to authenticate the user
        if (!Auth::attempt(['email'=>$email,'password'=>$password])) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Optionally revoke existing tokens
        $user->tokens()->delete();

        // Generate a new token
        $token = $user->createToken('access_token')->plainTextToken;

        // Return user data and token
        return collect([
            'user' => $user,
            'token' => $token,
        ]);
    }
}
