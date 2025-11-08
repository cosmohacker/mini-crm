<?php

namespace App\Services;

use App\Models\Customer;
use App\Repositories\CustomerRepository;

class CustomerService
{
    public function __construct(private CustomerRepository $customerRepository) {}

    // Find or create a customer
    public function findOrCreate(array $data): Customer
    {
        $customer = $this->customerRepository->findByEmail($data['email']);

        if ($customer) {
            $this->customerRepository->update($customer, [
                'name'  => $data['name'],
                'phone' => $data['phone'] ?? $customer->phone,
            ]);

            return $customer->fresh();
        }

        return $this->customerRepository->create($data);
    }

    // Get customers
    public function getCustomers(?string $email = null)
    {
        return $this->customerRepository->getAll($email);
    }
}
