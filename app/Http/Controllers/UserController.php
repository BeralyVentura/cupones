<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;  // Importa el modelo Role

class UserController extends Controller
{
    // Listar todos los usuarios (solo administradores)
    public function index()
    {
        return User::all();

        if (Gate::denies('is-admin')) {
            abort(403, 'Acceso denegado');
        }
    
        // código que solo admins pueden ver
        $users = User::all();
        return response()->json($users);
    }

    // Crear un nuevo usuario
    public function store(Request $request)
    {
        // Validación de los datos (añadir validación según sea necesario)
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        // Crear el usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Asignar rol al usuario (puedes cambiar 'Usuario' por el rol que desees)
        $user->assignRole('Usuario');  // Asigna el rol correspondiente (Administrador, Empresa, etc.)

        return response()->json(['message' => 'Usuario creado y rol asignado', 'user' => $user], 201);
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
