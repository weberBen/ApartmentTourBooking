<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\User;
use Carbon\Carbon;
use Illuminate\Hashing\BcryptHasher;
use App\Http\Resources\User as UserResource;
use App\Tools;
use App\Models\UserLoginUrl;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Login
     * 
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(LoginRequest $request)
    {
        $country_code = $request->country_code;

        $phone = Tools::phoneToFormat($request->phone, $country_code);
        if($phone==null)
        {
            return response()->json(['error' => 'Invalid phone format'], 404);
        }

        // Get User by email
        $user = User::where('phone', $phone)->first();

        // Return error message if user not found.
        if(!$user) return response()->json(['error' => 'Invalid username or password'], 404);

        // Account Validation
        if (!(new BcryptHasher)->check($request->input('password'), $user->password)) {
            // Return Error message if password is incorrect
            return response()->json(['error' => 'Invalid username or password'], 401);
        }
        
        // Get phone and password from Request
        $credentials = ['phone' => $phone, 'password' => $request->password];

        try {
            // Login Attempt
            if (! $token = auth()->attempt($credentials)) {
                // Return error message if validation failed
                return response()->json(['error' => 'invalid_credentials'], 401);

            }
        } catch (JWTException $e) {
            // Return Error message if cannot create token. 
            return response()->json(['error' => 'could_not_create_token'], 500);

        }
        
        // transform user data
        $data = new UserResource($user);

        return response()->json(compact('token', 'data'));
    }

    public function loginUrl(Request $request)
    {
        if(!env('LOGIN_WITH_URL'))
        {
            return response()->json(['error' => 'login url has been disabled'], 401);
        }

        $uuid = $request->uuid;

        $user_url = UserLoginUrl::find($uuid);

        if(!isset($user_url))
        {
            return response()->json([], 401);
        }else if(!$user_url->active)
        {
            return response()->json([], 401);
        }

        $user = User::find($user_url->id_user);

        $token = JWTAuth::fromUser($user);

         // transform user data
         $data = new UserResource($user);

        return response()->json(compact('token', 'data'));
    }
}
