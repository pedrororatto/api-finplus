<?php

namespace App\Console\Commands;

use App\Observers\TransactionObserver;
use Illuminate\Console\Command;
use App\Models\Goal;
use App\Models\GoalProgress;
use Carbon\Carbon;
use Symfony\Component\Console\Command\Command as CommandAlias;

class GenerateGoalProgress extends Command
{
    protected $signature = 'goals:generate-progress';
    protected $description = 'Gera GoalProgress automaticamente para metas com frequência semanal ou mensal';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();

        $goals = Goal::where(function ($query) use ($today) {
            $query->whereNull('end_date')
                ->orWhere('end_date', '>=', $today);
        })->get();

        foreach ($goals as $goal) {
            [$start, $end] = (new TransactionObserver())
                ->getPeriodRange($goal->frequency, $today);

            GoalProgress::firstOrCreate([
                'goal_id' => $goal->id,
                'period_start' => $start,
                'period_end' => $end,
            ], [
                'progress_amount' => 0,
                'notified_threshold' => false,
            ]);

            $this->info("GoalProgress criado para a Goal {$goal->id} no período {$start} até {$end}.");
        }

        return CommandAlias::SUCCESS;
    }
}
