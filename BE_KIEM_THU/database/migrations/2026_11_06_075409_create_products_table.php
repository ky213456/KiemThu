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
        Schema::create('san_pham', function (Blueprint $table) {
            $table->id();
            $table->foreignId('danh_muc_id')->constrained('danh_muc')->cascadeOnDelete();
            $table->string('ten_san_pham');
            $table->string('duong_dan')->unique(); // slug
            $table->string('ma_san_pham')->unique(); // sku
            $table->text('mo_ta_ngan')->nullable();
            $table->longText('noi_dung_chi_tiet')->nullable();
            $table->decimal('gia_ban', 12, 2);
            $table->decimal('gia_goc', 12, 2)->nullable();
            $table->integer('so_luong_ton')->default(0);
            $table->string('hinh_anh_chinh')->nullable();
            $table->enum('trang_thai', ['dang_ban', 'tam_an', 'het_hang'])->default('dang_ban');
            $table->boolean('noi_bat')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('san_pham');
    }
};
