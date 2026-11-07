<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coupons = [
            [
                'ma_giam_gia' => 'WELCOME50K',
                'ten_ma' => 'Giảm 50.000đ cho khách hàng mới',
                'loai_giam_gia' => 'tien_mat',
                'gia_tri_giam' => 50000,
                'don_hang_toi_thieu' => 200000,
                'giam_toi_da' => null,
                'so_luong_ma' => 100,
                'so_luong_da_dung' => 5,
                'ngay_bat_dau' => Carbon::now()->subDays(10),
                'ngay_ket_thuc' => Carbon::now()->addDays(60),
                'trang_thai' => true,
            ],
            [
                'ma_giam_gia' => 'SALE10',
                'ten_ma' => 'Giảm 10% tối đa 40.000đ',
                'loai_giam_gia' => 'phan_tram',
                'gia_tri_giam' => 10,
                'don_hang_toi_thieu' => 150000,
                'giam_toi_da' => 40000,
                'so_luong_ma' => 200,
                'so_luong_da_dung' => 12,
                'ngay_bat_dau' => Carbon::now()->subDays(5),
                'ngay_ket_thuc' => Carbon::now()->addDays(30),
                'trang_thai' => true,
            ],
            [
                'ma_giam_gia' => 'VIP500K',
                'ten_ma' => 'Giảm 100.000đ cho đơn hàng giá trị cao từ 1.000.000đ',
                'loai_giam_gia' => 'tien_mat',
                'gia_tri_giam' => 100000,
                'don_hang_toi_thieu' => 1000000,
                'giam_toi_da' => null,
                'so_luong_ma' => 50,
                'so_luong_da_dung' => 0,
                'ngay_bat_dau' => Carbon::now()->subDays(2),
                'ngay_ket_thuc' => Carbon::now()->addDays(90),
                'trang_thai' => true,
            ],
            // Test Case 1: Mã đã hết hạn
            [
                'ma_giam_gia' => 'EXPIREDVOUCHER',
                'ten_ma' => 'Mã hết hạn (Dành cho kiểm thử Test Case hết hạn)',
                'loai_giam_gia' => 'tien_mat',
                'gia_tri_giam' => 20000,
                'don_hang_toi_thieu' => 100000,
                'giam_toi_da' => null,
                'so_luong_ma' => 50,
                'so_luong_da_dung' => 10,
                'ngay_bat_dau' => Carbon::now()->subDays(60),
                'ngay_ket_thuc' => Carbon::now()->subDays(5),
                'trang_thai' => true,
            ],
            // Test Case 2: Mã đã hết lượt sử dụng
            [
                'ma_giam_gia' => 'OUTOFUSAGE',
                'ten_ma' => 'Mã hết lượt dùng (Dành cho kiểm thử Test Case hết lượt)',
                'loai_giam_gia' => 'tien_mat',
                'gia_tri_giam' => 30000,
                'don_hang_toi_thieu' => 100000,
                'giam_toi_da' => null,
                'so_luong_ma' => 10,
                'so_luong_da_dung' => 10,
                'ngay_bat_dau' => Carbon::now()->subDays(5),
                'ngay_ket_thuc' => Carbon::now()->addDays(30),
                'trang_thai' => true,
            ],
            // Test Case 3: Mã bị vô hiệu hóa bởi Admin
            [
                'ma_giam_gia' => 'DISABLEDVOUCHER',
                'ten_ma' => 'Mã tạm ngưng (Dành cho kiểm thử Test Case trang_thai = 0)',
                'loai_giam_gia' => 'tien_mat',
                'gia_tri_giam' => 50000,
                'don_hang_toi_thieu' => 100000,
                'giam_toi_da' => null,
                'so_luong_ma' => 100,
                'so_luong_da_dung' => 0,
                'ngay_bat_dau' => Carbon::now()->subDays(5),
                'ngay_ket_thuc' => Carbon::now()->addDays(30),
                'trang_thai' => false,
            ],
        ];

        foreach ($coupons as $coupon) {
            Coupon::updateOrCreate(['ma_giam_gia' => $coupon['ma_giam_gia']], $coupon);
        }
    }
}
