<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'event_creator'
        ]);

        $user->assignRole('event_creator');

        return response()->json(['message' => 'User registered successfully'], 201);
    }

   

    public function login(Request $request)
        {
            try {
                $request->validate([
                    'email' => 'required|string|email',
                    'password' => 'required|string',
                ]);

                $credentials = $request->only('email', 'password');

                if (!Auth::attempt($credentials)) {
                    Log::warning('Intento de inicio de sesión fallido para: ' . $request->email);
                    return response()->json(['error' => 'Unauthorized'], 401);
                }

                Log::info('Autenticación exitosa para: ' . $request->email); // Log después de autenticación exitosa

                $user = Auth::user(); // Obtener el usuario autenticado
                $token = $user->createToken('auth_token')->plainTextToken;
    
                Log::info('Token generado para el usuario: ' . $user->email); // Log después de generar el token
    
                return response()->json(['token' => $token, 'role' => $user->role]);
            } catch (\Exception $e) {
                // Log detalles adicionales del error
                Log::error('Error al procesar inicio de sesión: ' . $e->getMessage());
                Log::error('Trace: ' . $e->getTraceAsString());

                // Retornar respuesta de error genérica
                //return response()->json(['error' => 'Internal Server Error'], 500);
            }
        }


    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
