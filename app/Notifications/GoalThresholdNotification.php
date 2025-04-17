<?php

namespace App\Notifications;

use App\Models\Goal;
use App\Models\GoalProgress;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Contracts\Queue\ShouldQueue;

class GoalThresholdNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The goal instance.
     *
     * @var \App\Models\Goal
     */
    protected Goal $goal;

    /**
     * The progress instance.
     *
     * @var \App\Models\GoalProgress
     */
    protected GoalProgress $progress;

    /**
     * Create a new notification instance.
     *
     * @param  Goal  $goal
     * @param  GoalProgress  $progress
     * @return void
     */
    public function __construct(Goal $goal, GoalProgress $progress)
    {
        $this->goal = $goal;
        $this->progress = $progress;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification for database storage.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase(mixed $notifiable): array
    {
        $percentage = $this->calculatePercentage();

        return [
            'goal_id'       => $this->goal->id,
            'category_id'   => $this->goal->category_id,
            'target_amount' => (float) $this->goal->target_amount,
            'current_amount'=> (float) $this->progress->progress_amount,
            'percentage'    => $percentage,
            'message'       => "Você atingiu {$percentage}% da sua meta.",
        ];
    }

    /**
     * Get the representation for broadcasting.
     *
     * @param  mixed  $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast(mixed $notifiable): BroadcastMessage
    {
        $data = $this->toDatabase($notifiable);

        return new BroadcastMessage($data);
    }

    /**
     * Calculate progress percentage.
     *
     * @return int
     */
    protected function calculatePercentage(): int
    {
        if ($this->goal->target_amount == 0) {
            return 0;
        }

        return (int) floor(
            ($this->progress->progress_amount / $this->goal->target_amount) * 100
        );
    }
}
