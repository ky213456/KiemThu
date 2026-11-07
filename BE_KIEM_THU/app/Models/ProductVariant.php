<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    use HasFactory;

    protected $table = 'bien_the_san_pham';

    protected $fillable = [
        'san_pham_id',
        'kich_co',
        'mau_sac',
        'so_luong_ton',
        'ma_bien_the',
        'gia_tang_them',
    ];

    protected function casts(): array
    {
        return [
            'so_luong_ton' => 'integer',
            'gia_tang_them' => 'decimal:2',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'san_pham_id');
    }
}
