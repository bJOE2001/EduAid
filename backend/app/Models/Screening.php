<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Screening extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'scholarship_id',
        'name',
        'description',
        'criteria',
        'screening_date',
        'status',
        'created_by',
    ];

    protected $casts = [
        'criteria' => 'array',
        'screening_date' => 'date',
    ];

    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scores()
    {
        return $this->hasMany(ScreeningScore::class);
    }
}
