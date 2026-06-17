<?php
declare(strict_types=1);
require_once __DIR__ . '/../Models/Customer.php';
require_once __DIR__ . '/../Repositories/CustomerRepository.php';
require_once __DIR__ . '/../Utils/Normalization.php';
require_once __DIR__ . '/../Utils/Validation.php';

class CustomerService {
    public function list(): array {
        $customers = (new CustomerRepository)->all();
        if(!empty($customers)) {
            $list = '';
            $status = true;
        } else {
            $list = '<li>Houve um problema ao solicitar a lista de usuários.</li>';
            $status = false;
        }
        foreach($customers as $customer) {
            $list .= "$customer->name";
        }
        return [$status, $list];
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
}

?>