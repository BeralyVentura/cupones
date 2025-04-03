<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    // Obtener todos los cupones
    public function index()
    {
        return Coupon::all();
    }

    // Crear un nuevo cupón
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'discount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $coupon = Coupon::create($validated);

        return response()->json([
            'message' => 'Cupón creado correctamente',
            'coupon' => $coupon
        ], 201);
    }

    // Mostrar un cupón específico
    public function show($id)
    {
        return Coupon::findOrFail($id);
    }

    // Actualizar un cupón
    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'discount' => 'sometimes|required|numeric|min:0',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date|after:start_date',
        ]);

        $coupon->update($validated);

        return response()->json($coupon);
    }

    // Eliminar un cupón
    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        return response()->json(['message' => 'Cupón eliminado']);
    }

    // Canjear un cupón
    public function redeem($id)
    {
        $coupon = Coupon::findOrFail($id);

        // Aquí puedes agregar lógica para verificar si el cupón ya ha sido canjeado o no

        return response()->json([
            'message' => 'Cupón canjeado correctamente',
            'coupon' => $coupon
        ]);
    }
}