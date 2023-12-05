<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;


class ProductController extends Controller
{
    public function index()
    {
        $products = Product::paginate(10);

        return view('products.index', compact('products'));
    }

    public function buy(Product $product)
    {
        return view('products.buy', compact('product'));
    }
    
    public function stripeCharge(Request $request)
    {
        $productId = $request->input('product_id');
        $product = Product::find($productId);

        try {

            // Charge the user
            Auth::user()->charge($product->price * 100, $request->input('payment_method') ,[
                'description' => $product->name,
            ]);

            return redirect()->route('products.index')->with('success', 'Payment successful!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['stripe_error' => $e->getMessage()]);
        }
    }

}
