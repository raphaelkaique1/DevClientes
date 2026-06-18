<?php
declare(strict_types=1);
require_once __DIR__ . '/../Http/Request.php';
require_once __DIR__ . '/../Http/Response.php';
require_once __DIR__ . '/../Models/Customer.php';
require_once __DIR__ . '/../Views/Customers/list.php';
require_once __DIR__ . '/../Views/Customers/messages.php';
require_once __DIR__ . '/../Repositories/CustomerRepository.php';
require_once __DIR__ . '/../Services/CustomerService.php';
require_once __DIR__ . '/../Utils/Exception.php';

class CustomerController {
    public function index(): void {
        match($customers = Operation::runSafe(fn() => (new CustomerService())->list()) and true) {
            empty($customers)    => (new Response(ContentType::HTML, CustomerView::none('Nenhum usuário cadastrado...'), 204))->send(),
            is_array($customers) => (new Response(ContentType::HTML, CustomerView::success($customers), 200))->send(),
            default              => (new Response(ContentType::HTML, CustomerView::error('Sem resposta do servidor.'), 500))->send()
        };
    }

    public function store(): void {
        if(!$data = Request::data()) {
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
            true    => (new Response(ContentType::TEXT, Warning::success('Usuário criado com sucesso!'), 201))->send(),
            false   => (new Response(ContentType::TEXT, Warning::alert('Não foi possível criar o usuário.'), 400))->send(),
            default => (new Response(ContentType::TEXT, Warning::error('Erro ao cadastrar usuário!'), 500))->send()
        };
    }
}

?>