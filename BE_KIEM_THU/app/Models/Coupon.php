<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $table = 'ma_giam_gia';

    protected $fillable = [
        'ma_giam_gia',
        'ten_ma',
        'loai_giam_gia',
        'gia_tri_giam',
        'don_hang_toi_thieu',
        'giam_toi_da',
        'so_luong_ma',
        'so_luong_da_dung',
        'ngay_bat_dau',
        'ngay_ket_thuc',
        'trang_thai',
    ];

    protected function casts(): array
    {
        return [
            'gia_tri_giam' => 'decimal:2',
            'don_hang_toi_thieu' => 'decimal:2',
            'giam_toi_da' => 'decimal:2',
            'so_luong_ma' => 'integer',
            'so_luong_da_dung' => 'integer',
            'ngay_bat_dau' => 'datetime',
            'ngay_ket_thuc' => 'datetime',
            'trang_thai' => 'boolean',
        ];
    }
}
