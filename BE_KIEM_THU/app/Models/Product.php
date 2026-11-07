<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $table = 'san_pham';

    protected $fillable = [
        'danh_muc_id',
        'ten_san_pham',
        'duong_dan',
        'ma_san_pham',
        'mo_ta_ngan',
        'noi_dung_chi_tiet',
        'gia_ban',
        'gia_goc',
        'so_luong_ton',
        'hinh_anh_chinh',
        'trang_thai',
        'noi_bat',
    ];

    protected function casts(): array
    {
        return [
            'gia_ban' => 'decimal:2',
            'gia_goc' => 'decimal:2',
            'so_luong_ton' => 'integer',
            'noi_bat' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'danh_muc_id');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class, 'san_pham_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'san_pham_id');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'san_pham_id');
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class, 'san_pham_id');
    }
}
