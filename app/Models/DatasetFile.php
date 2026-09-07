<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatasetFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'dataset_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'column_headers',
    ];

    protected $casts = [
        'column_headers' => 'array',
    ];

    public function dataset()
    {
        return $this->belongsTo(Dataset::class);
    }
}
