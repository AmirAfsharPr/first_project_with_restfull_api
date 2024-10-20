<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function store(LoginRequest $request)
    {

        /**
         * @var User $user
         */

        $user = User::query()->where('email',$request->get('email'))->first();

        //(1) pluck all permissions of user
        $permissions = $user->role->permissions()->pluck('title')->toArray();

        if (! Hash::check($request->get('password'),$user->password)) {
            return response()->json([
                'data' =>[
                    'message' => "Wrong password"
                ]
            ])->setStatusCode(401);
        }

        $user->tokens()->delete();

        //token from laravel sanctum package
        //(2) second input of createToken is for abilities and use to middlewares
        return response()->json([
            'data' => [
                'token' => $user->createToken('accessToken',$permissions)->plainTextToken
            ]
        ])->setStatusCode(200);
    }


    public function destroy(Request $request)
    {

        auth()->user()->tokens()->delete();

        return response()->json([
            'data' => [
                'message' => 'you have been logged out'
            ]
        ])->setStatusCode(200);

    }
}
