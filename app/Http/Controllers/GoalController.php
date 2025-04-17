<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGoalFormRequest;
use App\Http\Requests\UpdateGoalFormRequest;
use App\Http\Resources\GoalResource;
use App\Models\Goal;
use App\Models\GoalProgress;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Exception;

class GoalController extends Controller
{
    public function index()
    {
        $goals = Goal::where('user_id', auth()->id())
            ->with(['category', 'progress'])
            ->get();

        return GoalResource::collection($goals);
    }

    /**
     * Store a new goal
     */
    public function store(StoreGoalFormRequest $request)
    {
        $validated = $request->validated();

        try {
            return DB::transaction(function () use ($validated) {
                $goal = Goal::create([
                    'user_id'       => auth()->id(),
                    'category_id'   => $validated['category_id'],
                    'target_amount' => $validated['target_amount'],
                    'frequency'     => $validated['frequency'],
                    'start_date'    => $validated['start_date'],
                    'end_date'      => $validated['end_date'] ?? null,
                ]);

                // Create initial progress period
                $this->createInitialProgress($goal);

                DB::commit();
                return response()->json($goal, 201);
            });
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to store goal', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Show a specific goal
     */
    public function show(Goal $goal)
    {
        Gate::authorize('view', $goal);

        $goal->load(['category', 'progress']);
    }

    /**
     * Update an existing goal
     */
    public function update(UpdateGoalFormRequest $request, Goal $goal)
    {
        Gate::authorize('update', $goal);

        $validated = $request->validated();
        try {
            return DB::transaction(function () use ($goal, $validated) {

                $goal->update($validated);

                if (isset($validated['frequency']) && $goal->wasChanged('frequency')) {
                    $goal->progress()->delete();

                    $this->createInitialProgress($goal);
                }

                DB::commit();
                return response()->json($goal);
            });
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update goal', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete a goal
     */
    public function destroy(Goal $goal)
    {
        try {
            Gate::authorize('delete', $goal);

            DB::transaction(function () use ($goal) {
                $goal->delete();
                $goal->progress()->delete();
            });
            DB::commit();

            return response()->json(['message' => 'Goal deleted'], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to delete goal', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Create the first GoalProgress record for the new goal
     */
    protected function createInitialProgress(Goal $goal): void
    {
        try {
            $start = now()
                ->{ $goal->frequency === 'weekly' ? 'startOfWeek' : 'startOfMonth' }()
                ->toDateString();
            $end = now()
                ->{ $goal->frequency === 'weekly' ? 'endOfWeek' : 'endOfMonth' }()
                ->toDateString();

            GoalProgress::create([
                'goal_id'        => $goal->id,
                'period_start'   => $start,
                'period_end'     => $end,
                'progress_amount'=> 0,
            ]);
        } catch (Exception $e) {
            throw new Exception('Failed to create initial progress: ' . $e->getMessage());
        }
    }
}
