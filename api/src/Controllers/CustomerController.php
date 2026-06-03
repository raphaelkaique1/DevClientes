<?php

require_once __DIR__ . '/../Http/Request.php';
require_once __DIR__ . '/../Models/Customer.php';
require_once __DIR__ . '/../Repositories/CustomerRepository.php';
require_once __DIR__ . '/../Services/CustomerService.php';
require_once __DIR__ . '/../Utils/Response.php';
require_once __DIR__ . '/../Utils/Exception.php';

class CustomerController {
    public function index(): void {
        echo 'List customers';
    }

    public function store(): void {
        foreach(Request::data() as $key => $value) {
            if($key === 'status') continue;
            if(empty($value)) {
                Response::send(new Response('Preencha todos os campos obrigatórios!', 406));
                return;
            };
        }

        Response::send(match(Operation::try(fn() => new CustomerService()->create(Request::data()))) {
            true    => new Response('Usuário criado com sucesso!', 201),
            false   => new Response('Não foi possível criar o usuário.', 400),
            default => new Response('Erro ao cadastrar usuário!', 500)
        });
    }
}

?>