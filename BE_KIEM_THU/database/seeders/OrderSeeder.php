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

        $userId = $customer ? $customer->id : null;

        // DANH SÁCH ĐƠN HÀNG CHUẨN ĐỒNG BỘ 100% VỚI FILE WORD BÁO CÁO CỦA THÀNH VIÊN 4
        $ordersData = [
            // TC_ORD_02: Xem đơn hàng #ORD-5821 (Chị Thảo My, 2.280.000đ, VNPAY)
            [
                'ma_don_hang'              => '#ORD-5821',
                'nguoi_dung_id'            => $userId,
                'ho_ten_nguoi_nhan'        => 'Chị Thảo My',
                'email_nguoi_nhan'         => 'thaomy.vip@gmail.com',
                'so_dien_thoai_nguoi_nhan' => '0912 345 678',
                'dia_chi_giao_hang'        => 'Tại quầy Flagship Quận 1',
                'phuong_thuc_thanh_toan'   => 'vnpay',
                'trang_thai_thanh_toan'    => 'da_thanh_toan',
                'trang_thai_don_hang'      => 'cho_xac_nhan',
                'tien_hang'                => 2280000,
                'tien_giam_gia'            => 0,
                'phi_van_chuyen'           => 0,
                'tong_tien'                => 2280000,
                'ma_giam_gia'              => null,
                'ghi_chu'                  => '2 món • Blazer Linen (M), Khăn lụa Atelier',
            ],
            // TC_ORD_03: Xem đơn hàng #ORD-5820 tại quầy (Khách vãng lai, 1.490.000đ, Tiền mặt)
            [
                'ma_don_hang'              => '#ORD-5820',
                'nguoi_dung_id'            => null,
                'ho_ten_nguoi_nhan'        => 'Khách vãng lai - Quầy 2',
                'email_nguoi_nhan'         => 'pos-guest@atelier.vn',
                'so_dien_thoai_nguoi_nhan' => '0905 123 456',
                'dia_chi_giao_hang'        => 'Tại quầy Flagship Quận 1',
                'phuong_thuc_thanh_toan'   => 'tien_mat',
                'trang_thai_thanh_toan'    => 'da_thanh_toan',
                'trang_thai_don_hang'      => 'dang_xu_ly',
                'tien_hang'                => 1490000,
                'tien_giam_gia'            => 0,
                'phi_van_chuyen'           => 0,
                'tong_tien'                => 1490000,
                'ma_giam_gia'              => null,
                'ghi_chu'                  => '1 món • Đầm Maxi Lụa Satin (S)',
            ],
            // TC_ORD_14: Tìm theo tài khoản Instagram (helen.tran, 890.000đ, Chuyển khoản, Đang giao)
            [
                'ma_don_hang'              => '#ORD-5819',
                'nguoi_dung_id'            => $userId,
                'ho_ten_nguoi_nhan'        => 'Instagram @helen.tran',
                'email_nguoi_nhan'         => 'helen.tran@instagram.com',
                'so_dien_thoai_nguoi_nhan' => '0988 776 655',
                'dia_chi_giao_hang'        => 'Online Instagram Direct',
                'phuong_thuc_thanh_toan'   => 'chuyen_khoan',
                'trang_thai_thanh_toan'    => 'chua_thanh_toan',
                'trang_thai_don_hang'      => 'dang_giao',
                'tien_hang'                => 890000,
                'tien_giam_gia'            => 0,
                'phi_van_chuyen'           => 0,
                'tong_tien'                => 890000,
                'ma_giam_gia'              => null,
                'ghi_chu'                  => '1 món • Sơ mi Poplin White (M)',
            ],
            // #ORD-5818: Chị Lan Hương (3.200.000đ, Tiền mặt, Đã giao)
            [
                'ma_don_hang'              => '#ORD-5818',
                'nguoi_dung_id'            => $userId,
                'ho_ten_nguoi_nhan'        => 'Chị Lan Hương',
                'email_nguoi_nhan'         => 'lanhuong@gmail.com',
                'so_dien_thoai_nguoi_nhan' => '0933 221 100',
                'dia_chi_giao_hang'        => 'Tại quầy Flagship Quận 1',
                'phuong_thuc_thanh_toan'   => 'tien_mat',
                'trang_thai_thanh_toan'    => 'da_thanh_toan',
                'trang_thai_don_hang'      => 'da_giao',
                'tien_hang'                => 3200000,
                'tien_giam_gia'            => 0,
                'phi_van_chuyen'           => 0,
                'tong_tien'                => 3200000,
                'ma_giam_gia'              => null,
                'ghi_chu'                  => '3 món • Blazer & Quần Tây Premium',
            ],
            // TC_ORD_39: Tạo đơn hàng giá trị lớn 50 triệu (Đơn hàng VIP Doanh Nhân)
            [
                'ma_don_hang'              => '#ORD-5817',
                'nguoi_dung_id'            => $userId,
                'ho_ten_nguoi_nhan'        => 'Khách VIP Doanh Nhân',
                'email_nguoi_nhan'         => 'ceo.vip@luxurygroup.vn',
                'so_dien_thoai_nguoi_nhan' => '0918 889 999',
                'dia_chi_giao_hang'        => 'Penthouse Landmark 81, Bình Thạnh, TP.HCM',
                'phuong_thuc_thanh_toan'   => 'chuyen_khoan',
                'trang_thai_thanh_toan'    => 'da_thanh_toan',
                'trang_thai_don_hang'      => 'da_giao',
                'tien_hang'                => 50000000,
                'tien_giam_gia'            => 0,
                'phi_van_chuyen'           => 0,
                'tong_tien'                => 50000000,
                'ma_giam_gia'              => null,
                'ghi_chu'                  => 'Bộ sưu tập dạ hội phiên bản giới hạn Limited Edition',
            ],
            // Đơn mẫu thêm để phục vụ kiểm thử phân trang (Pagination: Trang 1 và Trang 2 - TC_ORD_34, TC_ORD_35)
            [
                'ma_don_hang'              => '#ORD-5816',
                'nguoi_dung_id'            => $userId,
                'ho_ten_nguoi_nhan'        => 'Nguyễn Hoàng Yến',
                'email_nguoi_nhan'         => 'hoangyen@gmail.com',
                'so_dien_thoai_nguoi_nhan' => '0944 556 677',
                'dia_chi_giao_hang'        => '12 Pasteur, Bến Nghé, Quận 1',
                'phuong_thuc_thanh_toan'   => 'vnpay',
                'trang_thai_thanh_toan'    => 'da_thanh_toan',
                'trang_thai_don_hang'      => 'dang_giao',
                'tien_hang'                => 1850000,
                'tien_giam_gia'            => 0,
                'phi_van_chuyen'           => 0,
                'tong_tien'                => 1850000,
                'ma_giam_gia'              => null,
                'ghi_chu'                  => 'Áo khoác Tweed thu đông (Size S)',
            ],
            [
                'ma_don_hang'              => '#ORD-5815',
                'nguoi_dung_id'            => null,
                'ho_ten_nguoi_nhan'        => 'Khách vãng lai - Quầy 1',
                'email_nguoi_nhan'         => 'guest1@atelier.vn',
                'so_dien_thoai_nguoi_nhan' => '0903 445 566',
                'dia_chi_giao_hang'        => 'Tại quầy Flagship Quận 1',
                'phuong_thuc_thanh_toan'   => 'tien_mat',
                'trang_thai_thanh_toan'    => 'da_thanh_toan',
                'trang_thai_don_hang'      => 'da_giao',
                'tien_hang'                => 750000,
                'tien_giam_gia'            => 0,
                'phi_van_chuyen'           => 0,
                'tong_tien'                => 750000,
                'ma_giam_gia'              => null,
                'ghi_chu'                  => 'Chân váy xếp ly A-line',
            ],
            [
                'ma_don_hang'              => '#ORD-5814',
                'nguoi_dung_id'            => $userId,
                'ho_ten_nguoi_nhan'        => 'Trần Đình Trọng',
                'email_nguoi_nhan'         => 'trong.td@outlook.com',
                'so_dien_thoai_nguoi_nhan' => '0981 223 344',
                'dia_chi_giao_hang'        => 'Hà Nội Express',
                'phuong_thuc_thanh_toan'   => 'chuyen_khoan',
                'trang_thai_thanh_toan'    => 'hoan_tien',
                'trang_thai_don_hang'      => 'da_huy',
                'tien_hang'                => 1200000,
                'tien_giam_gia'            => 0,
                'phi_van_chuyen'           => 0,
                'tong_tien'                => 1200000,
                'ma_giam_gia'              => null,
                'ghi_chu'                  => 'Khách hủy đơn do trùng size - Đã hoàn tiền',
            ],
            [
                'ma_don_hang'              => '#ORD-5813',
                'nguoi_dung_id'            => $userId,
                'ho_ten_nguoi_nhan'        => 'Vũ Thị Minh Hằng',
                'email_nguoi_nhan'         => 'minhhang.vu@gmail.com',
                'so_dien_thoai_nguoi_nhan' => '0977 112 233',
                'dia_chi_giao_hang'        => 'Thảo Điền, TP. Thủ Đức',
                'phuong_thuc_thanh_toan'   => 'vnpay',
                'trang_thai_thanh_toan'    => 'da_thanh_toan',
                'trang_thai_don_hang'      => 'cho_xac_nhan',
                'tien_hang'                => 2450000,
                'tien_giam_gia'            => 0,
                'phi_van_chuyen'           => 0,
                'tong_tien'                => 2450000,
                'ma_giam_gia'              => null,
                'ghi_chu'                  => 'Set Suit công sở sang trọng',
            ],
            [
                'ma_don_hang'              => '#ORD-5812',
                'nguoi_dung_id'            => $userId,
                'ho_ten_nguoi_nhan'        => 'Đặng Phương Thảo',
                'email_nguoi_nhan'         => 'phuongthao@gmail.com',
                'so_dien_thoai_nguoi_nhan' => '0966 889 900',
                'dia_chi_giao_hang'        => 'Phú Mỹ Hưng, Quận 7',
                'phuong_thuc_thanh_toan'   => 'tien_mat',
                'trang_thai_thanh_toan'    => 'chua_thanh_toan',
                'trang_thai_don_hang'      => 'dang_xu_ly',
                'tien_hang'                => 1680000,
                'tien_giam_gia'            => 0,
                'phi_van_chuyen'           => 0,
                'tong_tien'                => 1680000,
                'ma_giam_gia'              => null,
                'ghi_chu'                  => 'Đầm lụa dạ tiệc đỏ ruby',
            ],
            [
                'ma_don_hang'              => '#ORD-5811',
                'nguoi_dung_id'            => $userId,
                'ho_ten_nguoi_nhan'        => 'Bùi Tiến Dũng',
                'email_nguoi_nhan'         => 'tiendung@gmail.com',
                'so_dien_thoai_nguoi_nhan' => '0938 778 899',
                'dia_chi_giao_hang'        => 'Quận 3, TP. Hồ Chí Minh',
                'phuong_thuc_thanh_toan'   => 'vnpay',
                'trang_thai_thanh_toan'    => 'da_thanh_toan',
                'trang_thai_don_hang'      => 'da_giao',
                'tien_hang'                => 980000,
                'tien_giam_gia'            => 0,
                'phi_van_chuyen'           => 0,
                'tong_tien'                => 980000,
                'ma_giam_gia'              => null,
                'ghi_chu'                  => 'Áo Sơ mi lụa tơ tằm',
            ],
            [
                'ma_don_hang'              => '#ORD-5810',
                'nguoi_dung_id'            => $userId,
                'ho_ten_nguoi_nhan'        => 'Lê Mỹ Duyên',
                'email_nguoi_nhan'         => 'myduyen.le@gmail.com',
                'so_dien_thoai_nguoi_nhan' => '0919 667 788',
                'dia_chi_giao_hang'        => 'Quận Bình Thạnh, TP.HCM',
                'phuong_thuc_thanh_toan'   => 'chuyen_khoan',
                'trang_thai_thanh_toan'    => 'da_thanh_toan',
                'trang_thai_don_hang'      => 'dang_giao',
                'tien_hang'                => 2100000,
                'tien_giam_gia'            => 0,
                'phi_van_chuyen'           => 0,
                'tong_tien'                => 2100000,
                'ma_giam_gia'              => null,
                'ghi_chu'                  => 'Áo Trench Coat dáng dài Thu Đông',
            ],
            // Giữ lại mã đơn DH-20261101-001 để đảm bảo tương thích các test case cũ của bạn
            [
                'ma_don_hang'              => 'DH-20261101-001',
                'nguoi_dung_id'            => $userId,
                'ho_ten_nguoi_nhan'        => $customer ? $customer->ho_ten : 'Nguyễn Văn An',
                'email_nguoi_nhan'         => $customer ? $customer->email : 'customer@shop.com',
                'so_dien_thoai_nguoi_nhan' => '0912345678',
                'dia_chi_giao_hang'        => '789 Lê Lợi, Phường Bến Thành, Quận 1, TP. Hồ Chí Minh',
                'phuong_thuc_thanh_toan'   => 'tien_mat',
                'trang_thai_thanh_toan'    => 'chua_thanh_toan',
                'trang_thai_don_hang'      => 'cho_xac_nhan',
                'tien_hang'                => 398000,
                'tien_giam_gia'            => 50000,
                'phi_van_chuyen'           => 30000,
                'tong_tien'                => 378000,
                'ma_giam_gia'              => 'WELCOME50K',
                'ghi_chu'                  => 'Giao hàng giờ hành chính giúp mình nhé.',
            ],
            [
                'ma_don_hang'              => 'DH-20261025-003',
                'nguoi_dung_id'            => $userId,
                'ho_ten_nguoi_nhan'        => $customer ? $customer->ho_ten : 'Nguyễn Văn An',
                'email_nguoi_nhan'         => $customer ? $customer->email : 'customer@shop.com',
                'so_dien_thoai_nguoi_nhan' => '0912345678',
                'dia_chi_giao_hang'        => '789 Lê Lợi, Phường Bến Thành, Quận 1, TP. Hồ Chí Minh',
                'phuong_thuc_thanh_toan'   => 'tien_mat',
                'trang_thai_thanh_toan'    => 'da_thanh_toan',
                'trang_thai_don_hang'      => 'da_giao',
                'tien_hang'                => 289000,
                'tien_giam_gia'            => 0,
                'phi_van_chuyen'           => 30000,
                'tong_tien'                => 319000,
                'ma_giam_gia'              => null,
                'ghi_chu'                  => 'Đơn hoàn tất tại quầy',
            ]
        ];

        foreach ($ordersData as $oData) {
            $createdOrder = Order::updateOrCreate(
                ['ma_don_hang' => $oData['ma_don_hang']],
                $oData
            );

            // Gán sản phẩm kèm theo
            if ($product1 && $createdOrder->items()->count() == 0) {
                $variant1 = $product1->variants->first();
                OrderItem::create([
                    'don_hang_id'        => $createdOrder->id,
                    'san_pham_id'        => $product1->id,
                    'bien_the_id'        => $variant1?->id,
                    'ten_san_pham'       => $product1->ten_san_pham,
                    'thong_tin_bien_the' => $variant1 ? "Kích cỡ: {$variant1->kich_co} - Màu sắc: {$variant1->mau_sac}" : 'Tiêu chuẩn',
                    'don_gia'            => $product1->gia_ban,
                    'so_luong'           => 1,
                    'thanh_tien'         => $product1->gia_ban,
                ]);
            }
        }
    }
}
