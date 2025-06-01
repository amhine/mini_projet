<?php

namespace App\Models;

use JsonSerializable;
use DateTime;

class Reservation implements JsonSerializable
{
    private  $id;
    private DateTime $dateArrivee;
    private DateTime $dateDepart;
    private  $montantTotal;
    private  $statut;
    private  $clientId;
    private  $chambreId;

    public const STATUT_CONFIRMEE = 'CONFIRMÉE';
    public const STATUT_LIBRE = 'LIBRE';
    public const STATUT_ANNULEE = 'ANNULÉE';

    public function __construct( $id, DateTime $dateArrivee, DateTime $dateDepart,  $montantTotal,  $statut, $clientId,  $chambreId)
    {
        $this->id = $id;
        $this->dateArrivee = $dateArrivee;
        $this->dateDepart = $dateDepart;
        $this->montantTotal = $montantTotal;
        $this->statut = $statut;
        $this->clientId = $clientId;
        $this->chambreId = $chambreId;
    }

     public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'dateArrivee' => $this->dateArrivee->format('Y-m-d'),
            'dateDepart' => $this->dateDepart->format('Y-m-d'),
            'montantTotal' => $this->montantTotal,
            'statut' => $this->statut,
            'clientId' => $this->clientId,
            'chambreId' => $this->chambreId,
            'nombreNuits' => $this->getNombreNuits()
        ];
    }

    public function getDateArrivee()
    {
        return $this->dateArrivee;
    }

    public function getDateDepart()
    {
        return $this->dateDepart;
    }

    public function getMontantTotal()
    {
        return $this->montantTotal;
    }

    public function getStatut()
    {
        return $this->statut;
    }

    public function getClientId()
    {
        return $this->clientId;
    }

    public function getChambreId()
    {
        return $this->chambreId;
    }

   

    public function setDateArrivee(DateTime $dateArrivee)
    {
        $this->dateArrivee = $dateArrivee;
    }

    public function setDateDepart(DateTime $dateDepart)
    {
        $this->dateDepart = $dateDepart;
    }

    public function setMontantTotal( $montantTotal)
    {
        $this->montantTotal = $montantTotal;
    }

    public function setStatut( $statut)
    {
        $this->statut = $statut;
    }

    public function setClientId( $clientId)
    {
        $this->clientId = $clientId;
    }

    public function setChambreId( $chambreId)
    {
        $this->chambreId = $chambreId;
    }

    public function getNombreNuits() 
    {
        return $this->dateArrivee->diff($this->dateDepart)->days;
    }

   
}


