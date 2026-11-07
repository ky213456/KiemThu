<?php

namespace Database\Seeders;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class CartItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customer = User::where('email', 'customer@shop.com')->first();
        $product1 = Product::where('ma_san_pham', 'ATN-001')->with('variants')->first();
        $product2 = Product::where('ma_san_pham', 'HOD-006')->with('variants')->first();

        if (!$customer || !$product1) {
            return;
        }

        CartItem::updateOrCreate(
            [
                'nguoi_dung_id' => $customer->id,
                'san_pham_id' => $product1->id,
            ],
            [
                'bien_the_id' => $product1->variants->first()?->id,
                'so_luong' => 2,
            ]
        );

        if ($product2) {
            CartItem::updateOrCreate(
                [
                    'nguoi_dung_id' => $customer->id,
                    'san_pham_id' => $product2->id,
                ],
                [
                    'bien_the_id' => $product2->variants->first()?->id,
                    'so_luong' => 1,
                ]
            );
        }
    }
}
