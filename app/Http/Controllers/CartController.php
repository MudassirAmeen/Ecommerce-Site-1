<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');

        $cartItems = Session::get('cartItems', []);
        $cartItems[] = [
            'product_id' => $productId,
            'quantity' => $quantity,
            'price' => Product::where('id', $productId)->pluck('price'),
        ];
        $cartItems = Session::get('cartItems', []);
        $productInCart = [];
        $productExists = false;
        foreach ($cartItems as $key => $item) {
            if ($item['product_id'] == $productId) {
                $productExists = true;
                break;
            }
            $productInCart[] = $item['product_id'];
        }

        if ($productExists) {
            // Product already exists in the cart
            return redirect()->back()->with('info', 'Product is already in the cart.');
        }



        // Add the new product to the cartItems array
        $cartItems[] = ['product_id' => $productId, 'quantity' => $quantity, 'price' => Product::where('id', $productId)->pluck('price')->first()];
        Session::put('cartItems', $cartItems);

        // Get the Total Price
        $totalPriceOfAllProductInCart = 0;
        $cartItems = session()->get('cartItems', []);
        foreach ($cartItems as $item) {
            $totalPriceOfAllProductInCart += $item['price'] * $item['quantity'];
        }
        // Save the Total Price
        Session::put('totalPrice', $totalPriceOfAllProductInCart);
        $savedTotalPrice = Session::get('totalPrice');

        // Check if the Coupons is applied
        if (Session::has('coupon')) {
            $couponDiscount = Session::get('coupon');
            $savedTotalPrice -= $couponDiscount;
            Session::put('totalPrice', $savedTotalPrice);
        }

        return redirect()->back()->with('success', 'Item added to cart.');
    }

    public function updateQuantity(Request $request, $id)
    {
        $quantity = $request->input('quantity');
        $cartItems = Session::get('cartItems', []);
        foreach ($cartItems as $key => $item) {
            if ($item['product_id'] == $id) {
                $cartItems[$key]['quantity'] = $quantity;
                break;
            }
        }

        $product = Product::find($id);
        $price = $product->price;
        $totalPrice = $quantity * $price;
        Session::put('cartItems', $cartItems);

        // Get the Total Price
        $totalPriceOfAllProductInCart = 0;
        $cartItems = session()->get('cartItems', []);
        foreach ($cartItems as $item) {
            $totalPriceOfAllProductInCart += $item['price'] * $item['quantity'];
        }
        // Save the Total Price
        Session::put('totalPrice', $totalPriceOfAllProductInCart);
        $savedTotalPrice = Session::get('totalPrice');

        // Check if the Coupons is applied
        if (Session::has('coupon')) {
            $couponDiscount = Session::get('coupon');
            $savedTotalPrice -= $couponDiscount;
        }
        Session::put('totalPrice', $totalPriceOfAllProductInCart);

        return response()->json([
            'quantity' => $quantity,
            'total_price' => $totalPrice,
            'totalPriceOfAllProductInCart' => $savedTotalPrice,
        ]);
    }

    public function removeItem($id)
    {
        $cartItems = Session::get('cartItems', []);
        foreach ($cartItems as $key => $item) {
            if ($item['product_id'] == $id) {
                unset($cartItems[$key]);
                break;
            }
        }

        session()->put('cartItems', $cartItems);

        // Get the Total Price
        $totalPriceOfAllProductInCart = 0;
        $cartItems = session()->get('cartItems', []);
        foreach ($cartItems as $item) {
            $totalPriceOfAllProductInCart += $item['price'] * $item['quantity'];
        }
        // Save the Total Price
        Session::put('totalPrice', $totalPriceOfAllProductInCart);

        $savedTotalPrice = Session::get('totalPrice');

        // Check if the Coupons is applied
        if (Session::has('coupon')) {
            $couponDiscount = Session::get('coupon');
            $savedTotalPrice -= $couponDiscount;
            Session::put('totalPrice', $savedTotalPrice);
        }

        return redirect()->back()->with('success', 'Item removed from cart.');
    }

    public function showCart()
    {

        // Get the Total Price
        $totalPriceOfAllProductInCart = 0;
        $cartItems = session()->get('cartItems', []);
        foreach ($cartItems as $item) {
            $totalPriceOfAllProductInCart += $item['price'] * $item['quantity'];
        }
        // Save the Total Price
        Session::put('totalPrice', $totalPriceOfAllProductInCart);

        $savedTotalPrice = Session::get('totalPrice');

        // Check if the Coupons is applied
        if (Session::has('coupon')) {
            $couponDiscount = Session::get('coupon');
            $savedTotalPrice -= $couponDiscount;
            Session::put('totalPrice', $savedTotalPrice);
        }

        $cartItems = [];

        $cartItemsSession = Session::get('cartItems', []);
        $productIds = array_column($cartItemsSession, 'product_id');
        $quantities = array_column($cartItemsSession, 'quantity');
        $cartItems = Product::whereIn('id', $productIds)->get()->map(function ($product, $key) use ($quantities) {
            $product->quantity = $quantities[$key];
            return $product;
        });

        return view('FrontEnd.cart', compact('cartItems'));
    }

    public function checkCoupon(Request $request)
    {
        $code = $request->input('code');

        $discount = Coupon::where('code', $code)->pluck('discount')->first();

        if ($discount != null) {
            $sessionPrice = Session::get('totalPrice');
            $totalPrice = $sessionPrice - $discount;

            Session::put('totalPrice', $totalPrice);
            Session::put('coupon', $discount);

            return redirect()->back()->with('success', 'Coupons Founded. You have get $' . $discount . ' discount.');
        }

        return redirect()->back()->with('error', 'Coupons Not Found.');
    }

    public function checkout()
    {

        // Get the Total Price
        $totalPriceOfAllProductInCart = 0;
        $cartItems = session()->get('cartItems', []);
        foreach ($cartItems as $item) {
            $totalPriceOfAllProductInCart += $item['price'] * $item['quantity'];
        }

        // Save the Total Price
        Session::put('totalPrice', $totalPriceOfAllProductInCart);

        $savedTotalPrice = Session::get('totalPrice');

        // Check if the Coupons is applied
        if (Session::has('coupon')) {
            $couponDiscount = Session::get('coupon');
            $savedTotalPrice -= $couponDiscount;
            Session::put('totalPrice', $savedTotalPrice);
        }

        $cartItems = [];

        $cartItemsSession = Session::get('cartItems', []);
        $productIds = array_column($cartItemsSession, 'product_id');
        $quantities = array_column($cartItemsSession, 'quantity');
        $cartItems = Product::whereIn('id', $productIds)->get()->map(function ($product, $key) use ($quantities) {
            $product->quantity = $quantities[$key];
            return $product;
        });

        $userData = User::find(auth()->user()->id);
        return view('FrontEnd.checkout', compact('cartItems', 'userData'));
    }

    public function checkoutSubmit(Request $request)
    {
        dd($request);
    }
}
