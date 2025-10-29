<?php

namespace App\Http\Controllers\Api;

use App\Models\Payment;
use App\Models\Installment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class InstallmentController extends Controller
{
    public function pay(Request $request, $id)
    {
        $installment = Installment::findOrFail($id);
        $user = Auth::user();

        if ($installment->is_paid) {
            return response()->json(['message' => 'Installment already paid'], 400);
        }

        $payment = Payment::create([
            'installment_id' => $installment->id,
            'user_id' => $user->id,
            'amount_paid' => $installment->total_amount,
            'payment_date' => Carbon::now(),
            'payment_method' => $request->input('payment_method', 'cash'),
        ]);

        $installment->update(['is_paid' => true]);

        return response()->json([
            'message' => 'Payment successful',
            'data' => $payment,
        ]);
    }
}
