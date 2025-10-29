<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $payments = Payment::with('installment.loan')
            ->where('user_id', $user->id)
            ->orderBy('payment_date', 'desc')
            ->get();

        return response()->json($payments);
    }
}
