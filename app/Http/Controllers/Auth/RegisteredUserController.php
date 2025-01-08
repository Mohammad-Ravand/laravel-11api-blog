<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Register\StoreCompleteUserInfos;
use App\Http\Requests\Api\Register\StoreRegisterUserSendEmailRequest;
use App\Http\Requests\Api\Register\StoreVerifyEmailCode;
use App\Http\Resources\ResponseResource;
use App\Jobs\SendEmailVerifyCodeJob;
use App\Models\User;
use App\Traits\ApiResponse;
use App\Traits\AuthenticatesWithSanctum;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Throwable;

class RegisteredUserController extends Controller
{
    use AuthenticatesWithSanctum;
    /**
     * Handle an incoming level one registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeEmail(StoreRegisterUserSendEmailRequest $request)
    {

        try {
            $code = rand(100000, 999999);
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                $user = User::create([
                    'email' => $request->email,
                    'verify_code' => $code,
                    'status' => 'sendEmail'
                ]);
            } else {
                $user->update([
                    'verify_code' => $code
                ]);
            }

            SendEmailVerifyCodeJob::dispatch($user);

            return new ResponseResource([
                'message' => 'Email sent successfully',
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
     * Handle an incoming level two registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function verifyEmailCode(StoreVerifyEmailCode $request)
    {
        try {
            $user = User::where('email', $request->email)
                ->where('verify_code', $request->code)
                ->first();
            if (!$user) {
                return new ResponseResource([
                    'error' => true,
                    'message' => 'Invalid Email Or Code',
                    'errors' => [],  // Replace with actual error details
                ]);
            }

            $updatedAt = $user->updated_at; // Retrieve the updated_at timestamp
            $now = Carbon::now();          // Get the current datetime

            // Check if the difference is more than 3 minutes
            if ($updatedAt->diffInMinutes($now) > 3) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'The verification code has expired.',
                ], 400);
            }

            $user->update([
                'verify_code' => null,
                'status' => 'verifiedEmail',
                'email_verified_at'=>Carbon::now()
            ]);


            return new ResponseResource([
                'message' => 'Email verified successfully',
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
     * Handle an incoming level 3 registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeUserInfo(StoreCompleteUserInfos $request)
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
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'active'=>true,
                'status'=>'completedInfos',
                'password' => Hash::make($request->string('password')),
            ]);


//            event(new Registered($user));

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
