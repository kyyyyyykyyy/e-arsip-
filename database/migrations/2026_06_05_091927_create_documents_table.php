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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Tracking siapa yang upload
            $table->string('document_number')->unique(); // Nomor surat, wajib unik
            $table->string('title'); // Judul atau perihal surat
            $table->enum('category', ['Surat Masuk', 'Surat Keluar', 'Internal']); // Kategori arsip
            $table->date('document_date'); // Tanggal fisik surat
            
            // Kolom spesifik Surat Satpol PP
            $table->string('origin_office')->nullable(); // Asal Surat
            $table->string('destination_office')->nullable(); // Tujuan Instansi
            $table->string('destination_field')->nullable(); // Tujuan Bidang
            
            $table->text('description')->nullable(); // Keterangan tambahan
            $table->string('file_path'); // Lokasi aman file
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};