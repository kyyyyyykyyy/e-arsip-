<?php

namespace App\Services;

use App\Models\Document;
use Illuminate\Support\Facades\Auth;

class DocumentService
{
    /**
     * Handle proses simpan dokumen & upload file secara aman.
     */
    public function storeDocument(array $data): Document
    {
        // 1. Ambil file PDF dari data request
        $file = $data['file'];

        // 2. Enkripsi nama file & simpan di folder PRIVATE (bukan folder public!)
        // File ini tidak akan bisa diakses langsung via URL browser biasa
        $filePath = $file->store('private/documents');

        // 3. Simpan meta data ke database MySQL
        return Document::create([
            'user_id' => Auth::id(), // ID user yang sedang login
            'document_number' => $data['document_number'],
            'title' => $data['title'],
            'category' => $data['category'],
            'document_date' => $data['document_date'],
            'description' => $data['description'] ?? null,
            'file_path' => $filePath,
        ]);
    }
}