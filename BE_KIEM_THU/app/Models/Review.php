<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $table = 'danh_gia';

    protected $fillable = [
        'nguoi_dung_id',
        'san_pham_id',
        'so_sao',
        'noi_dung',
        'trang_thai',
    ];

    protected function casts(): array
    {
        return [
            'so_sao' => 'integer',
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
}
