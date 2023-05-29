<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userId   = auth()->user()->id;
        $products = Product::where('user_id', $userId)->get();
        return view('AdminArea.Read.product', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $userId     = auth()->user()->id;
        $categories = Category::where('user_id',  $userId)->get();
        return view('AdminArea.create.product', compact('categories'));
    }

    /**
     * Store a newly created product in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name'              => 'required',
            'long_description'  => 'required',
            'short_description' => 'required',
            'price'             => 'required',
            'images.*'          => 'required|image',
            'sizes'             => 'array|required',
            'category_id'       => 'required'
        ]);

        $product = Product::create([
            'name'              => $validatedData['name'],
            'long_description'  => $validatedData['long_description'],
            'short_description' => $validatedData['short_description'],
            'price'             => $validatedData['price'],
            'sizes'             => json_encode($validatedData['sizes']),   // Convert array to JSON string
            'category_id'       => $validatedData['category_id'],
            'user_id'           => auth()->user()->id,
        ]);

        // Handle image uploads if provided
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $imageName      = $product->id . $image->getClientOriginalName();  // Get the original image name
                $image->storeAs('public/images', $imageName);                      // Store the image with the original name
                $images[] = $imageName;
            }
            $product->images = $images;
            $product->save();
        }

        return redirect()->route('product.index')->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified product.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $product->load(['category', 'user']);
        return view('AdminArea.single.product', compact('product'));
    }


    /**
     * Show the form for editing the specified product.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $userId     = auth()->user()->id;
        $categories = Category::where('user_id',  $userId)->get();
        return view('AdminArea.Update.product', compact('product', 'categories'));
    }

    /**
     * Update the specified product in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'name'              => 'required',
            'long_description'  => 'required',
            'short_description' => 'required',
            'price'             => 'required',
            'image'             => 'nullable|image',
            'category_id'       => 'required',
            'sizes'             => 'nullable|array',
        ]);

        // Handle image upload if provided
        if ($request->hasFile('image')) {
            $imagePath              = $request->file('image')->store('images', 'public');
            $validatedData['image'] = $imagePath;
        }

        // Remove previously uploaded images from the request
        unset($validatedData['image']);

        $product->update($validatedData);

        // Handle image uploads if provided
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $imageName      = $product->id . $image->getClientOriginalName();  // Get the original image name
                $image->storeAs('public/images', $imageName);                      // Store the image with the original name
                $images[] = $imageName;
            }
            $product->images = $images;
            $product->save();
        }

        return redirect()->route('product.index')->with('success', 'Product updated successfully.');
    }


    /**
     * Remove the specified product from the database.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        // Delete associated images
        $images = json_decode($product->images, true);
        foreach ($images as $image) {
            // Construct the full image path
            $imagePath = public_path('storage/images/' . $image);

            // Delete the image file
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        $product->delete();

        return redirect()->route('product.index')->with('success', 'Product deleted successfully.');
    }
}
