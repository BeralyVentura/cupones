<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;

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
            'category' => 'sometimes|string|max:255',
            'contact' => 'sometimes|string|max:255',
            'address' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
        ]);
        

        $business->update($request->only([
            'name', 'description', 'category', 'contact', 'address', 'phone'
        ]));
        

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

    public function redeem($id)
    {
        $coupon = Coupon::findOrFail($id);

        // Verificar que end_date no sea null
        if (!$coupon->end_date) {
            return response()->json([
                'message' => 'Este cupón no tiene fecha de vencimiento definida.'
            ], 400);
        }

        // Validar que la fecha actual no sea posterior a end_date
        if (Carbon::now()->greaterThan(Carbon::parse($coupon->end_date))) {
            return response()->json([
                'message' => 'Este cupón ha expirado y no puede ser canjeado.'
            ], 400);
        }

        // (Opcional) Validar que aún no haya comenzado
        if ($coupon->start_date && Carbon::now()->lessThan(Carbon::parse($coupon->start_date))) {
            return response()->json([
                'message' => 'Este cupón aún no está disponible para ser canjeado.'
            ], 400);
        }

        return response()->json([
            'message' => 'Cupón canjeado correctamente',
            'coupon' => $coupon
        ]);
    }
}
