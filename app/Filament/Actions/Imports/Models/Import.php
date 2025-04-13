<?php

namespace App\Filament\Actions\Imports\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Import extends Model
{
    protected $fillable = [
        'user_id',
        'file_name',
        'file_path',
        'file_disk',
        'importer',
        'options',
        'column_map',
        'processed_rows',
        'total_rows',
        'successful_rows',
        'status',
    ];

    protected $casts = [
        'options' => 'array',
        'column_map' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }
}
