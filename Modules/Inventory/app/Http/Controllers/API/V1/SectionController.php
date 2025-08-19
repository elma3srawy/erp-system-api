<?php

namespace Modules\Inventory\Http\Controllers\API\V1;

use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Modules\Inventory\Models\Section;
use Modules\Inventory\Http\Requests\V1\SectionRequest;
use Modules\Inventory\Http\Resources\V1\SectionResource;

class SectionController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $sections = Cache::tags('sections')->remember("section-page-" . request('page' , 1),60*60, function () {
                return Section::paginate(PAGINATION)->toResourceCollection(SectionResource::class)->resource;
            });
            return $this->success($sections, 'Sections retrieved successfully');
        } catch (\Exception $e) {
            return $this->error('Error retrieving sections :'. $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SectionRequest $request): JsonResponse
    {
        try {
            $section = Section::create($request->validated());
            return $this->created(
                new SectionResource($section),
                'Section created successfully',
            );
        } catch (\Exception $e) {
            return $this->error('Error creating section :'. $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Section $section): JsonResponse
    {
        try {
            return $this->success(
                new SectionResource($section),
                'Section retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->error('Error retrieving section', $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SectionRequest $request, Section $section): JsonResponse
    {
        try {
            $section->update($request->validated());
            return $this->updated(
                new SectionResource($section),
                'Section updated successfully'
            );
        } catch (\Exception $e) {
            return $this->error('Error updating section', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Section $section): JsonResponse
    {
        try {
            $section->delete();
            return $this->deleted(
                'Section deleted successfully',
            );
        } catch (\Exception $e) {
            return $this->error('Error deleting section', $e->getMessage());
        }
    }
}
