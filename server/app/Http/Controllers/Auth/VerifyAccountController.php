<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\User;

class VerifyAccountController extends Controller
{
    /**
     * Login
     * 
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function verify(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'token' => 'required',
        ]);

        try {

            $token = JWTAuth::getToken();

            $apy = JWTAuth::getPayload($token)->toArray();

            $user = User::where('phone', $request->phone)->first();

            // Return error message if user not found.
            if(!$user) return response()->json(['error' => 'User not found.'], 404);

            $auth = auth()->setToken($request->token)->user();

            // Return error message if user and token mismatch.
            if ($auth->phone != $user->phone) return response()->json(['error' => 'Phone and Token did not matched.'], 498);

            $user->update(['phone_verified_at' => \Carbon\Carbon::now()]);

            return response()->json(['message' => 'Account has been verified.'], 201);
            
        } catch (TokenExpiredException $e) {

            return response()->json(['error' => 'Session Expired.', 'status_code' => 498], 498);

        } catch (TokenInvalidException $e) {

            return response()->json(['error' => 'Token invalid.', 'status_code' => 498], 498);

        } catch (JWTException $e) {

            return response()->json(['token_absent' => $e->getMessage()], 498);

        }
    }

}
