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
        $users = Operation::runSafe(fn() => (new CustomerService())->list());
        match($users[0]) {
            true    => (new Response(ContentType::HTML, $users[1], 200))->send(),
            false   => (new Response(ContentType::HTML, '<li class="italic text-stone-900">Nenhum usuário cadastrado...</li>', 204))->send(),
            default => (new Response(ContentType::HTML, '<li class="bold text-red-300">Sem resposta do servidor.</li>', 500))->send()
        };
    }

    public function store(): void {
        $data = Request::data();
        if(!$data) {
            (new Response(ContentType::TEXT, 'Formato da requisição inválido.', 415))->send();
            return;
        }
        if(array_any($data, function(mixed $value, string|int $key): bool {
            if($key === 'status') return false;
            return empty($value);
        })) {
            (new Response(ContentType::TEXT, 'Preencha todos os campos obrigatórios!', 406))->send();
            return;
        }
        match(Operation::runSafe(fn() => (new CustomerService())->create($data))) {
            true    => (new Response(ContentType::TEXT, 'Usuário criado com sucesso!', 201))->send(),
            false   => (new Response(ContentType::TEXT, 'Não foi possível criar o usuário.', 400))->send(),
            default => (new Response(ContentType::TEXT, 'Erro ao cadastrar usuário!', 500))->send()
        };
    }
}

?>