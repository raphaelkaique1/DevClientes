<?php
declare(strict_types=1);

class Customer {
    public function __construct(
        public ?int $id,
        public string $name,
        public string $email,
        public string $role,
        public int $status
    ) {}
}
?>