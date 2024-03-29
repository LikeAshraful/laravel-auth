<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model {
    use HasFactory;

    protected $fillable = ['name', 'description', 'image', 'quantity'];

    protected $appends = [
        'image_url',
    ];

    public function inventory() {
        return $this->belongsTo(Inventory::class);
    }

    public function getImageUrlAttribute() {
        return url('/storage/images/' . $this->image);
    }

}
