<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'nota_id',
        'user_id',
        'amount',
        'type',
        'method',
        'discount_percent',
        'discount_amount',
    ];

    public function nota()
    {
        return $this->belongsTo(Nota::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
