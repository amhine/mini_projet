<?php

namespace App\Controllers;

use App\Services\Interfaces\ChambreServiceInterface;
use Core\Controller;
use Core\Http\Request;
use Core\Interfaces\ResourceController;
use DateTime;

class ChambreController extends Controller implements ResourceController
{
    private ChambreServiceInterface $chambreService;

    public function __construct(ChambreServiceInterface $chambreService)
    {
        $this->chambreService = $chambreService;
    }

    public function index(): void
    {
        try {
            $chambres = $this->chambreService->getAllChambres();
            $this->json(['success' => true, 'data' => $chambres]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show(int $id): void
    {
        try {
            $chambre = $this->chambreService->getChambreById($id);
            if ($chambre) {
                $this->json(['success' => true, 'data' => $chambre]);
            } else {
                $this->json(['success' => false, 'message' => 'Chambre non trouvée'], 404);
            }
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function store(): void
    {
        try {
            $data = $this->request->all();
            $chambre = $this->chambreService->createChambre($data);
            $this->json(['success' => true, 'data' => $chambre, 'message' => 'Chambre créée avec succès'], 201);
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
            $chambre = $this->chambreService->updateChambre($id, $data);
            if ($chambre) {
                $this->json(['success' => true, 'data' => $chambre, 'message' => 'Chambre mise à jour avec succès']);
            } else {
                $this->json(['success' => false, 'message' => 'Chambre non trouvée'], 404);
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
            $success = $this->chambreService->deleteChambre($id);
            if ($success) {
                $this->json(['success' => true, 'message' => 'Chambre supprimée avec succès']);
            } else {
                $this->json(['success' => false, 'message' => 'Chambre non trouvée'], 404);
            }
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function available(): void
    {
        try {
            $dateArrivee = $this->request->input('dateArrivee');
            $dateDepart = $this->request->input('dateDepart');

            if (empty($dateArrivee) || empty($dateDepart)) {
                $this->json(['success' => false, 'message' => 'Les dates d\'arrivée et de départ sont requises'], 400);
                return;
            }

            $dateArriveeObj = new DateTime($dateArrivee);
            $dateDepartObj = new DateTime($dateDepart);

            $chambres = $this->chambreService->getAvailableChambres($dateArriveeObj, $dateDepartObj);
            $this->json(['success' => true, 'data' => $chambres]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}

