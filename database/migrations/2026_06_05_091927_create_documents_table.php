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
            $table->string('document_number')->unique(); // Nomor DPA/RKA/Surat Laporan
            $table->string('title'); // Judul atau Nama Kegiatan
            
            // PERUBAHAN: Kategori diubah total khusus untuk Perencanaan & Pelaporan SIDEPPA
            $table->enum('category', ['DPA', 'RKA', 'Renja', 'Laporan Bulanan', 'Laporan Triwulanan']); 
            
            // PERUBAHAN: Mengganti kolom persuratan menjadi asal "Bidang" di internal Satpol PP
            $table->string('bidang'); 
            
            $table->date('document_date'); // Tanggal Pengesahan atau Pelaporan
            $table->text('description')->nullable(); // Keterangan tambahan
            $table->string('file_path'); // Lokasi aman file di Cloud / Storage
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