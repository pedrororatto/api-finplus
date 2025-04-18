<?php

namespace App\Observers;

use App\Models\Goal;
use App\Models\GoalProgress;
use App\Models\Transaction;
use App\Notifications\GoalThresholdNotification;
use Carbon\Carbon;

class TransactionObserver
{

    /**
     * Handle the Transaction "created" event.
     */
    public function created(Transaction $transaction)
    {
        $this->processGoalProgress($transaction);
    }

    /**
     * Handle the Transaction "updated" event.
     */
    public function updated(Transaction $transaction)
    {
        $this->processGoalProgress($transaction);
    }

    /**
     * Handle the Transaction "deleted" event.
     */
    public function deleted(Transaction $transaction)
    {
        $this->processGoalProgress($transaction);
    }

    /**
     * Process goals and update progress based on the transaction.
     */
    protected function processGoalProgress(Transaction $transaction): void
    {
        // Only expenses impact spending goals
        if ($transaction->type !== 'expense') {
            return;
        }

        // Fetch goals active for this user and category
        $goals = Goal::where('user_id', $transaction->user_id)
            ->where('category_id', $transaction->category_id)
            ->whereDate('start_date', '<=', $transaction->date)
            ->where(function ($query) use ($transaction) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', $transaction->date);
            })
            ->get();

        foreach ($goals as $goal) {
            // Determine period window
            [$start, $end] = $this->getPeriodRange($goal->frequency, $transaction->date);

            // Get or create a progress record
            $progress = GoalProgress::firstOrNew([
                'goal_id'     => $goal->id,
                'period_start'=> $start,
                'period_end'  => $end,
            ]);

            // Recalculate total for period
            $total = Transaction::where('user_id', $transaction->user_id)
                ->where('category_id', $goal->category_id)
                ->whereBetween('date', [$start, $end])
                ->sum('amount');

            $progress->progress_amount = $total;
            $progress->save();

            // Notify at threshold (e.g., 50%)
            $threshold = $goal->target_amount * 0.5;
            if ($total >= $threshold && ! $progress->notified_threshold) {
                // Send notification
                $transaction->user->notify(new GoalThresholdNotification($goal, $progress));

                // Mark as notified
                $progress->notified_threshold = true;
                $progress->save();
            }

            // Notify at threshold (e.g., 30%)
            $threshold = $goal->target_amount * 0.3;
            if ($total >= $threshold && ! $progress->notified_threshold) {
                // Send notification
                $transaction->user->notify(new GoalThresholdNotification($goal, $progress));

                // Mark as notified
                $progress->notified_threshold = true;
                $progress->save();
            }

            // Notify at threshold (e.g., 70%)
            $threshold = $goal->target_amount * 0.7;
            if ($total >= $threshold && ! $progress->notified_threshold) {
                // Send notification
                $transaction->user->notify(new GoalThresholdNotification($goal, $progress));

                // Mark as notified
                $progress->notified_threshold = true;
                $progress->save();
            }

            // Notify at threshold (e.g., 90%)
            $threshold = $goal->target_amount * 0.9;
            if ($total >= $threshold && ! $progress->notified_threshold) {
                // Send notification
                $transaction->user->notify(new GoalThresholdNotification($goal, $progress));

                // Mark as notified
                $progress->notified_threshold = true;
                $progress->save();
            }

            // Notify at threshold (e.g., 100%)
            $threshold = $goal->target_amount;
            if ($total >= $threshold && ! $progress->notified_threshold) {
                // Send notification
                $transaction->user->notify(new GoalThresholdNotification($goal, $progress));

                // Mark as notified
                $progress->notified_threshold = true;
                $progress->save();
            }
        }
    }

    /**
     * Calculate period start and end based on frequency.
     *
     * @param  string  $frequency  'weekly'|'monthly'
     * @param  \DateTime|string  $date
     * @return array [period_start, period_end]
     */
    public function getPeriodRange(string $frequency, $date): array
    {
        $carbon = $date instanceof Carbon ? $date : Carbon::parse($date);

        if ($frequency === 'weekly') {
            $start = $carbon->startOfWeek()->toDateString();
            $end   = $carbon->endOfWeek()->toDateString();
        } else {
            // default monthly
            $start = $carbon->startOfMonth()->toDateString();
            $end   = $carbon->endOfMonth()->toDateString();
        }

        return [$start, $end];
    }
}
