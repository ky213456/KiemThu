<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'ten_danh_muc' => 'Áo Nam',
                'duong_dan' => 'ao-nam',
                'mo_ta' => 'Áo thun, áo polo, áo sơ mi nam thanh lịch và năng động.',
                'hinh_anh' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=600&q=80',
                'trang_thai' => true,
            ],
            [
                'ten_danh_muc' => 'Áo Nữ',
                'duong_dan' => 'ao-nu',
                'mo_ta' => 'Áo kiểu, áo croptop, sơ mi nữ trẻ trung và thời trang.',
                'hinh_anh' => 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?auto=format&fit=crop&w=600&q=80',
                'trang_thai' => true,
            ],
            [
                'ten_danh_muc' => 'Quần Nam',
                'duong_dan' => 'quan-nam',
                'mo_ta' => 'Quần jeans, quần âu, quần kaki và quần short nam cá tính.',
                'hinh_anh' => 'https://images.unsplash.com/photo-1542272604-780c96856592?auto=format&fit=crop&w=600&q=80',
                'trang_thai' => true,
            ],
            [
                'ten_danh_muc' => 'Quần & Chân Váy Nữ',
                'duong_dan' => 'quan-chan-vay-nu',
                'mo_ta' => 'Chân váy chữ A, chân váy xếp ly và quần suông nữ.',
                'hinh_anh' => 'https://images.unsplash.com/photo-1583496661160-fb5886a0aaaa?auto=format&fit=crop&w=600&q=80',
                'trang_thai' => true,
            ],
            [
                'ten_danh_muc' => 'Váy & Đầm Nữ',
                'duong_dan' => 'vay-dam-nu',
                'mo_ta' => 'Đầm dạo phố, đầm hoa nhí, đầm dự tiệc phong cách.',
                'hinh_anh' => 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?auto=format&fit=crop&w=600&q=80',
                'trang_thai' => true,
            ],
            [
                'ten_danh_muc' => 'Áo Khoác & Hoodie',
                'duong_dan' => 'ao-khoac-hoodie',
                'mo_ta' => 'Áo khoác gió, áo cardigan, hoodie và bomber unisex.',
                'hinh_anh' => 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?auto=format&fit=crop&w=600&q=80',
                'trang_thai' => true,
            ],
            [
                'ten_danh_muc' => 'Danh Mục Ẩn (Test Inactive)',
                'duong_dan' => 'danh-muc-an',
                'mo_ta' => 'Danh mục này dùng để test kịch bản danh mục bị vô hiệu hóa.',
                'hinh_anh' => null,
                'trang_thai' => false,
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['duong_dan' => $cat['duong_dan']], $cat);
        }
    }
}
