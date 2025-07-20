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
        Schema::create('pelanggans', function (Blueprint $table) {
            $table->id('id_pelanggan'); // PK id_pelanggan
            $table->string('username')->unique(); // Pastikan username unik
            // $table->string('password');
            $table->string('nomor_kwh');
            $table->string('nama_pelanggan');
            $table->text('alamat');
            $table->unsignedBigInteger('id_tarif');
            $table->unsignedBigInteger('id_user')->nullable(); // id_user bisa null jika belum ditentukan pemiliknya

            // Definisi foreign keys
            $table->foreign('id_tarif')->references('id_tarif')->on('tarifs');
            $table->foreign('id_user')->references('id_user')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggans');
    }
};
