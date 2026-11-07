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
        Schema::create('ma_giam_gia', function (Blueprint $table) {
            $table->id();
            $table->string('ma_giam_gia')->unique();
            $table->string('ten_ma');
            $table->enum('loai_giam_gia', ['phan_tram', 'tien_mat'])->default('tien_mat');
            $table->decimal('gia_tri_giam', 12, 2);
            $table->decimal('don_hang_toi_thieu', 12, 2)->default(0);
            $table->decimal('giam_toi_da', 12, 2)->nullable();
            $table->integer('so_luong_ma')->nullable();
            $table->integer('so_luong_da_dung')->default(0);
            $table->dateTime('ngay_bat_dau')->nullable();
            $table->dateTime('ngay_ket_thuc')->nullable();
            $table->boolean('trang_thai')->default(true); // 1: hoat dong, 0: ngung
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ma_giam_gia');
    }
};
