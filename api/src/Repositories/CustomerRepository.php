<?php
declare(strict_types=1);
require_once __DIR__ . '/../Database/Connection.php';

class CustomerRepository {
    private PDO $db;
    public function __construct() {
        $this->db = Connection::connect();
    }
    public function insert(Customer $customer): bool {
        $stmt = $this->db->prepare("INSERT INTO Customers (
                name,
                email,
                role,
                status
            ) VALUES (
                :name,
                :email,
                :role,
                :status
            )
        ");
        return $stmt->execute([
            ':name'   => $customer->name,
            ':email'  => $customer->email,
            ':role'   => $customer->role,
            ':status' => $customer->status
        ]);
    }
}

?>