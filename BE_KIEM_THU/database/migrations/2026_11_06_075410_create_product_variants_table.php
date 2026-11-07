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
        Schema::create('bien_the_san_pham', function (Blueprint $table) {
            $table->id();
            $table->foreignId('san_pham_id')->constrained('san_pham')->cascadeOnDelete();
            $table->string('kich_co', 20); // S, M, L, XL, XXL, FreeSize
            $table->string('mau_sac', 50); // Den, Trang, Xanh Navy, Be...
            $table->integer('so_luong_ton')->default(0);
            $table->string('ma_bien_the')->nullable();
            $table->decimal('gia_tang_them', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bien_the_san_pham');
    }
};
