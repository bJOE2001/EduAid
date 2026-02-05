<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'applicant_id',
        'scholarship_id',
        'status',
        'remarks',
        'reviewed_by',
        'reviewed_at',
        'approved_by',
        'approved_at',
        'total_score',
        'rank',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'approved_at' => 'datetime',
        'total_score' => 'decimal:2',
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }

    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function documents()
    {
        return $this->hasMany(ApplicationDocument::class);
    }

    public function screeningScores()
    {
        return $this->hasMany(ScreeningScore::class);
    }

    public function scholar()
    {
        return $this->hasOne(Scholar::class);
    }
}
