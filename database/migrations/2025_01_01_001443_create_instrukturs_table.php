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
        Schema::create('instrukturs', function (Blueprint $table) {
            $table->id();
            $table->string('foto', 100);
            $table->string('nama', 45);
            $table->string('no_telepon', 15);
            $table->unsignedTinyInteger('pendidikan_terakhir')->length(1);
            $table->string('sertifikat', 100);
            $table->string('pengalaman', 60);
            $table->boolean('di_tampilkan')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instrukturs');
    }
};
