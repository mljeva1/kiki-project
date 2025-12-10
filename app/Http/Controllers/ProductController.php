<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Image;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index()
    {
        return view('product.create');
    }
    public function editForm()
    {
        $products = Product::with('images')->orderBy('article_number','asc')->get();
        return view('product.edit', ['products' => $products]);
    }
    public function create(Request $request)
    {
        Log::info('storeProduct pozvana');
        
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric|min:0.01',
            'quantity' => 'required|integer|min:0',
        ]);

        Log::info('Validacija prošla');

        $newProduct = new Product;
        $newProduct->name = $request->name;
        $newProduct->description = $request->description;
        $newProduct->price = $request->price;
        $newProduct->old_price = $request->price;
        $newProduct->quantity = $request->quantity;

        do {
            $articleNumber = rand(10000, 99999);
        } while (Product::where('article_number', $articleNumber)->exists());
        
        $newProduct->article_number = $articleNumber;
        $newProduct->is_deleted = false;
        $newProduct->save();

        Log::info('Proizvod spremljen', ['id' => $newProduct->id]);

        // Kreiraj direktorij
        $productDir = public_path('products');
        if (!file_exists($productDir)) {
            mkdir($productDir, 0755, true);
        }

        $manager = new ImageManager(new Driver());
        $sortOrder = 1;

        // CROP-OVANE slike (priority!)
        if ($request->has('cropped_images') && !empty($request->input('cropped_images'))) {
            Log::info('Procesiranje crop-ovanih slika');
            
            foreach ($request->input('cropped_images') as $base64Image) {
                if (empty($base64Image)) {
                    continue;
                }

                try {
                    // Dekodira base64
                    $imageData = base64_decode(
                        preg_replace('#^data:image/\w+;base64,#i', '', $base64Image)
                    );
                    
                    if (!$imageData) {
                        Log::warning("Nije moguće dekodirati base64 sliku");
                        continue;
                    }

                    // Spremi u temp datoteku
                    $tempPath = sys_get_temp_dir() . '/' . uniqid() . '.png';
                    file_put_contents($tempPath, $imageData);

                    $webpName = 'prod_' . $newProduct->id . '_' . time() . '_' . rand(1000, 9999) . '.webp';;
                    $fullPath = $productDir . '/' . $webpName;

                    // Pretvori u WebP
                    $img = $manager->read($tempPath);
                    $img->toWebp(80)->save($fullPath);

                    // Spremi u bazu
                    Image::create([
                        'location' => 'products/' . $webpName,
                        'sort_order' => $sortOrder,
                        'is_deleted' => false,
                        'product_id' => $newProduct->id
                    ]);

                    Log::info("Crop-ovana slika $webpName uspješno spraljena");
                    $sortOrder++;

                    // Obriši temp datoteku
                    if (file_exists($tempPath)) {
                        @unlink($tempPath);
                    }

                } catch (\Exception $e) {
                    Log::error("Greška pri obradi crop-ovane slike: " . $e->getMessage());
                    continue;
                }
            }
        }

        // Ako nema crop-ovanih, procesiraj originalne slike
        if ($request->hasFile('images') && $sortOrder === 1) {
            Log::info('Procesiranje originalnih slika');
            
            foreach ($request->file('images') as $i => $uploaded) {
                if (!$uploaded || !$uploaded->isValid()) {
                    continue;
                }

                $mimeType = $uploaded->getMimeType();
                if (!str_starts_with($mimeType, 'image/')) {
                    continue;
                }

                try {
                    $randomName = 'slika' . rand(100000, 999999);
                    $sortOrderValue = $request->input('sort_order.' . $i, $i + 1);
                    $webpName = $randomName . '.webp';
                    $fullPath = $productDir . '/' . $webpName;

                    $img = $manager->read($uploaded->getRealPath());
                    $img->toWebp(80)->save($fullPath);

                    Image::create([
                        'location' => 'products/' . $webpName,
                        'sort_order' => $sortOrderValue,
                        'is_deleted' => false,
                        'product_id' => $newProduct->id
                    ]);

                    Log::info("Originalna slika $webpName uspješno spraljena");
                    $sortOrder++;

                } catch (\Exception $e) {
                    Log::error("Greška pri obradi slike $i: " . $e->getMessage());
                    continue;
                }
            }
        }

        $imageCount = $sortOrder - 1;
        Log::info("Proizvod dodan s $imageCount slika");

        return redirect('/dashboard')->with('success', "Proizvod uspješno dodan s $imageCount slika!");
    }

    public function store(Request $request)
    {
        
    }

    public function show(Product $product)
    {
        //
    }

    public function edit(Product $product)
    {
        //
    }

    public function update(Request $request, $id)
    {
        Log::info('updateProduct pozvana', ['id' => $id]);
        
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric|min:0.01',
            'quantity' => 'required|integer|min:0',
        ]);

        $product = Product::findOrFail($id);
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->save();

        Log::info('Proizvod ažuriran', ['id' => $id]);
        return back()->with('success', 'Proizvod uspješno ažuriran!');
    }
    
    public function updateImages(Request $request, $id)
    {
        Log::info('updateImages pozvana', ['id' => $id]);
        
        $product = Product::findOrFail($id);
        $images = $request->input('images', []);

        foreach ($images as $imageId => $data) {
            // Ako je slika označena za brisanje
            if (isset($data['deleted']) && $data['deleted'] == '1') {
                $image = Image::find($imageId);
                if ($image && $image->product_id == $product->id) {
                    // Obriši datoteku
                    $filePath = public_path($image->location);
                    if (file_exists($filePath)) {
                        @unlink($filePath);
                    }
                    // Obriši iz baze
                    $image->delete();
                    Log::info("Slika obrisana: $imageId");
                }
            } else {
                // Ažurira sort_order
                $image = Image::find($imageId);
                if ($image && $image->product_id == $product->id) {
                    $image->sort_order = $data['sort_order'] ?? 1;
                    $image->save();
                    Log::info("Slika ažurirana: $imageId -> sort_order: {$data['sort_order']}");
                }
            }
        }

        return back()->with('success', 'Slike uspješno ažurirane!');
    
    }

    public function destroy(Product $product)
    {
        //
    }
}
