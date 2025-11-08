<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;

use App\Models\Customer;

class CustomerRepository
{
    // Find Customer By Email
    public function findByEmail(string $email): ?Customer
    {
        return Customer::where('email', $email)->first();
    }

    // Create New Customer
    public function create(array $data): Customer
    {
        return Customer::create($data);
    }

    // Update Customer Information
    public function update(Customer $customer, array $data): bool
    {
        return $customer->update($data);
    }

    // Get All Customers
    public function getAll(?string $email = null): Collection
    {
        $query = Customer::query();

        if ($email) {
            $query->where('email', $email);
        }

        return $query->get();
    }
}
