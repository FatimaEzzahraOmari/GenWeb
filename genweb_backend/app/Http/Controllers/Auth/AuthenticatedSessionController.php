<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest; // Si tu utilises LoginRequest
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    public function store(LoginRequest $request)
    {
        try {
            \Log::info('Login attempt', $request->all());

            // Valide les données avec LoginRequest (ou manuellement si tu ne l'utilises pas)
            $request->authenticate(); // Si tu utilises LoginRequest

            // Authentification réussie, récupérer l'utilisateur et générer un personnal_access_token
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json(['message' => 'Connexion réussie', 'token' => $token]);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la connexion', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Erreur serveur', 'details' => $e->getMessage()], 500);
        }
    }
}