<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Installment extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_application_id',
        'installment_number',
        'due_date',
        'principal_amount',
        'interest_amount',
        'total_amount',
        'is_paid'
    ];

    public function loan()
    {
        return $this->belongsTo(LoanApplication::class, 'loan_application_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
