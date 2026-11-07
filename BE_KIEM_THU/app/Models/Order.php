<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $table = 'don_hang';

    protected $fillable = [
        'ma_don_hang',
        'nguoi_dung_id',
        'ho_ten_nguoi_nhan',
        'email_nguoi_nhan',
        'so_dien_thoai_nguoi_nhan',
        'dia_chi_giao_hang',
        'phuong_thuc_thanh_toan',
        'trang_thai_thanh_toan',
        'trang_thai_don_hang',
        'tien_hang',
        'tien_giam_gia',
        'phi_van_chuyen',
        'tong_tien',
        'ma_giam_gia',
        'ghi_chu',
    ];

    protected function casts(): array
    {
        return [
            'tien_hang' => 'decimal:2',
            'tien_giam_gia' => 'decimal:2',
            'phi_van_chuyen' => 'decimal:2',
            'tong_tien' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'nguoi_dung_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'don_hang_id');
    }
}
