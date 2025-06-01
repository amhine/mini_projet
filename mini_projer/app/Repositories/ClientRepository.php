<?php
namespace App\Repositories;

use App\Models\Client;
use Core\Repository\RepositoryMutations;

class ClientRepository extends RepositoryMutations
{
    public function __construct()
    {
        parent::__construct('clients');
    }

    protected function mapper(array $data): object
    {
        return new Client(
            $data['id'] ?? null,
            $data['nom'],
            $data['email'],
            $data['phone']
        );
    }

    public function findByEmail(string $email): ?Client
    {
        $query = "SELECT * FROM {$this->tableName} WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        $result = $stmt->fetch();
        return $result ? $this->mapper($result) : null;
    }

    public function findAll(): array
    {
        $query = "SELECT * FROM {$this->tableName} ORDER BY nom";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        $results = $stmt->fetchAll();
        return array_map([$this, 'mapper'], $results);
    }

    public function findById(int $id): ?Client
    {
        $query = "SELECT * FROM {$this->tableName} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $result = $stmt->fetch();
        return $result ? $this->mapper($result) : null;
    }
}

