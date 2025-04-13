<?php

namespace App\Filament\Actions\Exports\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Export extends Model
{
    protected $fillable = [
        'user_id',
        'file_name',
        'file_disk',
        'exporter',
        'filters',
        'processed_rows',
        'total_rows',
        'successful_rows',
        'successful_rows_csv_columns',
        'status',
    ];

    protected $casts = [
        'filters' => 'array',
        'successful_rows_csv_columns' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }
}
