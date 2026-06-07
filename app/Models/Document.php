<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'document_number',
        'title',
        'category',
        'document_date',
        'bidang',
        'description',
        'file_path',
    ];

    // Relasi kelas dunia: Sistem tahu dokumen ini di-upload oleh User siapa
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}