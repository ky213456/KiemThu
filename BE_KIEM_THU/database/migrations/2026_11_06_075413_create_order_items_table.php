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
        Schema::create('chi_tiet_don_hang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('don_hang_id')->constrained('don_hang')->cascadeOnDelete();
            $table->foreignId('san_pham_id')->nullable()->constrained('san_pham')->nullOnDelete();
            $table->foreignId('bien_the_id')->nullable()->constrained('bien_the_san_pham')->nullOnDelete();
            $table->string('ten_san_pham');
            $table->string('thong_tin_bien_the')->nullable(); // vi du: "Kich co: L - Mau sac: Den"
            $table->decimal('don_gia', 12, 2);
            $table->integer('so_luong')->default(1);
            $table->decimal('thanh_tien', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chi_tiet_don_hang');
    }
};
