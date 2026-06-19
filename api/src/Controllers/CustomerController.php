<?php
declare(strict_types=1);
require_once __DIR__ . '/../Http/Request.php';
require_once __DIR__ . '/../Http/Response.php';
require_once __DIR__ . '/../Models/Customer.php';
require_once __DIR__ . '/../Views/Customers/List.php';
require_once __DIR__ . '/../Repositories/CustomerRepository.php';
require_once __DIR__ . '/../Services/CustomerService.php';
require_once __DIR__ . '/../Utils/Exception.php';

class CustomerController {
    public function index(): void {
        match($customers = Operation::runSafe(fn() => (new CustomerService())->list()) and true) {
            is_array($customers) && !empty($customers) => (new Response(ContentType::HTML, CustomerView::list($customers), 200))->send(),
            empty($customers)    => (new Response(ContentType::TEXT, 'Nenhum usuário cadastrado...', 204))->send(),
            default              => (new Response(ContentType::TEXT, 'Sem resposta do servidor.', 500))->send()
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
        match($result = Operation::runSafe(fn() => (new CustomerService())->create($data)) and true) {
            $result === true  => (new Response(ContentType::TEXT, 'Usuário criado com sucesso!', 201))->send(),
            $result === false => (new Response(ContentType::TEXT, 'Não foi possível criar o usuário.', 400))->send(),
            str_contains(
                $result,
                Violation::DuplicateEmailException->value
            )       => (new Response(ContentType::TEXT, 'Usuário já possui cadastro!', 409))->send(),
            default => (new Response(ContentType::TEXT, 'Erro ao cadastrar usuário!', 500))->send()
        };
    }
}

?>