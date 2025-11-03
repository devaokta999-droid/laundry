<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    /**
     * 🔒 Nama tabel (opsional, tapi lebih aman jika ada tabel custom)
     */
    protected $table = 'services';

    /**
     * 🧾 Kolom yang bisa diisi secara mass-assignment
     */
    protected $fillable = [
        'name',
        'description',
        'price',
    ];

    /**
     * 💰 Format harga otomatis ke "Rp"
     */
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * 🔗 Relasi ke model Order (jika nanti kamu mau buat transaksi)
     * Satu layanan bisa dipakai di banyak order item
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'service_id');
    }
}
