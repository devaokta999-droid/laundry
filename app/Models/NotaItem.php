<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaItem extends Model
{
    use HasFactory;

    protected $fillable = ['nota_id', 'item_id', 'quantity', 'price', 'subtotal'];

    public function nota()
    {
        return $this->belongsTo(Nota::class);
    }

    public function item()
    {
        return $this->belongsTo(ItemLaundry::class, 'item_id');
    }
}
