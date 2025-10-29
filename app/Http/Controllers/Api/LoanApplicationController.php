<?php

namespace App\Http\Controllers\Api;

use App\Models\Installment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\LoanApplication;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoanApplicationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $loans = LoanApplication::where('user_id', $user->id)
            ->with('installments')
            ->get();

        return response()->json($loans);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:100000',
            'term' => 'required|integer|min:1', //tenor_mont
            'interest_rate' => 'required|numeric|min:0',
        ]);

        $loanApplication = LoanApplication::create([
            'user_id' => Auth::id(),
            'amount' => $data['amount'],
            'tenor_months' => $data['term'],
            'interest_rate' => $data['interest_rate'],
        ]);

        // Hitung bunga dan cicilan
        $loanApplication->calculateLoanDetails();
        $loanApplication->save();

        return response()->json(['message' => 'Pengajuan pinjaman berhasil!', 'data' => $loanApplication]);
    }
    public function approve($id)
    {
        $loan = LoanApplication::findOrFail($id);

        $principal = $loan->amount;
        $interestRate = $loan->interest_rate / 100;
        $term = $loan->term;

        $totalInterest = $principal * $interestRate;
        $totalRepayment = $principal + $totalInterest;
        $installmentAmount = $totalRepayment / $term;

        $loan->update([
            'status' => 'approved',
            'total_interest' => $totalInterest,
            'total_repayment' => $totalRepayment,
        ]);

        for ($i = 1; $i <= $term; $i++) {
            Installment::create([
                'loan_application_id' => $loan->id,
                'installment_number' => $i,
                'due_date' => Carbon::now()->addMonths($i),
                'principal_amount' => $principal / $term,
                'interest_amount' => $totalInterest / $term,
                'total_amount' => $installmentAmount,
            ]);
        }

        return response()->json(['message' => 'Loan approved & installments created']);
    }
    public function reject($id)
    {
        $loanApplication = LoanApplication::findOrFail($id);
        $loanApplication->status = 'rejected';
        $loanApplication->save();

        return response()->json(['message' => 'Pinjaman ditolak!', 'data' => $loanApplication]);
    }
    public function show($id)
    {
        $loan = LoanApplication::with('installments')->findOrFail($id);
        return response()->json($loan);
    }
}
