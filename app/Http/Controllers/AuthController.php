<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Método para iniciar sesión
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        // Crear token de acceso
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    // Método para registrar un nuevo usuario
    public function register(Request $request)
    {
        // Validar los datos de registro
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',  // Confirmación de contraseña
        ]);

        // Crear el usuario
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']), // Cifrar la contraseña
        ]);

        // Crear token de acceso
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Usuario registrado con éxito',
            'token' => $token,
        ], 201);
    }

    // Método para cerrar sesión
    public function logout(Request $request)
    {
        // Eliminar el token actual
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Cierre de sesión exitoso']);
    }
}
