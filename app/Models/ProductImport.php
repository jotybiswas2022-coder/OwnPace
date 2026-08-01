<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImport extends Model
{
    protected $fillable = [
        'user_id', 'file_name', 'total_rows', 'success_rows',
        'error_rows', 'status', 'report', 'error',
    ];

    protected $casts = [
        'report' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
