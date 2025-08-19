<?php

namespace Modules\Inventory\Traits\V1\ControllerMethods;

use App\Traits\ResponseTrait;
use Modules\Inventory\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

trait CategoryMethods
{
    use ResponseTrait;

    public function sections(Request $request)
    {
        try {
            $categoriesWithSections = Cache::tags('categories-with-sections')
            ->remember('categories-with-sections-page-'.$request->integer('page', 1), 60*60 , function(){
                return Category::with('sections')->paginate(PAGINATION);
            });
            return $this->success($categoriesWithSections, 'Sections retrieved successfully');
        } catch (\Throwable $th) {
            return $this->error('Error retrieving categories with sections: ' . $th->getMessage());
        }
    }
}