<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'action', 'description'];

    // Relasi untuk tahu ini log milik User siapa
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Fungsi canggih biar kita gampang nyatet log dari mana aja
    public static function catat($action, $description)
    {
        self::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'description' => $description
        ]);
    }
}