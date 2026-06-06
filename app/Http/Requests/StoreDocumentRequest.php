<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'document_number' => ['required', 'string', 'unique:documents,document_number'],
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'in:Surat Masuk,Surat Keluar,Internal'],
            'document_date' => ['required', 'date'],
            'description' => ['nullable', 'string'],
            'file' => ['required', 'file', 'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,zip,rar', 'max:10240'],
        ];
    }

    // --- TAMBAHKAN BAGIAN INI BRO ---
    // Fungsi ini buat ngubah pesan error bawaan jadi lebih asik dan jelas
    public function messages(): array
    {
        return [
            'document_number.unique' => 'Bro, Nomor Surat / File ini sudah pernah di-upload! Coba cek lagi ya.',
            'file.max' => 'Waduh, ukuran filenya kegedean bro. Maksimal 10MB ya!',
            'file.mimes' => 'Format file tidak diizinkan. Gunakan PDF, Word, Excel, Gambar, atau ZIP.',
        ];
    }
    // --------------------------------
}