<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Product extends Model
{
    use HasFactory;
    use HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name', 'description', 'price', 'is_deleted', 'is_active', 'quantity', 'article_number', 'image_id'
    ];

    // Veza na glavnu sliku
    public function images()
    {
        return $this->hasMany(Image::class, 'product_id');
    }

    // Veza pivot tablice za sve kategorije
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories', 'product_id', 'category_id');
    }

    // Recenzije za proizvod
    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id');
    }

    // Logovi vezani za proizvod (po potrebi)
    public function logs()
    {
        return $this->hasMany(Log::class, 'product_id');
    }
}
