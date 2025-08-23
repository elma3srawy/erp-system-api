<?php

namespace Modules\Inventory\Http\Controllers\API\V1;

use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Http\Requests\V1\StoreProductRequest;
use Modules\Inventory\Http\Requests\V1\UpdateProductRequest;
use Modules\Inventory\Traits\V1\ControllerMethods\ProductMethods;
use Modules\Inventory\Http\Resources\V1\ProductResource;

class ProductController extends Controller
{
    use ResponseTrait,
        ProductMethods;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $products = Cache::tags('products')->remember("product-page-" . request('page' , 1),60*60, function () {
                return Product::with('section' , 'suppliers')->paginate(PAGINATION)
                ->toResourceCollection(ProductResource::class)->resource;
            });
            return $this->success($products, 'Products retrieved successfully');
        } catch (\Exception $e) {
            return $this->error('Error retrieving products: '.$e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $product = Product::create($request->validated());
            $product->suppliers()->sync([$request->validated('supplier_id') => ['price' => $request->validated('price')]]);
            $product->load('section','suppliers');
            DB::commit();
            return $this->created(
                $product->toResource(ProductResource::class),
                'Product created successfully',
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Error creating product: '.$e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): JsonResponse
    {
        try {
            return $this->success(
                new ProductResource($product),
                'Product retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->error('Error retrieving product: '.$e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        try {
            $product->update($request->validated());
            
            return $this->updated(
                new ProductResource($product),
                'Product updated successfully'
            );
        } catch (\Exception $e) {
            return $this->error('Error updating product: '.$e->getMessage());
        }       
    }
    public function destroy(Product $product): JsonResponse
    {
        try {
            DB::beginTransaction();
            $product->delete();
            $product->suppliers()->detach();
            DB::commit();
            return $this->deleted(
                'Product deleted successfully',
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Error deleting product: '.$e->getMessage());
        }
    }
}
