<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'ho_ten' => 'Quản Trị Viên (Admin)',
                'email' => 'admin@shop.com',
                'mat_khau' => Hash::make('password123'),
                'vai_tro' => 'quan_tri',
                'so_dien_thoai' => '0988888888',
                'dia_chi' => '123 Đường Cầu Giấy, Hà Nội',
                'anh_dai_dien' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=300&q=80',
                'trang_thai' => true,
            ],
            [
                'ho_ten' => 'Nhân Viên Bán Hàng',
                'email' => 'staff@shop.com',
                'mat_khau' => Hash::make('password123'),
                'vai_tro' => 'nhan_vien',
                'so_dien_thoai' => '0977777777',
                'dia_chi' => '456 Nguyễn Trãi, Thanh Xuân, Hà Nội',
                'anh_dai_dien' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=300&q=80',
                'trang_thai' => true,
            ],
            [
                'ho_ten' => 'Nguyễn Văn An (Khách Hàng)',
                'email' => 'customer@shop.com',
                'mat_khau' => Hash::make('password123'),
                'vai_tro' => 'khach_hang',
                'so_dien_thoai' => '0912345678',
                'dia_chi' => '789 Lê Lợi, Quận 1, TP. Hồ Chí Minh',
                'anh_dai_dien' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=300&q=80',
                'trang_thai' => true,
            ],
            [
                'ho_ten' => 'Trần Thị Mai',
                'email' => 'maitt@gmail.com',
                'mat_khau' => Hash::make('password123'),
                'vai_tro' => 'khach_hang',
                'so_dien_thoai' => '0905123456',
                'dia_chi' => '102 Hải Phòng, Q. Hải Châu, Đà Nẵng',
                'anh_dai_dien' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=300&q=80',
                'trang_thai' => true,
            ],
            [
                'ho_ten' => 'Tài Khoản Bị Khóa (Test Negative Case)',
                'email' => 'blocked@shop.com',
                'mat_khau' => Hash::make('password123'),
                'vai_tro' => 'khach_hang',
                'so_dien_thoai' => '0933333333',
                'dia_chi' => 'Số 1 Đại Cồ Việt, Hà Nội',
                'anh_dai_dien' => null,
                'trang_thai' => false,
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                $user
            );
        }
    }
}
