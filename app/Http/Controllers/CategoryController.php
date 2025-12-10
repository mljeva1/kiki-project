<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('category.index');
    }

    public function catEditForm()
    {
        $category = Category::where('is_deleted', false)
            ->get();
        return view('category.edit', ['category' => $category]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        Log::info('createCategory pozvana');
        
        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        Log::info('Validacija prošla');

        $category = new Category;
        $category->name = $request->name;
        $category->description = $request->description;
        $category->is_deleted = false;

        $category->save();

        return redirect('/dashboard')->with('success', "Kategorija uspješno dodana!");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        $category = Category::findOrFail($id);
        $category->name = $request->name;
        $category->description = $request->description;
        $category->save();

        
        return back()->with('success', 'Kategorija uspješno ažurirana!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Log::info('CategoryController: softDelete pozvana', ['id' => $id]);

        $product = Category::findOrFail($id);
        $product->is_deleted = true;
        $product->save();

        Log::info('ProductController: Kategorija soft-obrisan', ['id' => $product->id]);

        return back()->with('success', 'Kategorija uspješno obrisana!');
    }
}
