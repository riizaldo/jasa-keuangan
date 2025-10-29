<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LoanApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'tenor_months',
        'interest_rate',
        'total_payment',
        'monthly_installment',
        'status',
    ];
    protected $casts = [
        'status' => 'string',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function installments()
    {
        return $this->hasMany(Installment::class);
    }
    public function calculateLoanDetails()
    {
        // Hitung bunga per bulan
        $monthlyInterestRate = ($this->interest_rate / 100) / 12;
        // Cicilan pokok per bulan
        $monthlyPrincipal = $this->amount / $this->tenor_months;
        // Cicilan total per bulan (pokok + bunga)
        $monthlyInstallment = $monthlyPrincipal + ($this->amount * $monthlyInterestRate);
        // Total pembayaran pinjaman (pokok + bunga)
        $totalPayment = $monthlyInstallment * $this->tenor_months;

        $this->monthly_installment = round($monthlyInstallment, 2);
        $this->total_payment = round($totalPayment, 2);
    }
}
