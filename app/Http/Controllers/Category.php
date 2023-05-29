<?php

namespace App\Http\Controllers;

use App\Models\Category as CategoryModel;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class Category extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId     = auth()->user()->id;
        $categories = CategoryModel::where('user_id', $userId)->get();
        return view('AdminArea.Read.category', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('AdminArea.create.category');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        CategoryModel::create([
            'name'    => $request->name,
            'user_id' => auth()->user()->id
        ]);

        return redirect()->route('category.index')->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CategoryModel $category)
    {
        return view('category.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CategoryModel $category)
    {
        return view('AdminArea.Update.category', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CategoryModel $category)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $category->update([
            'name' => $request->name,
        ]);

        return redirect()->route('category.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(CategoryModel $category)
    {
        try {
            $category->delete();
            return redirect()->route('category.index')->with('success', 'Category deleted successfully.');
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return redirect()->route('category.index')->with('error', 'Cannot delete the category because it has associated products.');
            }
            return redirect()->route('category.index')->with('error', 'An error occurred while deleting the category.');
        }
    }
}
