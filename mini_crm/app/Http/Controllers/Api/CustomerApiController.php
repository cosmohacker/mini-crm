<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Services\CustomerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerApiController extends Controller
{
    public function __construct(private CustomerService $customerService) {}

    // Display a listing of customers by email
    public function index(Request $request): JsonResponse
    {
        $customers = $this->customerService->getCustomers($request->email);

        return CustomerResource::collection($customers)->response();
    }

    // Store a newly created customer
    public function store(StoreCustomerRequest $request): JsonResponse
    {
        $customer = $this->customerService->findOrCreate($request->validated());

        return (new CustomerResource($customer))->response()->setStatusCode($customer->wasRecentlyCreated ? 201 : 200);
    }
}
