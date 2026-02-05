<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScreeningScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'screening_id',
        'application_id',
        'criteria_name',
        'score',
        'weight',
        'remarks',
        'scored_by',
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'weight' => 'decimal:2',
    ];

    public function screening()
    {
        return $this->belongsTo(Screening::class);
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function scorer()
    {
        return $this->belongsTo(User::class, 'scored_by');
    }
}
