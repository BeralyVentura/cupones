<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class BusinessController extends Controller
{
    public function index()
    {
        $businesses = Business::all();
        return response()->json($businesses);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category'=> 'required|string|max:255',
            'contact' => 'required|int'


        ]);

        $business = Business::create([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => Auth::id(),
            'category'=> $request->category,
            'contact'=> $request->contact

        ]);

        // Obtener el usuario autenticado
        $user = Auth::user();

        // Verificar si el rol "Empresa" ya existe y si el usuario lo tiene
        if (!$user->hasRole('Empresa')) {
            $user->assignRole('Empresa'); // Asignar el rol automáticamente
        }

        return response()->json([
            'message' => 'Negocio creado y rol asignado si era necesario.',
            'business' => $business
        ], 201);
    }

    public function show($id)
    {
        $business = Business::findOrFail($id);
        return response()->json($business);
    }

    public function update(Request $request, $id)
    {
        $business = Business::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',


        ]);

        $business->update($request->only(['name', 'description']));

        return response()->json([
            'message' => 'Negocio actualizado correctamente.',
            'business' => $business
        ]);
    }

    public function destroy($id)
    {
        $business = Business::findOrFail($id);
        $business->delete();

        return response()->json(['message' => 'Negocio eliminado.']);
    }
}
