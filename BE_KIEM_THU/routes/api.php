<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DanhMucController;
use App\Http\Controllers\SanPhamController;
use App\Http\Controllers\DonHangController;
use App\Http\Controllers\MaGiamGiaController;
use App\Http\Controllers\DanhGiaController;
use App\Http\Controllers\NguoiDungController;
use App\Http\Controllers\BaiVietController;

// Authentication & Khách hàng
Route::post('/dang-nhap', [AuthController::class, 'dangNhap']);
Route::post('/login', [AuthController::class, 'dangNhap']);
Route::post('/dang-ky', [AuthController::class, 'dangKy']);
Route::post('/register', [AuthController::class, 'dangKy']);
Route::post('/quen-mat-khau', [AuthController::class, 'quenMatKhau']);
Route::post('/forgot-password', [AuthController::class, 'quenMatKhau']);
Route::post('/dat-lai-mat-khau', [AuthController::class, 'datLaiMatKhau']);
Route::post('/reset-password', [AuthController::class, 'datLaiMatKhau']);

Route::group(['prefix' => 'admin'], function () {

    // 1. Quản lý Danh mục / Bộ sưu tập Lookbook
    Route::group(['prefix' => 'danh-muc'], function () {
        Route::get('/get-data', [DanhMucController::class, 'getData']);
        Route::post('/add-data', [DanhMucController::class, 'addData']);
        Route::post('/create', [DanhMucController::class, 'addData']);
        Route::post('/update', [DanhMucController::class, 'update']);
        Route::post('/destroy', [DanhMucController::class, 'destroy']);
        Route::post('/delete', [DanhMucController::class, 'destroy']);
        Route::post('/change-status', [DanhMucController::class, 'changeStatus']);
    });

    // 2. Quản lý Sản phẩm thời trang & Kho biến thể
    Route::group(['prefix' => 'san-pham'], function () {
        Route::get('/get-data', [SanPhamController::class, 'getData']);
        Route::post('/add-data', [SanPhamController::class, 'addData']);
        Route::post('/create', [SanPhamController::class, 'addData']);
        Route::post('/update', [SanPhamController::class, 'update']);
        Route::post('/destroy', [SanPhamController::class, 'destroy']);
        Route::post('/delete', [SanPhamController::class, 'destroy']);
        Route::post('/change-status', [SanPhamController::class, 'changeStatus']);
    });

    // 3. Quản lý Đơn hàng & POS Bán lẻ
    Route::group(['prefix' => 'don-hang'], function () {
        Route::get('/get-data', [DonHangController::class, 'getData']);
        Route::post('/add-data', [DonHangController::class, 'addData']);
        Route::post('/create', [DonHangController::class, 'addData']);
        Route::post('/update', [DonHangController::class, 'update']);
        Route::post('/destroy', [DonHangController::class, 'destroy']);
        Route::post('/delete', [DonHangController::class, 'destroy']);
        Route::post('/change-status', [DonHangController::class, 'changeStatus']);
        Route::post('/change-payment-status', [DonHangController::class, 'changePaymentStatus']);
    });

    // 4. Quản lý Mã giảm giá & Voucher VIP
    Route::group(['prefix' => 'ma-giam-gia'], function () {
        Route::get('/get-data', [MaGiamGiaController::class, 'getData']);
        Route::post('/add-data', [MaGiamGiaController::class, 'addData']);
        Route::post('/create', [MaGiamGiaController::class, 'addData']);
        Route::post('/update', [MaGiamGiaController::class, 'update']);
        Route::post('/destroy', [MaGiamGiaController::class, 'destroy']);
        Route::post('/delete', [MaGiamGiaController::class, 'destroy']);
        Route::post('/change-status', [MaGiamGiaController::class, 'changeStatus']);
    });

    // 5. Quản lý Đánh giá & Phản hồi
    Route::group(['prefix' => 'danh-gia'], function () {
        Route::get('/get-data', [DanhGiaController::class, 'getData']);
        Route::post('/add-data', [DanhGiaController::class, 'addData']);
        Route::post('/create', [DanhGiaController::class, 'addData']);
        Route::post('/update', [DanhGiaController::class, 'update']);
        Route::post('/destroy', [DanhGiaController::class, 'destroy']);
        Route::post('/delete', [DanhGiaController::class, 'destroy']);
        Route::post('/change-status', [DanhGiaController::class, 'changeStatus']);
    });

    // 6. Quản lý Tài khoản & Phân quyền Người dùng
    Route::group(['prefix' => 'nguoi-dung'], function () {
        Route::get('/get-data', [NguoiDungController::class, 'getData']);
        Route::post('/add-data', [NguoiDungController::class, 'addData']);
        Route::post('/create', [NguoiDungController::class, 'addData']);
        Route::post('/update', [NguoiDungController::class, 'update']);
        Route::post('/destroy', [NguoiDungController::class, 'destroy']);
        Route::post('/delete', [NguoiDungController::class, 'destroy']);
        Route::post('/change-status', [NguoiDungController::class, 'changeStatus']);
    });

    // 7. Quản lý Bài viết / Lookbook Blog
    Route::group(['prefix' => 'bai-viet'], function () {
        Route::get('/get-data', [BaiVietController::class, 'getData']);
        Route::post('/add-data', [BaiVietController::class, 'addData']);
        Route::post('/create', [BaiVietController::class, 'addData']);
        Route::post('/update', [BaiVietController::class, 'update']);
        Route::post('/destroy', [BaiVietController::class, 'destroy']);
        Route::post('/delete', [BaiVietController::class, 'destroy']);
        Route::post('/change-status', [BaiVietController::class, 'changeStatus']);
    });

});
