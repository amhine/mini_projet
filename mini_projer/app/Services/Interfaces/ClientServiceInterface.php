<?php

namespace App\Services\Interfaces;

use App\Models\Client;

interface ClientServiceInterface
{
    public function getAllClients(): array;
    public function getClientById(int $id): ?Client;
    public function createClient(array $data): Client;
    public function updateClient(int $id, array $data): ?Client;
    public function deleteClient(int $id): bool;
    public function findClientByEmail(string $email): ?Client;
}