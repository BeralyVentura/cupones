<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BusinessController extends Controller
{
    /**
     * Listar todos los negocios
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Business::all()
        ]);
    }

    /**
     * Crear un nuevo negocio
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'email' => 'required|email|unique:businesses',
            'phone' => 'required|string|max:20',
            'description' => 'nullable|string' // Campo opcional
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $business = Business::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Negocio creado exitosamente',
            'data' => $business
        ], 201);
    }

    /**
     * Mostrar un negocio específico
     */
    public function show($id)
    {
        try {
            $business = Business::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $business
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Negocio no encontrado'
            ], 404);
        }
    }

    /**
     * Actualizar un negocio
     */
    public function update(Request $request, $id)
    {
        try {
            $business = Business::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|string|max:255',
                'address' => 'sometimes|string',
                'email' => 'sometimes|email|unique:businesses,email,'.$id,
                'phone' => 'sometimes|string|max:20',
                'description' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $business->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Negocio actualizado exitosamente',
                'data' => $business
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Negocio no encontrado'
            ], 404);
        }
    }

    /**
     * Eliminar un negocio
     */
    public function destroy($id)
    {
        try {
            $business = Business::findOrFail($id);
            $business->delete();

            return response()->json([
                'success' => true,
                'message' => 'Negocio eliminado exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Negocio no encontrado'
            ], 404);
        }
    }
}