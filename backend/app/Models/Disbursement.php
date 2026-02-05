<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Disbursement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'scholar_id',
        'reference_number',
        'amount',
        'type',
        'status',
        'scheduled_date',
        'released_date',
        'remarks',
        'released_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'scheduled_date' => 'date',
        'released_date' => 'date',
    ];

    public function scholar()
    {
        return $this->belongsTo(Scholar::class);
    }

    public function releaser()
    {
        return $this->belongsTo(User::class, 'released_by');
    }
}
