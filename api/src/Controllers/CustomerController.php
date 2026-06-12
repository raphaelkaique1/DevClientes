<?php
declare(strict_types=1);
require_once __DIR__ . '/../Http/Request.php';
require_once __DIR__ . '/../Http/Response.php';
require_once __DIR__ . '/../Models/Customer.php';
require_once __DIR__ . '/../Repositories/CustomerRepository.php';
require_once __DIR__ . '/../Services/CustomerService.php';
require_once __DIR__ . '/../Utils/Exception.php';

class CustomerController {
    public function index(): void {
        echo 'List customers';
    }

    public function store(): void {
        $data = Request::data();
        if(!$data) {
            (new Response(ContentType::TEXT, 'Formato da requisição inválido.', 415))->send();
            return;
        }
        foreach($data as $key => $value) {
            if($key === 'status') continue;
            if(empty($value)) {
                (new Response(ContentType::TEXT, 'Preencha todos os campos obrigatórios!', 406))->send();
                return;
            };
        }
        match(Operation::runSafe(fn() => (new CustomerService())->create($data))) {
            true    => (new Response(ContentType::TEXT, 'Usuário criado com sucesso!', 201))->send(),
            false   => (new Response(ContentType::TEXT, 'Não foi possível criar o usuário.', 400))->send(),
            default => (new Response(ContentType::TEXT, 'Erro ao cadastrar usuário!', 500))->send()
        };
    }
}

?>