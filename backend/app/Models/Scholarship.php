<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Scholarship extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'type',
        'budget',
        'allowance_per_month',
        'max_slots',
        'current_slots',
        'application_start',
        'application_end',
        'qualifications',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'application_start' => 'date',
        'application_end' => 'date',
        'budget' => 'decimal:2',
        'allowance_per_month' => 'decimal:2',
        'qualifications' => 'array',
        'is_active' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function requirements()
    {
        return $this->hasMany(Requirement::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function scholars()
    {
        return $this->hasMany(Scholar::class);
    }

    public function screenings()
    {
        return $this->hasMany(Screening::class);
    }
}
