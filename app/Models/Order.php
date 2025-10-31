<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    /**
     * 🔹 Kolom yang bisa diisi (mass assignable)
     */
    protected $fillable = [
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'notes',
        'items',
        'total_price',
        'pickup_date',
        'pickup_time',
        'status',
    ];

    /**
     * 🔹 Tipe data otomatis dikonversi
     */
    protected $casts = [
        'items' => 'array',
        'pickup_date' => 'datetime',
        'pickup_time' => 'datetime',
    ];

    /**
     * 🔹 Default value untuk kolom tertentu
     */
    protected $attributes = [
        'status' => 'pending',
    ];

    /**
     * 🔹 Relasi ke User (Pelanggan)
     * Satu order dimiliki oleh satu user (jika login)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 🔹 Relasi ke Service (Layanan)
     * Satu order bisa memiliki banyak layanan
     */
    public function services()
    {
        return $this->belongsToMany(Service::class, 'order_service')
                    ->withPivot('quantity', 'subtotal') // simpan info tambahan di pivot
                    ->withTimestamps();
    }

    /**
     * 🔹 Helper: format total harga otomatis ke format rupiah
     */
    public function getFormattedTotalAttribute()
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }

    /**
     * 🔹 Helper: untuk menampilkan status dengan label warna
     */
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'pending' => 'bg-warning text-dark',
            'proses' => 'bg-info text-dark',
            'selesai' => 'bg-success',
            default => 'bg-secondary text-light',
        };
    }
}
