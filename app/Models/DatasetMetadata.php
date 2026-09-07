<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatasetMetadata extends Model
{
    use HasFactory;

    protected $fillable = [
        'dataset_id',
        'total_columns',
        'data_types',
        'quality_score',
        'last_updated_by',
    ];

    protected $casts = [
        'data_types' => 'array',
    ];

    public function dataset()
    {
        return $this->belongsTo(Dataset::class);
    }
}
