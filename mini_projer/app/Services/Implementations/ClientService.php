<?php

namespace App\Services\Implementations;

use App\Models\Client;
use App\Repositories\ClientRepository;
use App\Services\Interfaces\ClientServiceInterface;

class ClientService implements ClientServiceInterface
{
    private ClientRepository $clientRepository;

    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    public function getAllClients(): array
    {
        return $this->clientRepository->findAll();
    }

    public function getClientById(int $id): ?Client
    {
        return $this->clientRepository->findById($id);
    }

    public function createClient(array $data): Client
    {
        $this->validateClientData($data);
        
        $clientData = [
            'nom' => $data['nom'],
            'email' => $data['email'],
            'phone' => $data['phone']
        ];

        $id = $this->clientRepository->save($clientData);
        return new Client($id, $data['nom'], $data['email'], $data['phone']);
    }

    public function updateClient(int $id, array $data): ?Client
    {
        $client = $this->clientRepository->findById($id);
        if (!$client) {
            return null;
        }

        $this->validateClientData($data);

        $updateData = [
            'nom' => $data['nom'],
            'email' => $data['email'],
            'phone' => $data['phone']
        ];

        $success = $this->clientRepository->update($updateData, ['id' => $id]);
        
        if ($success) {
            return new Client($id, $data['nom'], $data['email'], $data['phone']);
        }

        return null;
    }

    public function deleteClient(int $id): bool
    {
        return $this->clientRepository->delete(['id' => $id]);
    }

    public function findClientByEmail(string $email): ?Client
    {
        return $this->clientRepository->findByEmail($email);
    }

    private function validateClientData(array $data): void
    {
        if (empty($data['nom'])) {
            throw new \InvalidArgumentException("Le nom du client est requis");
        }

        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Un email valide est requis");
        }

        if (empty($data['phone'])) {
            throw new \InvalidArgumentException("Le téléphone du client est requis");
        }
    }
}

