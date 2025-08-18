<?php

namespace Modules\Inventory\Http\Controllers\API\V1;

use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Modules\Inventory\Models\Category;
use Symfony\Component\HttpFoundation\Response;
use Modules\Inventory\Http\Requests\V1\CategoryRequest;
use Modules\Inventory\Http\Resources\V1\CategoryResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $categories = Cache::tags('categories')->remember("category-page-" . request('page' , 1),60*60, function () {
                return Category::paginate(PAGINATION)->toResourceCollection(CategoryResource::class);
            });
            return $categories;
        } catch (\Exception $e) {
            return $this->error('Error retrieving categories :'. $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request): JsonResponse
    {
        try {
            $category = Category::create($request->validated());
            return $this->success(
                new CategoryResource($category),
                'Category created successfully',
                Response::HTTP_CREATED
            );
        } catch (\Exception $e) {
            return $this->error('Error creating category', $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category): JsonResponse
    {
        try {
            return $this->success(
                new CategoryResource($category),
                'Category retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->error('Error retrieving category', $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, Category $category): JsonResponse
    {
        try {
            $category->update($request->validated());
            return $this->success(
                new CategoryResource($category),
                'Category updated successfully'
            );
        } catch (\Exception $e) {
            return $this->error('Error updating category', $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): JsonResponse
    {
        try {
            $category->delete();
            return $this->success(
                [],
                'Category deleted successfully',
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return $this->error('Error deleting category', $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
