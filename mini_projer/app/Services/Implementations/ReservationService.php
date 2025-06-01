<?php

namespace App\Services\Implementations;

use App\Models\Reservation;
use App\Repositories\ReservationRepository;
use App\Repositories\ChambreRepository;
use App\Services\Interfaces\ReservationServiceInterface;
use DateTime;

class ReservationService implements ReservationServiceInterface
{
    private ReservationRepository $reservationRepository;
    private ChambreRepository $chambreRepository;

    public function __construct(ReservationRepository $reservationRepository, ChambreRepository $chambreRepository)
    {
        $this->reservationRepository = $reservationRepository;
        $this->chambreRepository = $chambreRepository;
    }

    public function getAllReservations(): array
    {
        return $this->reservationRepository->findAll();
    }

    public function getReservationById(int $id): ?Reservation
    {
        return $this->reservationRepository->findById($id);
    }

    public function createReservation(array $data): Reservation
    {
        $this->validateReservationData($data);

        $dateArrivee = new DateTime($data['dateArrivee']);
        $dateDepart = new DateTime($data['dateDepart']);
        
        $montantTotal = $this->calculateMontantTotal($data['chambreId'], $dateArrivee, $dateDepart);

        $reservationData = [
            'date_arrivee' => $dateArrivee->format('Y-m-d'),
            'date_depart' => $dateDepart->format('Y-m-d'),
            'montant_total' => $montantTotal,
            'statut' => $data['statut'] ?? Reservation::STATUT_CONFIRMEE,
            'client_id' => $data['clientId'],
            'chambre_id' => $data['chambreId']
        ];

        $id = $this->reservationRepository->save($reservationData);

        return new Reservation($id, $dateArrivee, $dateDepart, $montantTotal, $reservationData['statut'], $data['clientId'], $data['chambreId']);
    }

    public function updateReservation(int $id, array $data): ?Reservation
    {
        $reservation = $this->reservationRepository->findById($id);
        if (!$reservation) {
            return null;
        }

        $this->validateReservationData($data);

        $dateArrivee = new DateTime($data['dateArrivee']);
        $dateDepart = new DateTime($data['dateDepart']);
        
        $montantTotal = $this->calculateMontantTotal($data['chambreId'], $dateArrivee, $dateDepart);

        $updateData = [
            'date_arrivee' => $dateArrivee->format('Y-m-d'),
            'date_depart' => $dateDepart->format('Y-m-d'),
            'montant_total' => $montantTotal,
            'statut' => $data['statut'],
            'client_id' => $data['clientId'],
            'chambre_id' => $data['chambreId']
        ];

        $success = $this->reservationRepository->update($updateData, ['id' => $id]);

        if ($success) {
            return new Reservation($id, $dateArrivee, $dateDepart, $montantTotal, $data['statut'], $data['clientId'], $data['chambreId']);
        }

        return null;
    }

    public function deleteReservation(int $id): bool
    {
        return $this->reservationRepository->delete(['id' => $id]);
    }

    public function calculateMontantTotal(int $chambreId, \DateTime $dateArrivee, \DateTime $dateDepart): float
    {
        $chambre = $this->chambreRepository->findById($chambreId);
        if (!$chambre) {
            throw new \InvalidArgumentException("Chambre non trouvée");
        }

        $nombreNuits = $dateArrivee->diff($dateDepart)->days;
        $montantHT = $nombreNuits * $chambre->getTarifJournalier();
        $tva = $montantHT * 0.20; // TVA à 20%
        
        return $montantHT + $tva;
    }

    public function getReservationsWithDetails(): array
    {
        return $this->reservationRepository->findWithDetails();
    }

    private function validateReservationData(array $data): void
    {
        if (empty($data['dateArrivee']) || empty($data['dateDepart'])) {
            throw new \InvalidArgumentException("Les dates d'arrivée et de départ sont requises");
        }

        $dateArrivee = new DateTime($data['dateArrivee']);
        $dateDepart = new DateTime($data['dateDepart']);

        if ($dateArrivee >= $dateDepart) {
            throw new \InvalidArgumentException("La date de départ doit être postérieure à la date d'arrivée");
        }

        if ($dateArrivee < new DateTime('today')) {
            throw new \InvalidArgumentException("La date d'arrivée ne peut pas être dans le passé");
        }

        if (empty($data['clientId']) || empty($data['chambreId'])) {
            throw new \InvalidArgumentException("L'ID du client et l'ID de la chambre sont requis");
        }

        if (!empty($data['statut']) && !in_array($data['statut'], [Reservation::STATUT_CONFIRMEE, Reservation::STATUT_LIBRE, Reservation::STATUT_ANNULEE])) {
            throw new \InvalidArgumentException("Statut de réservation invalide");
        }
    }
}