<?php

namespace Modules\CRM\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Traits\ResponseTrait;
use Modules\CRM\Models\Customer;
use Modules\CRM\Http\Resources\V1\CustomerResource;
use Modules\CRM\Http\Requests\V1\StoreCustomerRequest;
use Modules\CRM\Http\Requests\V1\UpdateCustomerRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;

class CustomerController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the customers.
     */
    public function index()
    {
        $customers = Cache::tags('customer')->remember('customers-page-'.request('page' , 1), 60*60, function ()  {
            return  Customer::paginate(PAGINATION)->toResourceCollection(CustomerResource::class)->response();
        });
        return $customers;
    }

    /**
     * Store a newly created customer in storage.
     */
    public function store(StoreCustomerRequest $request): JsonResponse
    {
        $customer = Customer::create($request->validated());
     
        return $this->success([
            'message' => 'Customer created successfully',
            'customer' => $customer->toResource(CustomerResource::class)
        ], status:Response::HTTP_CREATED);
    }

    /**
     * Display the specified customer.
     */
    public function show(Customer $customer): JsonResponse
    {
        return $this->success(
            $customer->toResource(CustomerResource::class),
            'Customer retrieved successfully'
        );
    }

    /**
     * Update the specified customer in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer): JsonResponse
    {
        $customer->update($request->validated());
        return $this->success([
            'message' => 'Customer updated successfully',
            'data' => $customer->toResource(CustomerResource::class),
        ]);
    }

    /**
     * Remove the specified customer from storage.
     */
    public function destroy(Customer $customer): JsonResponse
    {
        $customer->delete();
        return $this->success([], 'Customer deleted successfully');
    }
}
