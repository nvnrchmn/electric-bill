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
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id('id_pembayaran'); // PK id_pembayaran
            $table->unsignedBigInteger('id_tagihan'); // Foreign key ke tabel tagihans
            $table->unsignedBigInteger('id_pelanggan'); // Foreign key ke tabel pelanggans
            $table->unsignedBigInteger('id_user'); // Foreign key ke tabel users (yang PK-nya id_user)
            $table->date('tanggal_pembayaran');
            $table->string('bulan_bayar'); // Contoh: "Januari" atau "1"
            $table->decimal('biaya_admin', 10, 2)->default(0); // Biaya admin untuk pembayaran
            $table->decimal('total_bayar', 10, 2);
            $table->string('metode_pembayaran')->nullable();
            $table->string('keterangan')->nullable(); // Keterangan tambahan untuk pembayaran
            $table->timestamps();

            // Definisi foreign keys
            $table->foreign('id_tagihan')->references('id_tagihan')->on('tagihans')->onDelete('cascade');
            $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggans')->onDelete('cascade');
            // Pastikan Anda sudah mengonfigurasi id_user sebagai primary key di tabel users
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
