<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customer = User::where('email', 'customer@shop.com')->first();
        $product1 = Product::where('ma_san_pham', 'ATN-001')->with('variants')->first();
        $product2 = Product::where('ma_san_pham', 'POLO-002')->with('variants')->first();
        $product3 = Product::where('ma_san_pham', 'JEAN-005')->with('variants')->first();

        if (!$customer || !$product1) {
            return;
        }

        // Đơn 1: Chờ xác nhận - Tiền mặt (COD)
        $order1 = Order::updateOrCreate(
            ['ma_don_hang' => 'DH-20261101-001'],
            [
                'nguoi_dung_id' => $customer->id,
                'ho_ten_nguoi_nhan' => $customer->ho_ten,
                'email_nguoi_nhan' => $customer->email,
                'so_dien_thoai_nguoi_nhan' => $customer->so_dien_thoai ?? '0912345678',
                'dia_chi_giao_hang' => '789 Lê Lợi, Phường Bến Thành, Quận 1, TP. Hồ Chí Minh',
                'phuong_thuc_thanh_toan' => 'tien_mat',
                'trang_thai_thanh_toan' => 'chua_thanh_toan',
                'trang_thai_don_hang' => 'cho_xac_nhan',
                'tien_hang' => 398000,
                'tien_giam_gia' => 50000,
                'phi_van_chuyen' => 30000,
                'tong_tien' => 378000,
                'ma_giam_gia' => 'WELCOME50K',
                'ghi_chu' => 'Giao hàng giờ hành chính giúp mình nhé.',
            ]
        );

        $variant1 = $product1->variants->first();
        OrderItem::updateOrCreate(
            ['don_hang_id' => $order1->id, 'san_pham_id' => $product1->id],
            [
                'bien_the_id' => $variant1?->id,
                'ten_san_pham' => $product1->ten_san_pham,
                'thong_tin_bien_the' => $variant1 ? "Kích cỡ: {$variant1->kich_co} - Màu sắc: {$variant1->mau_sac}" : 'Tiêu chuẩn',
                'don_gia' => $product1->gia_ban,
                'so_luong' => 2,
                'thanh_tien' => $product1->gia_ban * 2,
            ]
        );

        // Đơn 2: Đang giao - Chuyển khoản (Đã thanh toán)
        $order2 = Order::updateOrCreate(
            ['ma_don_hang' => 'DH-20261103-002'],
            [
                'nguoi_dung_id' => $customer->id,
                'ho_ten_nguoi_nhan' => $customer->ho_ten,
                'email_nguoi_nhan' => $customer->email,
                'so_dien_thoai_nguoi_nhan' => $customer->so_dien_thoai ?? '0912345678',
                'dia_chi_giao_hang' => '789 Lê Lợi, Phường Bến Thành, Quận 1, TP. Hồ Chí Minh',
                'phuong_thuc_thanh_toan' => 'chuyen_khoan',
                'trang_thai_thanh_toan' => 'da_thanh_toan',
                'trang_thai_don_hang' => 'dang_giao',
                'tien_hang' => 669000,
                'tien_giam_gia' => 0,
                'phi_van_chuyen' => 30000,
                'tong_tien' => 699000,
                'ma_giam_gia' => null,
                'ghi_chu' => 'Gọi trước khi giao 15 phút.',
            ]
        );

        if ($product2) {
            $variant2 = $product2->variants->first();
            OrderItem::updateOrCreate(
                ['don_hang_id' => $order2->id, 'san_pham_id' => $product2->id],
                [
                    'bien_the_id' => $variant2?->id,
                    'ten_san_pham' => $product2->ten_san_pham,
                    'thong_tin_bien_the' => $variant2 ? "Kích cỡ: {$variant2->kich_co} - Màu sắc: {$variant2->mau_sac}" : 'Tiêu chuẩn',
                    'don_gia' => $product2->gia_ban,
                    'so_luong' => 1,
                    'thanh_tien' => $product2->gia_ban,
                ]
            );
        }

        if ($product3) {
            $variant3 = $product3->variants->first();
            OrderItem::updateOrCreate(
                ['don_hang_id' => $order2->id, 'san_pham_id' => $product3->id],
                [
                    'bien_the_id' => $variant3?->id,
                    'ten_san_pham' => $product3->ten_san_pham,
                    'thong_tin_bien_the' => $variant3 ? "Kích cỡ: {$variant3->kich_co} - Màu sắc: {$variant3->mau_sac}" : 'Tiêu chuẩn',
                    'don_gia' => $product3->gia_ban,
                    'so_luong' => 1,
                    'thanh_tien' => $product3->gia_ban,
                ]
            );
        }

        // Đơn 3: Đã giao thành công
        $order3 = Order::updateOrCreate(
            ['ma_don_hang' => 'DH-20261025-003'],
            [
                'nguoi_dung_id' => $customer->id,
                'ho_ten_nguoi_nhan' => $customer->ho_ten,
                'email_nguoi_nhan' => $customer->email,
                'so_dien_thoai_nguoi_nhan' => $customer->so_dien_thoai ?? '0912345678',
                'dia_chi_giao_hang' => '789 Lê Lợi, Phường Bến Thành, Quận 1, TP. Hồ Chí Minh',
                'phuong_thuc_thanh_toan' => 'tien_mat',
                'trang_thai_thanh_toan' => 'da_thanh_toan',
                'trang_thai_don_hang' => 'da_giao',
                'tien_hang' => 289000,
                'tien_giam_gia' => 0,
                'phi_van_chuyen' => 30000,
                'tong_tien' => 319000,
                'ma_giam_gia' => null,
                'ghi_chu' => null,
            ]
        );

        if ($product2) {
            $variant2 = $product2->variants->first();
            OrderItem::updateOrCreate(
                ['don_hang_id' => $order3->id, 'san_pham_id' => $product2->id],
                [
                    'bien_the_id' => $variant2?->id,
                    'ten_san_pham' => $product2->ten_san_pham,
                    'thong_tin_bien_the' => $variant2 ? "Kích cỡ: {$variant2->kich_co} - Màu sắc: {$variant2->mau_sac}" : 'Tiêu chuẩn',
                    'don_gia' => $product2->gia_ban,
                    'so_luong' => 1,
                    'thanh_tien' => $product2->gia_ban,
                ]
            );
        }

        // Đơn 4: Đã hủy (Cancelled)
        Order::updateOrCreate(
            ['ma_don_hang' => 'DH-20261020-004'],
            [
                'nguoi_dung_id' => $customer->id,
                'ho_ten_nguoi_nhan' => $customer->ho_ten,
                'email_nguoi_nhan' => $customer->email,
                'so_dien_thoai_nguoi_nhan' => $customer->so_dien_thoai ?? '0912345678',
                'dia_chi_giao_hang' => '789 Lê Lợi, Phường Bến Thành, Quận 1, TP. Hồ Chí Minh',
                'phuong_thuc_thanh_toan' => 'tien_mat',
                'trang_thai_thanh_toan' => 'chua_thanh_toan',
                'trang_thai_don_hang' => 'da_huy',
                'tien_hang' => 199000,
                'tien_giam_gia' => 0,
                'phi_van_chuyen' => 30000,
                'tong_tien' => 229000,
                'ma_giam_gia' => null,
                'ghi_chu' => 'Khách đổi ý muốn đặt màu khác nên xin hủy đơn.',
            ]
        );
    }
}
