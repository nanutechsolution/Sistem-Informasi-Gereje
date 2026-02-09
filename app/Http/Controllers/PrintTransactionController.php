<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class PrintTransactionController extends Controller
{
    public function show(Transaction $transaction)
    {
        // Pastikan load relasi yang dibutuhkan
        $transaction->load(['account', 'budgetPost', 'user']);
        
        return view('finance.print-receipt', [
            'trx' => $transaction
        ]);
    }
}