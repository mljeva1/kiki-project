<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Delivery extends Model
{
    use HasFactory;
    use HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['street_name', 'street_number', 'city', 'zip_code', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}