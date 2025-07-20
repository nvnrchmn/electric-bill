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
        Schema::table('users', function (Blueprint $table) {
            // Ubah primary key menjadi 'id_user' jika belum
            // Perlu dicatat, Laravel secara default menggunakan 'id'.
            // Jika Anda ingin mengubahnya, Anda harus menanganinya di Model User.
            // Untuk kesederhanaan, kita bisa tetap menggunakan 'id' bawaan Laravel
            // dan menganggapnya sebagai 'id_user' sesuai ERD.
            // Atau, kita bisa rename kolom 'id' menjadi 'id_user'.
            // Untuk saat ini, mari kita asumsikan 'id' bawaan Laravel adalah 'id_user'.

            // Tambahkan kolom baru
            $table->string('nama_admin')->after('password')->nullable(); // Boleh null jika ada user bukan admin
            $table->unsignedBigInteger('id_level')->after('nama_admin')->nullable(); // Foreign key ke tabel levels

            // Tambahkan definisi foreign key
            $table->foreign('id_level')->references('id_level')->on('levels')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus foreign key
            $table->dropForeign(['id_level']);

            // Hapus kolom yang ditambahkan
            $table->dropColumn(['nama_admin', 'id_level']);
        });
    }
};
