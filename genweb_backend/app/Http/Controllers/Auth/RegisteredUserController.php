<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class RegisteredUserController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        \Log::info('Register attempt', $request->all());

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'phone' => 'required|string|max:20',
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'],
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            \Log::info('User registered successfully', ['email' => $user->email]);

            return response()->json([
                'message' => 'Inscription réussie',
                'token' => $token,
            ], 201);
        } catch (ValidationException $e) {
            \Log::error('Validation failed', ['errors' => $e->errors()]);
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Registration failed', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Erreur lors de l\'inscription',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}