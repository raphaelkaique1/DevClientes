<?php
require_once __DIR__ . '/../Models/Customer.php';
require_once __DIR__ . '/../Repositories/CustomerRepository.php';

class CustomerService {
    public function create(array $data): bool {
        $customerRepository = new CustomerRepository;
        $customer = new Customer(
            null,
            $data['name'],
            $data['email'],
            $data['role'],
            (int)$data['status']
        );
        return $customerRepository->insert($customer);
    }
}

?>