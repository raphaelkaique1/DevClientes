<?php
declare(strict_types=1);
require_once __DIR__ . '/../Database/Connection.php';

class CustomerRepository {
    private PDO $db;
    public function __construct() {
        $this->db = Connection::connect();
    }

    public function all(): array {
        return $this->db->query("SELECT * FROM Customers")
        ->fetchAll(PDO::FETCH_OBJ);
    }

    public function total(): int|false {
        return $this->db->query("SELECT MAX(id) AS max FROM Customers")
        ->fetchColumn();
    }

    public function insert(Customer $customer): bool {
        return $this->db->prepare("INSERT INTO Customers (
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
        ")
        ->execute([
            ':name'   => $customer->name,
            ':email'  => $customer->email,
            ':role'   => $customer->role,
            ':status' => $customer->status
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM Customers WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount() > 0;
    }
}

?>