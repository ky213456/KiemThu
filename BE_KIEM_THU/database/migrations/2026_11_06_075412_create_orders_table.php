<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('don_hang', function (Blueprint $table) {
            $table->id();
            $table->string('ma_don_hang')->unique();
            $table->foreignId('nguoi_dung_id')->nullable()->constrained('nguoi_dung')->nullOnDelete();
            $table->string('ho_ten_nguoi_nhan');
            $table->string('email_nguoi_nhan');
            $table->string('so_dien_thoai_nguoi_nhan');
            $table->string('dia_chi_giao_hang');
            $table->enum('phuong_thuc_thanh_toan', ['tien_mat', 'chuyen_khoan', 'vnpay'])->default('tien_mat');
            $table->enum('trang_thai_thanh_toan', ['chua_thanh_toan', 'da_thanh_toan', 'hoan_tien'])->default('chua_thanh_toan');
            $table->enum('trang_thai_don_hang', ['cho_xac_nhan', 'dang_xu_ly', 'dang_giao', 'da_giao', 'da_huy'])->default('cho_xac_nhan');
            $table->decimal('tien_hang', 12, 2)->default(0);
            $table->decimal('tien_giam_gia', 12, 2)->default(0);
            $table->decimal('phi_van_chuyen', 12, 2)->default(0);
            $table->decimal('tong_tien', 12, 2)->default(0);
            $table->string('ma_giam_gia')->nullable();
            $table->text('ghi_chu')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('don_hang');
    }
};
