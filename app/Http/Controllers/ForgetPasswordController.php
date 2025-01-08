<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\Login\StoreChangePasswordRequest;
use App\Http\Requests\Api\Login\StoreForgetPasswordRequest;
use App\Http\Resources\ResponseResource;
use App\Jobs\SendEmailVerifyCodeJob;
use App\Models\User;
use App\Traits\AuthenticatesWithSanctum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Throwable;

class ForgetPasswordController extends Controller
{
    use AuthenticatesWithSanctum;

    /**
     * Store a forget password code.
     */
    public function forgetPassword(StoreForgetPasswordRequest $request)
    {
        try {
            $code = rand(100000, 999999);
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return new ResponseResource([
                    'error' => true,
                    'message' => 'Invalid Email Or Code',
                    'errors' => [],  // Replace with actual error details
                ]);
            }
            $user->update([
                'verify_code' => $code,
                'status'=>'forgetPassword'
            ]);

            SendEmailVerifyCodeJob::dispatch($user);

            return new ResponseResource([
                'message' => 'Email code sent successfully',
                'data' => $user,  // Replace with actual data
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
     * Store a change password.
     */
    public function changePassword(StoreChangePasswordRequest $request)
    {
        try {

            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return new ResponseResource([
                    'error' => true,
                    'message' => 'Invalid Email Or Code',
                    'errors' => [],  // Replace with actual error details
                ]);
            }

            $user->update([
                'active'=>true,
                'status'=>'completedInfos',
                'password' => Hash::make($request->string('password')),
            ]);

            $credentials = $this->login($request->email,$request->password);
            return new ResponseResource([
                'message' => 'Email verified successfully',
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

}
