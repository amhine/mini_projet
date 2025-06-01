<?php
namespace App\Repositories;

use App\Models\Chambre;
use App\Models\ChambreStandard;
use App\Models\Suite;
use Core\Repository\RepositoryMutations;

class ChambreRepository extends RepositoryMutations
{
    public function __construct()
    {
        parent::__construct('chambres');
    }

    protected function mapper(array $data): object
    {
        if ($data['type'] === 'standard') {
            return new ChambreStandard(
                $data['id'] ?? null,
                $data['numero'],
                (float)$data['tarif_journalier'],
                (int)$data['capacite'],
                json_decode($data['services_inclus'] ?? '[]', true)
            );
        } elseif ($data['type'] === 'suite') {
            return new Suite(
                $data['id'] ?? null,
                $data['numero'],
                (float)$data['tarif_journalier'],
                (int)$data['capacite'],
                (float)$data['surface'],
                (bool)$data['balcon'],
                json_decode($data['services_premium'] ?? '[]', true)
            );
        }
        
        throw new \InvalidArgumentException("Type de chambre non supporté: " . $data['type']);
    }

    public function findAll(): array
    {
        $query = "SELECT * FROM {$this->tableName} ORDER BY numero";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        $results = $stmt->fetchAll();
        return array_map([$this, 'mapper'], $results);
    }

    public function findById(int $id): ?Chambre
    {
        $query = "SELECT * FROM {$this->tableName} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $result = $stmt->fetch();
        return $result ? $this->mapper($result) : null;
    }

    public function findByNumero(string $numero): ?Chambre
    {
        $query = "SELECT * FROM {$this->tableName} WHERE numero = :numero";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':numero', $numero);
        $stmt->execute();
        
        $result = $stmt->fetch();
        return $result ? $this->mapper($result) : null;
    }

    public function findAvailableChambres(\DateTime $dateArrivee, \DateTime $dateDepart): array
    {
        $query = "SELECT c.* FROM {$this->tableName} c 
                  WHERE c.id NOT IN (
                      SELECT r.chambre_id FROM reservations r 
                      WHERE r.statut = 'CONFIRMÉE' 
                      AND (
                          (r.date_arrivee <= :dateArrivee AND r.date_depart > :dateArrivee)
                          OR (r.date_arrivee < :dateDepart AND r.date_depart >= :dateDepart)
                          OR (r.date_arrivee >= :dateArrivee AND r.date_depart <= :dateDepart)
                      )
                  )
                  ORDER BY c.numero";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':dateArrivee', $dateArrivee->format('Y-m-d'));
        $stmt->bindParam(':dateDepart', $dateDepart->format('Y-m-d'));
        $stmt->execute();
        
        $results = $stmt->fetchAll();
        return array_map([$this, 'mapper'], $results);
    }
}

