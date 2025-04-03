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
            'business_id' => 'required|int',
            'regular_price' => 'required|int',
            'offer_price' => 'required|int'

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
            'title' => 'required|string|max:255',
            'discount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'business_id' => 'required|int',
            'regular_price' => 'required|int',
            'offer_price' => 'required|int'
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

        // Verificar si el cupón ha expirado
        if (now()->greaterThan($coupon->end_date)) {
            return response()->json([
                'message' => 'Este cupón ha expirado y no puede ser canjeado.'
            ], 400);
        }

        // Aquí puedes agregar más lógica, como si ya ha sido canjeado

        return response()->json([
            'message' => 'Cupón canjeado correctamente',
            'coupon' => $coupon
        ]);
    }
}