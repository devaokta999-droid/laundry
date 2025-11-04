<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_name',
        'customer_address',
        'tgl_masuk',
        'tgl_keluar',
        'total',
        'uang_muka',
        'sisa'
    ];

    /**
     * Casting kolom tanggal ke instance Carbon (agar bisa pakai ->format())
     */
    protected $casts = [
        'tgl_masuk' => 'datetime',
        'tgl_keluar' => 'datetime',
    ];

    /**
     * Relasi ke User (pembuat nota)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke NotaItem (daftar item laundry)
     */
    public function items()
    {
        return $this->hasMany(NotaItem::class);
    }
}
