<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Image extends Model
{
    use HasFactory;
    use HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['location', 'sort_order', 'is_deleted', 'product_id'];

    // Veza na proizvode koji koriste ovu sliku kao glavnu
    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

}
