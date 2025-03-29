<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryFormRequest;
use App\Http\Requests\UpdateCategoryFormRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => 'sometimes|string|max:255',
            'type' => 'sometimes|string|in:income,expense,transfer',
        ]);

        $categories = Category::where(function (Builder $query) {
                $query->where('user_id', auth()->user()->id)->orWhere('is_system', 1);
            })
            ->filter($validated)
            ->get();

        return CategoryResource::collection($categories);
    }

    public function store(StoreCategoryFormRequest $request)
    {
        return DB::transaction(function () use ($request) {
            try {
                $categoryData = $request->validated();

                // Create the category
                $category = Category::create([
                    'user_id' => auth()->user()->id,
                    'name' => $categoryData['name'],
                    'type' => $categoryData['type'],
                    'color' => $categoryData['color'],
                ]);

                if (!$category) {
                    return response()->json(['message' => 'Category creation failed'], 500);
                }

                return CategoryResource::make($category);
            } catch (\Exception $e) {
                Log::error('Category creation failed: ' . $e->getMessage());
                DB::rollBack();
                return response()->json([
                    'message' => 'Failed to create category',
                    'error' => $e->getMessage(),
                ], 500);
            }
        });
    }

    public function show(Category $category)
    {
        Gate::authorize('view', $category);

        return CategoryResource::make($category);
    }

    public function update(UpdateCategoryFormRequest $request, Category $category)
    {
        Gate::authorize('update', $category);

        return DB::transaction(function () use ($request, $category) {
            try {
                $categoryData = $request->validated();

                // Update the category
                $category->update([
                    'name' => $categoryData['name'],
                    'type' => $categoryData['type'],
                    'color' => $categoryData['color'],
                ]);

                if (!$category) {
                    return response()->json(['message' => 'Category update failed'], 500);
                }

                return CategoryResource::make($category);
            } catch (\Exception $e) {
                Log::error('Category update failed: ' . $e->getMessage());
                DB::rollBack();
                return response()->json([
                    'message' => 'Failed to update category',
                    'error' => $e->getMessage(),
                ], 500);
            }
        });
    }

    public function destroy(Category $category)
    {
        Gate::authorize('delete', $category);

        return DB::transaction(function () use ($category) {
            try {
                $category->delete();

                return response()->json(['message' => 'Category deleted successfully']);
            } catch (\Exception $e) {
                Log::error('Category deletion failed: ' . $e->getMessage());
                DB::rollBack();
                return response()->json([
                    'message' => 'Failed to delete category',
                    'error' => $e->getMessage(),
                ], 500);
            }
        });
    }
}
