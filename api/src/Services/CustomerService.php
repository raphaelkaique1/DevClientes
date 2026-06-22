<?php
declare(strict_types=1);
require_once __DIR__ . '/../Models/Customer.php';
require_once __DIR__ . '/../Repositories/CustomerRepository.php';
require_once __DIR__ . '/../Utils/Normalization.php';
require_once __DIR__ . '/../Utils/Validation.php';

class CustomerService {
    public function list(): array {
        return (new CustomerRepository)->all();
    }

    public function create(array $data): bool {
        $customerRepository = new CustomerRepository;
        $customer = new Customer(
            null,
            Validate::name(Normalize::format($data['name'], Type::CASE_TITLE)),
            Validate::email(Normalize::format($data['email'], Type::CASE_EMAIL)),
            Validate::role(Normalize::format($data['role'], Type::CASE_LOWER)),
            isset($data['status']) ? 1 : 0
        );
        return $customerRepository->insert($customer);
    }

    public function exclude(int $id): bool {
        return Validate::id(Normalize::format($id, Type::CASE_NUMBER), (new CustomerRepository())->total()) !== null
        && (new CustomerRepository)->delete($id);
    }
}

?>