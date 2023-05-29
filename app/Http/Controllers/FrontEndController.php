<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class FrontEndController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('FrontEnd.home', compact('products'));
    }

    public function shop(Request $request, $category = null)
    {
        $categories = Category::withCount('products')->get();
        $products = Product::with('category')->paginate(9);
        $sizes = Product::pluck('sizes')->flatten();

        if ($category) {
            $categoryID = Category::where('name', $category)->value('id');
            $products = Product::with('category')->where('category_id', $categoryID)->paginate(9);
        }
        if ($request->search) {
            $categoryID = Category::where('name', $category)->value('id');
            $products = Product::with('category')
                ->where(function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('long_description', 'like', '%' . $request->search . '%')
                        ->orWhere('short_description', 'like', '%' . $request->search . '%');
                })
                ->where('category_id', $categoryID)
                ->paginate(9);
        }
        $sizeCounts = [];
        foreach ($sizes as $size) {
            $decodedSize = json_decode($size);
            foreach ($decodedSize as $item) {
                if (!isset($sizeCounts[$item])) {
                    $sizeCounts[$item] = 0;
                }
                $sizeCounts[$item]++;
            }
        }

        return view('FrontEnd.shop', compact('categories', 'products', 'sizeCounts'));
    }

    public function filterSort(Request $request, $sort = null)
    {
        $categories = Category::withCount('products')->get();
        $sizes = Product::pluck('sizes')->flatten();
        $sizeCounts = [];

        foreach ($sizes as $size) {
            $decodedSize = json_decode($size);
            foreach ($decodedSize as $item) {
                if (!isset($sizeCounts[$item])) {
                    $sizeCounts[$item] = 0;
                }
                $sizeCounts[$item]++;
            }
        }

        $query = Product::with('category');

        if ($request->isMethod('post')) {
            $selectedSizes = $request->input('sizes');
            $sortBy = $request->route('sort');

            // Apply filters based on the selected sizes
            if ($selectedSizes) {
                $query->where(function (Builder $query) use ($selectedSizes) {
                    foreach ($selectedSizes as $size) {
                        $query->orWhereJsonContains('sizes', $size);
                    }
                });

                // Store the applied filters in session
                session()->put('selectedSizes', $selectedSizes);
            } else {
                // Clear the filters from session if no sizes selected
                session()->forget('selectedSizes');
            }
        } else {
            // Retrieve the applied filters from session
            $selectedSizes = session('selectedSizes');

            // Apply filters based on the retrieved sizes
            if ($selectedSizes) {
                $query->where(function (Builder $query) use ($selectedSizes) {
                    foreach ($selectedSizes as $size) {
                        $query->orWhereJsonContains('sizes', $size);
                    }
                });
            }
        }

        // Apply sorting based on the selected option
        if ($sort) {
            switch ($sort) {
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                    // Add additional cases for other sorting options if needed
                default:
                    // Default case: No sorting
                    break;
            }
        }

        // Retrieve the filtered and sorted products
        $products = $query->paginate(9);

        return view('FrontEnd.shop', compact('categories', 'products', 'sizeCounts'));
    }

    public function single($slug)
    {
        $product = Product::where('slug', $slug)->first();
        return view('FrontEnd.single', compact('product'));
    }
}
