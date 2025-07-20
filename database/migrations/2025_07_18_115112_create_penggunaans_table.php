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
        Schema::create('penggunaans', function (Blueprint $table) {
            $table->id('id_penggunaan'); // PK id_penggunaan
            $table->unsignedBigInteger('id_pelanggan'); // Foreign key ke tabel pelanggans

            // Hapus .after('id_pelanggan') di sini karena kita sedang membuat tabel
            $table->unsignedBigInteger('petugas_id')->nullable(); // Kolom petugas_id

            $table->integer('bulan');
            $table->integer('tahun');
            $table->date('tanggal_periksa'); // Tanggal pemeriksaan meter
            $table->integer('meter_awal');
            $table->integer('meter_akhir');

            // Relasi ke tabel pelanggans
            // Ini diasumsikan sudah benar jika sebelumnya tidak ada error di sini
            $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggans')->onDelete('cascade');

            // Relasi ke tabel users
            // KOREKSI UTAMA: Menggunakan 'id_user' sesuai koreksi Anda
            $table->foreign('petugas_id')->references('id_user')->on('users')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penggunaans');
    }
};
