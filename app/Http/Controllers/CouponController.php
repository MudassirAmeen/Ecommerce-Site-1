<?php

namespace App\Http\Controllers;

use App\Coupon;
use App\Models\Coupon as ModelsCoupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = ModelsCoupon::all();

        return view('AdminArea.Read.coupon', compact('coupons'));
    }

    public function create()
    {
        return view('AdminArea.create.coupon');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code' => 'required|unique:coupons',
            'discount' => 'required|numeric',
        ]);

        ModelsCoupon::create($validatedData);

        return redirect()->route('coupons.index')->with('success', 'Coupon created successfully.');
    }

    public function edit(ModelsCoupon $coupon)
    {
        return redirect()->back()->with('info', 'You Cannnot Edit The Coupon. Please Delete This And Create New One.');
    }

    public function update(Request $request, ModelsCoupon $coupon)
    {
        return redirect()->back()->with('info', 'You Cannnot Edit The Coupon. Please Delete This And Create New One.');
    }

    public function destroy(ModelsCoupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('coupons.index')->with('success', 'Coupon deleted successfully.');
    }
}
