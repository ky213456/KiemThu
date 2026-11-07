<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $catAoNam = Category::where('duong_dan', 'ao-nam')->first();
        $catAoNu = Category::where('duong_dan', 'ao-nu')->first();
        $catQuanNam = Category::where('duong_dan', 'quan-nam')->first();
        $catVayNu = Category::where('duong_dan', 'vay-dam-nu')->first();
        $catAoKhoac = Category::where('duong_dan', 'ao-khoac-hoodie')->first();

        $productsData = [
            // 1. Áo thun nam basic
            [
                'danh_muc_id' => $catAoNam->id,
                'ten_san_pham' => 'Áo Thun Nam Cổ Tròn Cotton Compact 250GSM',
                'duong_dan' => 'ao-thun-nam-co-tron-cotton-compact',
                'ma_san_pham' => 'ATN-001',
                'mo_ta_ngan' => 'Áo thun trơn basic chất liệu 100% cotton compact mềm mại, thấm hút mồ hôi cực tốt.',
                'noi_dung_chi_tiet' => 'Chất liệu vải dệt kim cao cấp, co giãn 4 chiều. Phù hợp mặc hàng ngày, đi chơi, đi làm văn phòng phong cách năng động.',
                'gia_ban' => 199000,
                'gia_goc' => 250000,
                'so_luong_ton' => 100,
                'hinh_anh_chinh' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=800&q=80',
                'trang_thai' => 'dang_ban',
                'noi_bat' => true,
                'bien_the' => [
                    ['kich_co' => 'M', 'mau_sac' => 'Trắng', 'so_luong_ton' => 30, 'ma_bien_the' => 'ATN-001-W-M', 'gia_tang_them' => 0],
                    ['kich_co' => 'L', 'mau_sac' => 'Trắng', 'so_luong_ton' => 25, 'ma_bien_the' => 'ATN-001-W-L', 'gia_tang_them' => 0],
                    ['kich_co' => 'M', 'mau_sac' => 'Đen', 'so_luong_ton' => 25, 'ma_bien_the' => 'ATN-001-B-M', 'gia_tang_them' => 0],
                    ['kich_co' => 'L', 'mau_sac' => 'Đen', 'so_luong_ton' => 20, 'ma_bien_the' => 'ATN-001-B-L', 'gia_tang_them' => 0],
                ],
            ],
            // 2. Áo polo nam
            [
                'danh_muc_id' => $catAoNam->id,
                'ten_san_pham' => 'Áo Polo Nam Phối Bo Cổ Dệt Thể Thao',
                'duong_dan' => 'ao-polo-nam-phoi-bo-co-det',
                'ma_san_pham' => 'POLO-002',
                'mo_ta_ngan' => 'Áo polo nam lịch lãm với chi tiết phối viền bo cổ tinh tế, form regular fit vừa vặn tôn dáng.',
                'noi_dung_chi_tiet' => 'Vải sợi cá sấu poly-cotton đứng form, không xù lông và hạn chế nhăn sau khi giặt máy.',
                'gia_ban' => 289000,
                'gia_goc' => 350000,
                'so_luong_ton' => 60,
                'hinh_anh_chinh' => 'https://images.unsplash.com/photo-1581655353564-df123a1eb820?auto=format&fit=crop&w=800&q=80',
                'trang_thai' => 'dang_ban',
                'noi_bat' => true,
                'bien_the' => [
                    ['kich_co' => 'M', 'mau_sac' => 'Xanh Navy', 'so_luong_ton' => 15, 'ma_bien_the' => 'POLO-002-NV-M', 'gia_tang_them' => 0],
                    ['kich_co' => 'L', 'mau_sac' => 'Xanh Navy', 'so_luong_ton' => 20, 'ma_bien_the' => 'POLO-002-NV-L', 'gia_tang_them' => 0],
                    ['kich_co' => 'XL', 'mau_sac' => 'Xám Tiêu', 'so_luong_ton' => 25, 'ma_bien_the' => 'POLO-002-GR-XL', 'gia_tang_them' => 10000],
                ],
            ],
            // 3. Sơ mi nữ Oxford
            [
                'danh_muc_id' => $catAoNu->id,
                'ten_san_pham' => 'Áo Sơ Mi Nữ Tay Dài Form Rộng Dáng Hàn Quốc',
                'duong_dan' => 'ao-so-mi-nu-tay-dai-form-rong',
                'ma_san_pham' => 'SMN-003',
                'mo_ta_ngan' => 'Áo sơ mi dáng oversize phong cách công sở trẻ trung, dễ dàng phối chân váy hoặc quần jeans.',
                'noi_dung_chi_tiet' => 'Vải thô lụa mềm mát, thoáng khí, thiết kế tay bồng nhẹ nhẹ nhàng nữ tính.',
                'gia_ban' => 245000,
                'gia_goc' => 290000,
                'so_luong_ton' => 45,
                'hinh_anh_chinh' => 'https://images.unsplash.com/photo-1598554747436-c9293d6a588f?auto=format&fit=crop&w=800&q=80',
                'trang_thai' => 'dang_ban',
                'noi_bat' => false,
                'bien_the' => [
                    ['kich_co' => 'S', 'mau_sac' => 'Xanh Pastel', 'so_luong_ton' => 15, 'ma_bien_the' => 'SMN-003-BL-S', 'gia_tang_them' => 0],
                    ['kich_co' => 'M', 'mau_sac' => 'Xanh Pastel', 'so_luong_ton' => 15, 'ma_bien_the' => 'SMN-003-BL-M', 'gia_tang_them' => 0],
                    ['kich_co' => 'S', 'mau_sac' => 'Trắng Kem', 'so_luong_ton' => 15, 'ma_bien_the' => 'SMN-003-CR-S', 'gia_tang_them' => 0],
                ],
            ],
            // 4. Váy hoa nhí vintage
            [
                'danh_muc_id' => $catVayNu->id,
                'ten_san_pham' => 'Đầm Hoa Nhí Cổ Vuông Tay Phồng Dáng Xòe Vintage',
                'duong_dan' => 'dam-hoa-nhi-co-vuong-vintage',
                'ma_san_pham' => 'DAM-004',
                'mo_ta_ngan' => 'Đầm xòe hoa nhí tiểu thư điệu đà, eo may bo chun co giãn thoải mái khi mặc.',
                'noi_dung_chi_tiet' => 'Chất liệu voan tơ cao cấp có lớp lót lụa bên trong kín đáo, không lo lộ, tôn dáng triệt để.',
                'gia_ban' => 350000,
                'gia_goc' => 420000,
                'so_luong_ton' => 30,
                'hinh_anh_chinh' => 'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?auto=format&fit=crop&w=800&q=80',
                'trang_thai' => 'dang_ban',
                'noi_bat' => true,
                'bien_the' => [
                    ['kich_co' => 'S', 'mau_sac' => 'Hoa Vàng', 'so_luong_ton' => 10, 'ma_bien_the' => 'DAM-004-Y-S', 'gia_tang_them' => 0],
                    ['kich_co' => 'M', 'mau_sac' => 'Hoa Vàng', 'so_luong_ton' => 10, 'ma_bien_the' => 'DAM-004-Y-M', 'gia_tang_them' => 0],
                    ['kich_co' => 'M', 'mau_sac' => 'Hoa Xanh', 'so_luong_ton' => 10, 'ma_bien_the' => 'DAM-004-B-M', 'gia_tang_them' => 0],
                ],
            ],
            // 5. Quần Jeans Nam Slim Fit
            [
                'danh_muc_id' => $catQuanNam->id,
                'ten_san_pham' => 'Quần Jeans Nam Dáng Slim Fit Co Giãn Xanh Rách Gối',
                'duong_dan' => 'quan-jeans-nam-slim-fit-xanh-rach-goi',
                'ma_san_pham' => 'JEAN-005',
                'mo_ta_ngan' => 'Quần bò nam chất denim dày dặn co giãn, wash màu hiện đại và cá tính.',
                'noi_dung_chi_tiet' => 'Form ôm nhẹ chân, tôn dáng người mặc, đường chỉ may 2 lớp chắc chắn bền màu.',
                'gia_ban' => 380000,
                'gia_goc' => 450000,
                'so_luong_ton' => 50,
                'hinh_anh_chinh' => 'https://images.unsplash.com/photo-1542272604-780c96856592?auto=format&fit=crop&w=800&q=80',
                'trang_thai' => 'dang_ban',
                'noi_bat' => false,
                'bien_the' => [
                    ['kich_co' => '29', 'mau_sac' => 'Xanh Đậm', 'so_luong_ton' => 15, 'ma_bien_the' => 'JEAN-005-29', 'gia_tang_them' => 0],
                    ['kich_co' => '30', 'mau_sac' => 'Xanh Đậm', 'so_luong_ton' => 15, 'ma_bien_the' => 'JEAN-005-30', 'gia_tang_them' => 0],
                    ['kich_co' => '31', 'mau_sac' => 'Xanh Đậm', 'so_luong_ton' => 20, 'ma_bien_the' => 'JEAN-005-31', 'gia_tang_them' => 0],
                ],
            ],
            // 6. Áo khoác Hoodie Unisex
            [
                'danh_muc_id' => $catAoKhoac->id,
                'ten_san_pham' => 'Áo Hoodie Unisex Form Rộng Nỉ Bông Dày Dặn Mũ 2 Lớp',
                'duong_dan' => 'ao-hoodie-unisex-form-rong-ni-bong',
                'ma_san_pham' => 'HOD-006',
                'mo_ta_ngan' => 'Áo hoodie trơn unisex mùa đông ấm áp, giữ nhiệt tốt, mũ to trùm đầu phong cách streetwear.',
                'noi_dung_chi_tiet' => 'Vải nỉ chân cua định lượng 380GSM, bo chun gấu áo và cổ tay dày dặn không bai dão.',
                'gia_ban' => 320000,
                'gia_goc' => 399000,
                'so_luong_ton' => 80,
                'hinh_anh_chinh' => 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?auto=format&fit=crop&w=800&q=80',
                'trang_thai' => 'dang_ban',
                'noi_bat' => true,
                'bien_the' => [
                    ['kich_co' => 'L', 'mau_sac' => 'Đen', 'so_luong_ton' => 25, 'ma_bien_the' => 'HOD-006-BK-L', 'gia_tang_them' => 0],
                    ['kich_co' => 'XL', 'mau_sac' => 'Đen', 'so_luong_ton' => 25, 'ma_bien_the' => 'HOD-006-BK-XL', 'gia_tang_them' => 0],
                    ['kich_co' => 'L', 'mau_sac' => 'Xám Chì', 'so_luong_ton' => 15, 'ma_bien_the' => 'HOD-006-GR-L', 'gia_tang_them' => 0],
                    ['kich_co' => 'XL', 'mau_sac' => 'Xám Chì', 'so_luong_ton' => 15, 'ma_bien_the' => 'HOD-006-GR-XL', 'gia_tang_them' => 0],
                ],
            ],
            // 7. Sản phẩm Hết Hàng (Test Out of Stock)
            [
                'danh_muc_id' => $catAoNam->id,
                'ten_san_pham' => 'Áo Khoác Bomber Kaki Nam 2 Mặt (HẾT HÀNG - Test Out of Stock)',
                'duong_dan' => 'ao-khoac-bomber-kaki-nam-het-hang',
                'ma_san_pham' => 'TEST-OOS-007',
                'mo_ta_ngan' => 'Sản phẩm mẫu có tồn kho bằng 0 để kiểm tra kịch bản mua hàng khi hết tồn kho.',
                'noi_dung_chi_tiet' => 'Dùng để test validation: không thể thêm vào giỏ hàng hoặc cảnh báo tồn kho.',
                'gia_ban' => 450000,
                'gia_goc' => 550000,
                'so_luong_ton' => 0,
                'hinh_anh_chinh' => 'https://images.unsplash.com/photo-1548883354-7622d03aca27?auto=format&fit=crop&w=800&q=80',
                'trang_thai' => 'het_hang',
                'noi_bat' => false,
                'bien_the' => [
                    ['kich_co' => 'L', 'mau_sac' => 'Rêu', 'so_luong_ton' => 0, 'ma_bien_the' => 'TEST-OOS-007-L', 'gia_tang_them' => 0],
                ],
            ],
            // 8. Sản phẩm Tạm Ẩn (Test Inactive)
            [
                'danh_muc_id' => $catAoNu->id,
                'ten_san_pham' => 'Áo Croptop Nữ Len Dệt Kim (TẮT BÁN - Test Inactive)',
                'duong_dan' => 'ao-croptop-nu-len-det-kim-inactive',
                'ma_san_pham' => 'TEST-INACT-008',
                'mo_ta_ngan' => 'Sản phẩm ở trạng thái tam_an để kiểm tra bộ lọc sản phẩm đang kinh doanh.',
                'noi_dung_chi_tiet' => 'Không được hiển thị ở màn hình người dùng.',
                'gia_ban' => 150000,
                'gia_goc' => 180000,
                'so_luong_ton' => 20,
                'hinh_anh_chinh' => 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=800&q=80',
                'trang_thai' => 'tam_an',
                'noi_bat' => false,
                'bien_the' => [],
            ],
        ];

        foreach ($productsData as $data) {
            $bienTheList = $data['bien_the'] ?? [];
            unset($data['bien_the']);

            $product = Product::updateOrCreate(
                ['ma_san_pham' => $data['ma_san_pham']],
                $data
            );

            foreach ($bienTheList as $variant) {
                ProductVariant::updateOrCreate(
                    ['san_pham_id' => $product->id, 'ma_bien_the' => $variant['ma_bien_the']],
                    array_merge($variant, ['san_pham_id' => $product->id])
                );
            }
        }
    }
}
