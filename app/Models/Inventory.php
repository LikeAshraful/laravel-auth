<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    public function items () {
        return $this->hasMany(Item::class);
    }

    public function user () {
        return $this->belongsTo(User::class);
    }
}
