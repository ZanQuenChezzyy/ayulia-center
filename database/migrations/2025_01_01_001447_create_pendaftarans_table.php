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
        Schema::create('pendaftarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->string('nama', 45);
            $table->string('email', 45);
            $table->string('no_telepon', 15);
            $table->string('tempat_lahir', 45);
            $table->date('tanggal_lahir');
            $table->text('alamat');
            $table->unsignedTinyInteger('pendidikan_terakhir')->length(1);
            $table->string('ktp_url', 100);
            $table->string('avatar_url', 100);
            $table->string('bukti_pembayaran', 100);
            $table->unsignedTinyInteger('status_pembayaran')->length(1)->default(0);
            $table->unsignedTinyInteger('status_pendaftaran')->length(1)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftarans');
    }
};
