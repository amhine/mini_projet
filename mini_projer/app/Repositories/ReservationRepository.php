<?php
namespace App\Repositories;

use App\Models\Reservation;
use Core\Repository\RepositoryMutations;
use DateTime;

class ReservationRepository extends RepositoryMutations
{
    public function __construct()
    {
        parent::__construct('reservations');
    }

    protected function mapper(array $data): object
    {
        return new Reservation(
            $data['id'] ?? null,
            new DateTime($data['date_arrivee']),
            new DateTime($data['date_depart']),
            (float)$data['montant_total'],
            $data['statut'],
            (int)$data['client_id'],
            (int)$data['chambre_id']
        );
    }

    public function findAll(): array
    {
        $query = "SELECT * FROM {$this->tableName} ORDER BY date_arrivee DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        $results = $stmt->fetchAll();
        return array_map([$this, 'mapper'], $results);
    }

    public function findById(int $id): ?Reservation
    {
        $query = "SELECT * FROM {$this->tableName} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $result = $stmt->fetch();
        return $result ? $this->mapper($result) : null;
    }

    public function findByClientId(int $clientId): array
    {
        $query = "SELECT * FROM {$this->tableName} WHERE client_id = :clientId ORDER BY date_arrivee DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':clientId', $clientId);
        $stmt->execute();
        
        $results = $stmt->fetchAll();
        return array_map([$this, 'mapper'], $results);
    }

    public function findByChambreId(int $chambreId): array
    {
        $query = "SELECT * FROM {$this->tableName} WHERE chambre_id = :chambreId ORDER BY date_arrivee DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':chambreId', $chambreId);
        $stmt->execute();
        
        $results = $stmt->fetchAll();
        return array_map([$this, 'mapper'], $results);
    }

    public function findWithDetails(): array
    {
        $query = "SELECT r.*, c.nom as client_nom, ch.numero as chambre_numero 
                  FROM {$this->tableName} r
                  JOIN clients c ON r.client_id = c.id
                  JOIN chambres ch ON r.chambre_id = ch.id
                  ORDER BY r.date_arrivee DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        $results = $stmt->fetchAll();
        $reservations = [];
        
        foreach ($results as $row) {
            $reservation = $this->mapper($row);
            $data = $reservation->jsonSerialize();
            $data['clientNom'] = $row['client_nom'];
            $data['chambreNumero'] = $row['chambre_numero'];
            $reservations[] = $data;
        }
        
        return $reservations;
    }
}

