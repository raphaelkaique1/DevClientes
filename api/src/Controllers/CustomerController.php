<?php

require_once __DIR__ . '/../Http/Request.php';
require_once __DIR__ . '/../Models/Customer.php';
require_once __DIR__ . '/../Repositories/CustomerRepository.php';
class CustomerController {
    public function index(): void {
        echo 'List customers';
    }

    public function store(): void {
        $data = Request::data();
        if (empty($data['name']) || empty($data['email'])) {
            echo 'Preencha todos os campos!';
            exit;
        };
        try {
            $customer = new Customer(
                null,
                $data['name'],
                $data['email'],
                $data['role'],
                (int)$data['status']
            );
            $repository = new CustomerRepository;

            $repository->create($customer);
            echo 'Usuário cadastrado com sucesso!';
        } catch(Throwable $error) {
            echo "Erro ao registrar este usuário!";
        }
    }
}

?>