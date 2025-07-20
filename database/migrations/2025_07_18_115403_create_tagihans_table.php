<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tagihans', function (Blueprint $table) {
            $table->id('id_tagihan'); // PK id_tagihan
            $table->unsignedBigInteger('id_penggunaan'); // Foreign key ke tabel penggunaans
            $table->unsignedBigInteger('id_pelanggan'); // Foreign key ke tabel pelanggans
            $table->string('bulan'); // Atau integer
            $table->string('tahun'); // Atau integer
            $table->integer('jumlah_meter');
            $table->decimal('total_tagihan', 10, 2);
            $table->enum('status', ['belum_dibayar', 'sudah_dibayar', 'dibatalkan'])->default('belum_dibayar');
            $table->timestamps();

            // Definisi foreign keys
            $table->foreign('id_penggunaan')->references('id_penggunaan')->on('penggunaans')->onDelete('cascade');
            $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihans');
    }
};
