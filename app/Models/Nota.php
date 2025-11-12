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

    /**
     * Relasi ke kasir (alias dari user)
     * 👉 Untuk dipakai pada laporan/export Excel
     */
    public function kasir()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke admin (alias dari user)
     * 👉 Bisa dipakai jika kamu ingin membedakan user dengan role admin
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke deva (alias tambahan sesuai permintaan)
     * 👉 Sama-sama menunjuk ke user_id agar tidak error saat dipanggil
     */
    public function deva()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
