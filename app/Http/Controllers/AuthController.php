<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|regex:/^[a-zA-Z]+$/u|min:2|max:20',
            'last_name' => 'required|regex:/^[a-zA-Z]+$/u|min:2|max:20',
            'username' => 'required|regex:/^[a-zA-Z0-9_.]+$/u|min:5|max:12|unique:users',
            'password' => 'required|min:5|max:12',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'invalid field'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = User::create(array_merge($validator->validated(), ['password' => bcrypt($request->password)]));

        $loginToken = $user->loginToken()->create(['token' => bcrypt($user->id)]);

        auth()->login($user);

        return response()->json(['token' => $loginToken->token, 'role' => 'user']);
    }

    public function login(Request $request)
    {
        if (auth()->attempt(['username' => $request->username, 'password' => $request->password])) {
            $user = User::where('username', $request->username)->first();

            auth()->login($user);

            $user->loginToken()->update(['token' => bcrypt($user->id)]);

            return response()->json(['token' => $user->loginToken->token, 'role' => 'user']);
        } else {
            return response()->json(['message' => 'invalid login'], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function logout()
    {
        auth()->user()->loginToken()->update(['token' => null]);

        auth()->logout();

        return response()->json(['message' => 'logout success']);
    }
}
