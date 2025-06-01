<?php

namespace App\Services\Implementations;

use App\Models\Chambre;
use App\Models\ChambreStandard;
use App\Models\Suite;
use App\Repositories\ChambreRepository;
use App\Services\Interfaces\ChambreServiceInterface;

class ChambreService implements ChambreServiceInterface
{
    private ChambreRepository $chambreRepository;

    public function __construct(ChambreRepository $chambreRepository)
    {
        $this->chambreRepository = $chambreRepository;
    }

    public function getAllChambres(): array
    {
        return $this->chambreRepository->findAll();
    }

    public function getChambreById(int $id): ?Chambre
    {
        return $this->chambreRepository->findById($id);
    }

    public function createChambre(array $data): Chambre
    {
        $this->validateChambreData($data);

        $chambreData = [
            'numero' => $data['numero'],
            'tarif_journalier' => $data['tarifJournalier'],
            'capacite' => $data['capacite'],
            'type' => $data['type']
        ];

        if ($data['type'] === 'standard') {
            $chambreData['services_inclus'] = json_encode($data['servicesInclus'] ?? []);
        } elseif ($data['type'] === 'suite') {
            $chambreData['surface'] = $data['surface'];
            $chambreData['balcon'] = $data['balcon'] ? 1 : 0;
            $chambreData['services_premium'] = json_encode($data['servicesPremium'] ?? []);
        }

        $id = $this->chambreRepository->save($chambreData);

        if ($data['type'] === 'standard') {
            return new ChambreStandard($id, $data['numero'], $data['tarifJournalier'], $data['capacite'], $data['servicesInclus'] ?? []);
        } else {
            return new Suite($id, $data['numero'], $data['tarifJournalier'], $data['capacite'], $data['surface'], $data['balcon'], $data['servicesPremium'] ?? []);
        }
    }

    public function updateChambre(int $id, array $data): ?Chambre
    {
        $chambre = $this->chambreRepository->findById($id);
        if (!$chambre) {
            return null;
        }

        $this->validateChambreData($data);

        $updateData = [
            'numero' => $data['numero'],
            'tarif_journalier' => $data['tarifJournalier'],
            'capacite' => $data['capacite'],
            'type' => $data['type']
        ];

        if ($data['type'] === 'standard') {
            $updateData['services_inclus'] = json_encode($data['servicesInclus'] ?? []);
            $updateData['surface'] = null;
            $updateData['balcon'] = null;
            $updateData['services_premium'] = null;
        } elseif ($data['type'] === 'suite') {
            $updateData['surface'] = $data['surface'];
            $updateData['balcon'] = $data['balcon'] ? 1 : 0;
            $updateData['services_premium'] = json_encode($data['servicesPremium'] ?? []);
            $updateData['services_inclus'] = null;
        }

        $success = $this->chambreRepository->update($updateData, ['id' => $id]);

        if ($success) {
            return $this->chambreRepository->findById($id);
        }

        return null;
    }

    public function deleteChambre(int $id): bool
    {
        return $this->chambreRepository->delete(['id' => $id]);
    }

    public function getAvailableChambres(\DateTime $dateArrivee, \DateTime $dateDepart): array
    {
        return $this->chambreRepository->findAvailableChambres($dateArrivee, $dateDepart);
    }

    private function validateChambreData(array $data): void
    {
        if (empty($data['numero'])) {
            throw new \InvalidArgumentException("Le numéro de chambre est requis");
        }

        if (empty($data['tarifJournalier']) || $data['tarifJournalier'] <= 0) {
            throw new \InvalidArgumentException("Le tarif journalier doit être supérieur à 0");
        }

        if (empty($data['capacite']) || $data['capacite'] <= 0) {
            throw new \InvalidArgumentException("La capacité doit être supérieure à 0");
        }

        if (!in_array($data['type'], ['standard', 'suite'])) {
            throw new \InvalidArgumentException("Le type de chambre doit être 'standard' ou 'suite'");
        }

        if ($data['type'] === 'suite') {
            if (empty($data['surface']) || $data['surface'] <= 0) {
                throw new \InvalidArgumentException("La surface est requise pour une suite");
            }
        }
    }
}

