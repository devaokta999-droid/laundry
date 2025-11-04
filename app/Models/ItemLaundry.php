<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemLaundry extends Model
{
    use HasFactory;

    protected $table = 'items';
    protected $fillable = ['name', 'price'];

    public function notaItems()
    {
        return $this->hasMany(NotaItem::class, 'item_id');
    }
}
