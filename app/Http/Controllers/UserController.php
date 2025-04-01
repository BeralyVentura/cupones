<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Listar todos los usuarios (solo administradores)
    public function index()
    {
        return User::all();
    }

    // Crear un nuevo usuario
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'message' => 'Usuario creado correctamente',
            'user' => $user
        ], 201);
    }

    // Obtener un usuario específico
    public function show(User $user)
    {
        return response()->json($user);
    }

    // Actualizar un usuario específico
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'string',
            'email' => 'email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }

        $user->update($validated);

        return response()->json($user);
    }

    // Eliminar un usuario
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(['message' => 'Usuario eliminado']);
    }
}
