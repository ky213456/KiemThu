<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'chi_tiet_don_hang';

    protected $fillable = [
        'don_hang_id',
        'san_pham_id',
        'bien_the_id',
        'ten_san_pham',
        'thong_tin_bien_the',
        'don_gia',
        'so_luong',
        'thanh_tien',
    ];

    protected function casts(): array
    {
        return [
            'don_gia' => 'decimal:2',
            'so_luong' => 'integer',
            'thanh_tien' => 'decimal:2',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'don_hang_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'san_pham_id');
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'bien_the_id');
    }
}
