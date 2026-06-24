<?php
declare(strict_types=1);
require_once __DIR__ . '/../Http/Request.php';
require_once __DIR__ . '/../Http/Response.php';
require_once __DIR__ . '/../Services/CustomerService.php';
require_once __DIR__ . '/../Models/Customer.php';
require_once __DIR__ . '/../Repositories/CustomerRepository.php';
require_once __DIR__ . '/../Views/Customers/List.php';
require_once __DIR__ . '/../Views/Alerts/Notifications.php';
require_once __DIR__ . '/../Utils/Exception.php';

class CustomerController {
    public function index(): void {
        match($customers = Operation::runSafe(fn() => (new CustomerService())->list()) and is_array($customers) && !empty($customers)) {
            true    => (new Response(ContentType::HTML, CustomerView::list(Status::SUCCESS, $customers), 200))->send(),
            false   => (new Response(ContentType::HTML, CustomerView::list(Status::EMPTY), 200))->send(),
            default => (new Response(ContentType::HTML, CustomerView::list(Status::ERROR), 500))->send()
        };
    }

    public function store(): void {
        if(!$data = Request::data()) {
            (new Response(ContentType::HTML, AlertsView::notification('Formato da requisição inválido.', Category::POPUP_ERROR), 415))->send();
            return;
        }
        if(array_any($data, function(mixed $value, string|int $key): bool {
            if($key === 'status') return false;
            return empty($value);
        })) {
            (new Response(ContentType::HTML, AlertsView::notification('Preencha todos os campos obrigatórios!', Category::POPUP_ERROR), 406))->send();
            return;
        }
        match($result = Operation::runSafe(fn() => (new CustomerService())->create($data)) and true) {
            $result === true  => (new Response(ContentType::HTML, AlertsView::notification('Usuário criado com sucesso!', Category::POPUP_SUCCESS), 201))->send(),
            $result === false => (new Response(ContentType::HTML, AlertsView::notification('Não foi possível criar o usuário.', Category::POPUP_ERROR), 400))->send(),
            str_contains(
                $result,
                Violation::DuplicateEmailException->value
            )       => (new Response(ContentType::HTML, AlertsView::notification('Usuário já possui cadastro!', Category::POPUP_ERROR), 409))->send(),
            default => (new Response(ContentType::HTML, AlertsView::notification('Erro ao cadastrar usuário!', Category::POPUP_ERROR), 500))->send()
        };
    }

    public function remove(array $params): void {
        match (Operation::runSafe(fn() => (new CustomerService())->exclude((int) $params['id']))) {
            true    => (new Response(ContentType::HTML, AlertsView::notification('Usuário deletado com sucesso!', Category::POPUP_SUCCESS), 200))->send(),
            false   => (new Response(ContentType::TEXT, AlertsView::notification('Usuário não encontrado.', Category::POPUP_ERROR), 404))->send(),
            default => (new Response(ContentType::TEXT, AlertsView::notification('Erro ao excluir.', Category::POPUP_ERROR), 500))->send(),
        };
    }
}

?>