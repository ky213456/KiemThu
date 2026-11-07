<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaiViet extends Model
{
    use HasFactory;

    protected $table = 'bai_viet';

    protected $fillable = [
        'tieu_de',
        'mo_ta_ngan',
        'noi_dung',
        'hinh_anh',
        'tag',
        'tinh_trang',
    ];
}
