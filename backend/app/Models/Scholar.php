<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Scholar extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'application_id',
        'scholarship_id',
        'applicant_id',
        'scholar_number',
        'start_date',
        'end_date',
        'status',
        'current_gwa',
        'is_renewable',
        'suspension_reason',
        'termination_reason',
        'contract_signed_by',
        'contract_signed_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'current_gwa' => 'decimal:2',
        'is_renewable' => 'boolean',
        'contract_signed_at' => 'datetime',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class);
    }

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }

    public function signer()
    {
        return $this->belongsTo(User::class, 'contract_signed_by');
    }

    public function disbursements()
    {
        return $this->hasMany(Disbursement::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
}
