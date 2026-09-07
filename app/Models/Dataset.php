<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dataset extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category',
        'user_id',
        'opd_id',
        'is_public',
        'row_count',
        'file_size',
        'published_at',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function opd()
    {
        return $this->belongsTo(OPD::class);
    }

    public function files()
    {
        return $this->hasMany(DatasetFile::class);
    }

    public function metadata()
    {
        return $this->hasOne(DatasetMetadata::class);
    }
}
