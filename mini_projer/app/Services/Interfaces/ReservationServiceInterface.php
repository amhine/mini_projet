<?php
namespace App\Services\Interfaces;

use App\Models\Reservation;

interface ReservationServiceInterface
{
    public function getAllReservations(): array;
    public function getReservationById(int $id): ?Reservation;
    public function createReservation(array $data): Reservation;
    public function updateReservation(int $id, array $data): ?Reservation;
    public function deleteReservation(int $id): bool;
    public function calculateMontantTotal(int $chambreId, \DateTime $dateArrivee, \DateTime $dateDepart): float;
    public function getReservationsWithDetails(): array;
}