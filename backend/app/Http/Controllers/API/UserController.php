<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\JsonResponse;
use function Laravel\Prompts\error;

class UserController extends Controller
{
    /**
     * Handle an authentication attempt.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();
            $user = $request->user();

            $success = true;
            $message = 'User login successfully';
            $userResponse = [
                'name' => $user->name,
                'email' => $user->email,
                'hasSubscription' => $user->hasSubscription(),
                'isAdmin' => $user->isAdmin()
            ];
        } else {
            $success = false;
            $message = 'Неверный логин или пароль';
            $userResponse = null;
        }


        $response = [
            'success' => $success,
            'message' => $message,
            'user' => $userResponse
        ];

        return response()->json($response, 201);

    }


    public function register(Request $request)
    {

        $credentials = $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!is_null($user)) {
            return response()->json(['message' => 'User with this email already exists.'], 409);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $request->session()->regenerate();

        return response()->json(['message' => 'User registered successfully', 'user' => [
            'name' => $user->name,
            'email' => $user->email,
            'hasSubscription' => $user->hasSubscription(),
            'isAdmin' => $user->isAdmin()
        ]], 201);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    public function fetchUser(Request $request): JsonResponse
    {
        $user = $request->user();

        $response = [
            'success' => true,
            'message' => '',
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'hasSubscription' => $user->hasSubscription(),
                'isAdmin' => $user->isAdmin()
            ]
        ];

        return response()->json($response, 201);
    }
}
