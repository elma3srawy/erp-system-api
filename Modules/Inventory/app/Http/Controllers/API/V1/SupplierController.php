<?php

namespace Modules\Inventory\Http\Controllers\API\V1;

use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Modules\Inventory\Models\Supplier;
use Symfony\Component\HttpFoundation\Response;
use Modules\Inventory\Http\Resources\V1\SupplierResource;
use Modules\Inventory\Http\Requests\V1\StoreSupplierRequest;
use Modules\Inventory\Http\Requests\V1\UpdateSupplierRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SupplierController extends Controller
{
    use ResponseTrait;
    public function index(): AnonymousResourceCollection
    {
        $suppliers = Supplier::query()
            ->latest()
            ->paginate(10);

        return $suppliers->toResourceCollection(SupplierResource::class);
    }

    public function store(StoreSupplierRequest $request): JsonResponse
    {
        $supplier = Supplier::create($request->validated());

        return $this->success(
            $supplier->toResource(SupplierResource::class) , 
            'Supplier created successfully'
        , Response::HTTP_CREATED);
    }

    public function show(Supplier $supplier): JsonResponse
    {
        return $this->success(
            $supplier->toResource(SupplierResource::class) , 
            'Supplier retrieved successfully'
        , Response::HTTP_OK);
    }

    public function update(UpdateSupplierRequest $request, Supplier $supplier): JsonResponse
    {
        $supplier->update($request->validated());

        return $this->success(
            $supplier->toResource(SupplierResource::class) , 
            'Supplier updated successfully'
        , Response::HTTP_OK);
    }

    public function destroy(Supplier $supplier): JsonResponse
    {
        $supplier->delete();

        return $this->success(
            [] ,
            'Supplier deleted successfully', 
            Response::HTTP_OK);
    }
}
