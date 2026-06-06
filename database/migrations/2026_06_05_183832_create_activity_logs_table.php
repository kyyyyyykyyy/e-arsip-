<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Siapa pelakunya
            $table->string('action'); // Nama aksinya (Contoh: "Upload Arsip", "Hapus User")
            $table->text('description'); // Detailnya (Contoh: "Budi mengupload surat nomor 123")
            $table->timestamps(); // Kapan kejadiannya
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};