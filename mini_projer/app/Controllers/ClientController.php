<?php

namespace App\Controllers;

use App\Services\Interfaces\ClientServiceInterface;
use Core\Controller;
use Core\Http\Request;
use Core\Interfaces\ResourceController;

class ClientController extends Controller implements ResourceController
{
    private ClientServiceInterface $clientService;

    public function __construct(ClientServiceInterface $clientService)
    {
        $this->clientService = $clientService;
    }

    public function index(): void
    {
        try {
            $clients = $this->clientService->getAllClients();
            $this->json(['success' => true, 'data' => $clients]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show(int $id): void
    {
        try {
            $client = $this->clientService->getClientById($id);
            if ($client) {
                $this->json(['success' => true, 'data' => $client]);
            } else {
                $this->json(['success' => false, 'message' => 'Client non trouvé'], 404);
            }
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function store(): void
    {
        try {
            $data = $this->request->all();
            $client = $this->clientService->createClient($data);
            $this->json(['success' => true, 'data' => $client, 'message' => 'Client créé avec succès'], 201);
        } catch (\InvalidArgumentException $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(int $id): void
    {
        try {
            $data = $this->request->all();
            $client = $this->clientService->updateClient($id, $data);
            if ($client) {
                $this->json(['success' => true, 'data' => $client, 'message' => 'Client mis à jour avec succès']);
            } else {
                $this->json(['success' => false, 'message' => 'Client non trouvé'], 404);
            }
        } catch (\InvalidArgumentException $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy(int $id): void
    {
        try {
            $success = $this->clientService->deleteClient($id);
            if ($success) {
                $this->json(['success' => true, 'message' => 'Client supprimé avec succès']);
            } else {
                $this->json(['success' => false, 'message' => 'Client non trouvé'], 404);
            }
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}