<?php
declare(strict_types=1);

class CustomerView {
    public static function success(array $customers): string {
        return array_map((fn(object $customer): string => "
                    <li class='text-black'>
                        <h3 class='text-xl'><strong>$customer->name</strong></h3>
                        <p>Email: $customer->email</p>
                    </li>
                "),
                $customers)
        |> (fn(array $list): string => implode('', $list));
    }

    public static function none(string $alert = 'Nenhum usuário cadastrado...'): string {
        return "<li class='italic text-stone-900'>{$alert}</li>";
    }

    public static function error(string $alert = 'Sem resposta do servidor.'): string {
        return "<li class='bold text-red-300'>{$alert}</li>";
    }
}

?>