<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionFormRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TransactionFormRequest $request)
    {
        return DB::transaction(function () use ($request) {
            try {
                $transactionData = $request->validated();

                // Create the transaction
                $transaction = Transaction::create([
                    'user_id' => $request->user()->id,
                    'type' => $transactionData['type'],
                    'category_id' => $transactionData['category_id'],
                    'description' => $transactionData['description'],
                    'amount' => $transactionData['amount'],
                    'date' => $transactionData['date'] ?? now(),
                ]);

                if (!$transaction) {
                    return response()->json(['message' => 'Transaction creation failed'], 500);
                }

                return TransactionResource::make($transaction);
            } catch (\Exception $e) {
                Log::error('Transaction creation failed: ' . $e->getMessage());

                DB::rollBack();
                return response()->json([
                    'message' => 'Failed to create transaction',
                    'error' => $e->getMessage()
                ], 500);
            }
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
