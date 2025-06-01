<?php

namespace App\Controllers;

use App\Services\Interfaces\ReservationServiceInterface;
use Core\Controller;
use Core\Http\Request;
use Core\Interfaces\ResourceController;

class ReservationController extends Controller implements ResourceController
{
    private ReservationServiceInterface $reservationService;

    public function __construct(ReservationServiceInterface $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    public function index(): void
    {
        try {
            $reservations = $this->reservationService->getReservationsWithDetails();
            $this->json(['success' => true, 'data' => $reservations]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show(int $id): void
    {
        try {
            $reservation = $this->reservationService->getReservationById($id);
            if ($reservation) {
                $this->json(['success' => true, 'data' => $reservation]);
            } else {
                $this->json(['success' => false, 'message' => 'Réservation non trouvée'], 404);
            }
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function store(): void
    {
        try {
            $data = $this->request->all();
            $reservation = $this->reservationService->createReservation($data);
            $this->json(['success' => true, 'data' => $reservation, 'message' => 'Réservation créée avec succès'], 201);
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
            $reservation = $this->reservationService->updateReservation($id, $data);
            if ($reservation) {
                $this->json(['success' => true, 'data' => $reservation, 'message' => 'Réservation mise à jour avec succès']);
            } else {
                $this->json(['success' => false, 'message' => 'Réservation non trouvée'], 404);
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
            $success = $this->reservationService->deleteReservation($id);
            if ($success) {
                $this->json(['success' => true, 'message' => 'Réservation supprimée avec succès']);
            } else {
                $this->json(['success' => false, 'message' => 'Réservation non trouvée'], 404);
            }
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}

