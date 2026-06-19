<?php
declare(strict_types=1);

class CustomerView {
    public static function list(array $customers): string {
        return array_map((fn(object $customer): string => "
                    <li class='relative bg-gray-100 text-black p-8 my-8 rounded-lg'>
                        <h3 class='text-xl'><strong>$customer->name</strong></h3>
                        <p>Email: $customer->email</p>
                        <p>Cargo: $customer->role</p>
                        <p>Status: ". ($customer->status ?
                            "<span class='bg-emerald-400 text-white font-bold rounded-sm px-1'>Ativo</span>" :
                            "<span class='text-gray-500 rounded-sm px-1'>Inativo</span>")
                        ."</p>
                        <div class='absolute bottom-2 left-8'>
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
                            >
                                Excluir
                            </button>
                        </div>
                    </li>
                "),
                $customers)
        |> (fn(array $list): string => implode('', $list));
    }
}

?>