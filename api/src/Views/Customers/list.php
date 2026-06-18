<?php
declare(strict_types=1);

class CustomerView {
    public static function list(array $customers): string {
        return array_map((fn(object $customer): string => "
                    <li class='text-black'>
                        <h3 class='text-xl'><strong>$customer->name</strong></h3>
                        <p>Email: $customer->email</p>
                    </li>
                "),
                $customers)
        |> (fn(array $list): string => implode('', $list));
    }
}

?>