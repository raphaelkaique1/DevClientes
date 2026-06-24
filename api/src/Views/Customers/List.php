<?php
declare(strict_types=1);

enum Status {
    case SUCCESS;
    case EMPTY;
    case ERROR;
}

class CustomerView {
    public static function list(Status $type, array $customers = []): string {
        return match($type) {
            Status::SUCCESS => array_map((fn(object $customer): string => "
                                <li class='relative bg-gray-100 text-black p-8 my-8 rounded-lg'>
                                    <h3 class='text-xl'><strong>$customer->name</strong></h3>
                                    <p>Email: $customer->email</p>
                                    <p>Cargo: $customer->role</p>
                                    <p>Status: ". ($customer->status ?
                                        "<span class='bg-emerald-400 text-white font-bold rounded-sm px-1'>Ativo</span>" :
                                        "<span class='text-gray-500 rounded-sm px-1'>Inativo</span>")
                                    ."</p>
                                    <div class='absolute top-8 right-4'>
                                        <button
                                            type='button'
                                            class='
                                                cursor-pointer
                                                bg-blue-500
                                                hover:bg-blue-600
                                                text-white
                                                font-bold
                                                rounded-sm
                                                px-1
                                            '
                                        >
                                            Editar
                                        </button>
                                        <button
                                            type='button'
                                            class='
                                                cursor-pointer
                                                bg-red-700
                                                hover:bg-red-800
                                                text-white
                                                font-bold
                                                rounded-sm
                                                px-1
                                            '
                                            hx-delete='/api/customers/$customer->id'
                                            hx-disabled-elt='this'
                                            hx-target='#toast-notification'
                                        >
                                            Excluir
                                        </button>
                                    </div>
                                </li>
                            "),
                            $customers)
                    |> (fn(array $list): string => implode('', $list)),
            Status::EMPTY => '<li class="text-zinc-500">Nenhum usuário cadastrado...</li>',
            Status::ERROR => '<li class="bold text-red-300">Sem resposta do servidor.</li>'
        };
    }
}

?>