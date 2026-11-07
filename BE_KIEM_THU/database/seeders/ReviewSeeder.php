<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customer1 = User::where('email', 'customer@shop.com')->first();
        $customer2 = User::where('email', 'maitt@gmail.com')->first();
        $product1 = Product::where('ma_san_pham', 'ATN-001')->first();
        $product2 = Product::where('ma_san_pham', 'POLO-002')->first();
        $product4 = Product::where('ma_san_pham', 'DAM-004')->first();

        if (!$customer1 || !$product1) {
            return;
        }

        $reviews = [
            [
                'nguoi_dung_id' => $customer1->id,
                'san_pham_id' => $product1->id,
                'so_sao' => 5,
                'noi_dung' => 'Áo chất vải rất mát, đường may tỉ mỉ, mặc vừa in form người. Shop giao nhanh 2 ngày là có hàng!',
                'trang_thai' => 'da_duyet',
            ],
            [
                'nguoi_dung_id' => $customer2 ? $customer2->id : $customer1->id,
                'san_pham_id' => $product1->id,
                'so_sao' => 4,
                'noi_dung' => 'Vải đẹp, đóng gói cẩn thận. Mua áo trắng nên hơi dễ dính bẩn một chút nhưng nhìn rất sáng sủa.',
                'trang_thai' => 'da_duyet',
            ],
            [
                'nguoi_dung_id' => $customer1->id,
                'san_pham_id' => $product2 ? $product2->id : $product1->id,
                'so_sao' => 5,
                'noi_dung' => 'Áo polo đứng form xịn sò, cổ dệt viền rất tinh tế. Sẽ tiếp tục ủng hộ shop.',
                'trang_thai' => 'da_duyet',
            ],
            [
                'nguoi_dung_id' => $customer2 ? $customer2->id : $customer1->id,
                'san_pham_id' => $product4 ? $product4->id : $product1->id,
                'so_sao' => 5,
                'noi_dung' => 'Váy hoa nhí cưng xỉu, chất voan mềm mại có lớp lót kín đáo. Mặc đi dạo biển chụp hình sống ảo hết nước chấm!',
                'trang_thai' => 'da_duyet',
            ],
            // Review 2 sao (test phản hồi tiêu cực)
            [
                'nguoi_dung_id' => $customer1->id,
                'san_pham_id' => $product2 ? $product2->id : $product1->id,
                'so_sao' => 2,
                'noi_dung' => 'Màu thực tế hơi tối hơn so với hình một chút, shipper giao trễ 1 ngày.',
                'trang_thai' => 'da_duyet',
            ],
            // Review chờ duyệt (cho_duyet) - Test kiểm thử phía Admin duyệt bình luận
            [
                'nguoi_dung_id' => $customer2 ? $customer2->id : $customer1->id,
                'san_pham_id' => $product1->id,
                'so_sao' => 3,
                'noi_dung' => 'Đánh giá chờ admin kiểm duyệt nội dung trước khi xuất hiện trên trang chi tiết.',
                'trang_thai' => 'cho_duyet',
            ],
        ];

        foreach ($reviews as $rev) {
            Review::create($rev);
        }
    }
}
