<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionFormRequest;
use App\Http\Requests\UpdateTransactionFormRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => 'sometimes|string|max:255',
            'category' => 'sometimes|integer|exists:categories,id',
            'type' => 'sometimes|string|in:income,expense,transfer',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date',
        ]);

        $transactions = Transaction::filter($validated)
            ->where('user_id', auth()->user()->id)
            ->with('category') // Carregar a relação 'category'
            ->orderBy('date', 'desc')
            ->paginate(10);

        return TransactionResource::collection($transactions);
    }

    public function store(StoreTransactionFormRequest $request)
    {
        return DB::transaction(function () use ($request) {
            try {
                $transactionData = $request->validated();

                // Create the transaction
                $transaction = Transaction::create([
                    'user_id' => auth()->user()->id,
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

    public function show(Transaction $transaction)
    {
        Gate::authorize('view', $transaction);

        return TransactionResource::make($transaction);
    }

    public function update(UpdateTransactionFormRequest $request, Transaction $transaction)
    {
        Gate::authorize('update', $transaction);

        return DB::transaction(function () use ($request, $transaction) {
            try {
                $transactionData = $request->validated();
                logger(['before' => $transaction->toArray(), 'after' => $transactionData]);
                // Update the transaction
                $transaction->update([
                    'type' => $transactionData['type'],
                    'category_id' => $transactionData['category_id'],
                    'description' => $transactionData['description'],
                    'amount' => $transactionData['amount'],
                    'date' => $transactionData['date'] ?? now(),
                ]);
                logger(['new' => $transaction->toArray()]);
                if (!$transaction) {
                    return response()->json(['message' => 'Transaction update failed'], 500);
                }

                return TransactionResource::make($transaction);
            } catch (\Exception $e) {
                Log::error('Transaction update failed: ' . $e->getMessage());

                DB::rollBack();
                return response()->json([
                    'message' => 'Failed to update transaction',
                    'error' => $e->getMessage()
                ], 500);
            }
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        Gate::authorize('delete', $transaction);

        return DB::transaction(function () use ($transaction) {
            try {
                $transaction->delete();

                return response()->json([
                    'message' => 'Transaction deleted successfully'
                ], 200);
            } catch (\Exception $e) {
                Log::error('Transaction deletion failed: ' . $e->getMessage());

                DB::rollBack();
                return response()->json([
                    'message' => 'Failed to delete transaction',
                    'error' => $e->getMessage()
                ], 500);
            }
        });
    }
}
