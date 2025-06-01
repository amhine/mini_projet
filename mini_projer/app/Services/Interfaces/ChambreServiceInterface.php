<?php

namespace App\Services\Interfaces;

use App\Models\Chambre;

interface ChambreServiceInterface
{
    public function getAllChambres(): array;
    public function getChambreById(int $id): ?Chambre;
    public function createChambre(array $data): Chambre;
    public function updateChambre(int $id, array $data): ?Chambre;
    public function deleteChambre(int $id): bool;
    public function getAvailableChambres(\DateTime $dateArrivee, \DateTime $dateDepart): array;
}