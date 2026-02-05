<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'scholar_id',
        'subject_code',
        'subject_name',
        'grade',
        'units',
        'semester',
        'school_year',
        'remarks',
        'encoded_by',
    ];

    protected $casts = [
        'grade' => 'decimal:2',
    ];

    public function scholar()
    {
        return $this->belongsTo(Scholar::class);
    }

    public function encoder()
    {
        return $this->belongsTo(User::class, 'encoded_by');
    }
}
