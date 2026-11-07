<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory;

    protected $table = 'gio_hang';

    protected $fillable = [
        'nguoi_dung_id',
        'san_pham_id',
        'bien_the_id',
        'so_luong',
    ];

    protected function casts(): array
    {
        return [
            'so_luong' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'nguoi_dung_id');
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
