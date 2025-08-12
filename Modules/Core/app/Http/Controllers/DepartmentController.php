<?php

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Modules\Core\Models\Department;
use Modules\Core\Http\Requests\V1\DepartmentStoreRequest;
use Modules\Core\Http\Requests\V1\DepartmentUpdateRequest;
use Illuminate\Support\Facades\Cache;

class DepartmentController extends Controller
{

    use ResponseTrait;

    public function index()
    {
        $departments = Cache::tags('department')->remember('departments-page-'.request('page', 1) , 60*60 , function(){
            return Department::paginate(PAGINATION);
        });     
        
        return $this->success($departments , 'Departments Fetched Successfully' , Response::HTTP_OK);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(DepartmentStoreRequest $request) 
    {
        try {
            $department = Department::create($request->validated());
            return $this->success($department , 'Department Created Successfully', Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            return $this->error('Some Thing Error' , Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(DepartmentUpdateRequest $request, string $id) 
    {
        try {
            $department = Department::find($id);
            if(!$department) {
                return $this->error('Department Not Found' , Response::HTTP_NOT_FOUND);
            }
            $department->update($request->validated());
            return $this->success($department , 'Department Updated Successfully' , Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->error('Something Error' , Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) 
    {
        try {
            $department = Department::find($id);
            if(!$department) {
                return $this->error('Department Not Found' , Response::HTTP_NOT_FOUND);
            }
            $department->delete();
            return $this->success($department , 'Department Deleted Successfully' , Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->error('Something Error' , Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
