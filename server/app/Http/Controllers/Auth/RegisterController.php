<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\User as UserResource;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Notifications\UserVerifyNotification;
use App\Tools;
use App\Models\UserLoginUrl;

class RegisterController extends Controller
{
    use RegistersUsers;
    
    /**
     * Register
     * 
     * @param RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(RegisterRequest $request)
    {
        $right_level = $request->input('right_level', User::$RIGHT_LEVELS["none"]);
        $current_user = \Auth::user();

        if($current_user->right_level!=User::$RIGHT_LEVELS["admin"])
        {
            return response()->json(["error" => "you have not the priviledges to add an user with a right level of " . $right_level]);
        }

        $phone = Tools::phoneToFormat($request->phone, $request->country_code);
        if($phone==null)
        {
            return response()->json(['error' => 'Invalid phone format'], 404);
        }

        // Create user data
        $user = User::create([
            'name' => $request->name,
            'phone' => $phone,
            'password' => Hash::make($request->password),
            'right_level' => $right_level,
        ]);

        //  Generate token
        $token = auth()->fromUser($user);

        // Transform user data
        $data = new UserResource($user);

        $login_url = null;
        if($request->with_login_url)
        {
            $user_uuid = UserLoginUrl::where('id_user', $user->id)->first()->id;
            $login_url = route('login_url', ['uuid' => $user_uuid]);
        }

        return response()->json(compact('token', 'data', 'login_url'));

    }
}
