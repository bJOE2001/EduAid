<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Requirement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'scholarship_id',
        'name',
        'description',
        'is_required',
        'file_type',
        'order',
    ];

    protected $casts = [
        'is_required' => 'boolean',
    ];

    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class);
    }

    public function documents()
    {
        return $this->hasMany(ApplicationDocument::class);
    }
}
