<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Image; // Eloquent model za tablicu images
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function storeProduct(Request $request)
    {
        Log::info('storeProduct pozvana', $request->all());
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric|min:0.01',
            'quantity' => 'required|integer|min:0',
        ]);
        if ($request->hasFile('images')) {
            $validationRules['images.*'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096';
        }

        Log::info('Validacija prošla');

        $newProduct = new Product;
        $newProduct->name = $request->name;
        $newProduct->description = $request->description;
        $newProduct->price = $request->price;
        $newProduct->old_price = $request->price;
        $newProduct->quantity = $request->quantity;

        Log::info('Proizvod spremljen', ['id' => $newProduct->id]);

        do {
            $articleNumber = rand(10000, 99999);
        } while (Product::where('article_number', $articleNumber)->exists());
        $newProduct->article_number = $articleNumber;
        $newProduct->is_deleted = false;
        $newProduct->save();

        if (!file_exists(public_path('products'))) {
            mkdir(public_path('products'), 0755, true);
        }


        $manager = new ImageManager(new Driver());

        if ($request->hasFile('images')) {
            
            foreach ($request->file('images') as $i => $uploaded) {
                // Provjeri je li file valjan i je li slika
                if (!$uploaded || !$uploaded->isValid()) {
                    continue;
                }

                // Dodatna provjera MIME type-a
                $mimeType = $uploaded->getMimeType();
                if (!str_starts_with($mimeType, 'image/')) {
                    continue;
                }

                try {
                    $randomName = 'slika' . rand(100000, 999999);
                    $sortOrder = $request->input('sort_order.' . $i, $i + 1);
                    $webpName = $randomName . '.webp';
                    $fullPath = public_path('products/' . $webpName);

                    // Koristi getRealPath() za sigurnost
                    $img = $manager->read($uploaded->getRealPath());
                    $img->toWebp(80)->save($fullPath);

                    Image::create([
                        'location' => 'products/' . $webpName,
                        'sort_order' => $sortOrder,
                        'is_deleted' => false,
                        'product_id' => $newProduct->id
                    ]);
                    
                } catch (\Exception $e) {
                    Log::error("Greška pri obradi slike $i: " . $e->getMessage());
                    // Ne baca exception, samo logira i nastavlja s drugim slikama
                    continue;
                }
            }
        }

        return redirect()->back()->with('success', 'Proizvod uspješno dodan!');
    }


    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
