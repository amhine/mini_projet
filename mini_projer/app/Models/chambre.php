<?php

namespace App\Models;

use JsonSerializable;

abstract class Chambre implements JsonSerializable
{
    protected  $id;
    protected  $numero;
    protected  $tarifJournalier;
    protected  $capacite;
    protected  $type;

    public function __construct( $id,  $numero,  $tarifJournalier,  $capacite,  $type)
    {
        $this->id = $id;
        $this->numero = $numero;
        $this->tarifJournalier = $tarifJournalier;
        $this->capacite = $capacite;
        $this->type = $type;
    }

     public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'numero' => $this->numero,
            'tarifJournalier' => $this->tarifJournalier,
            'capacite' => $this->capacite,
            'type' => $this->type
        ];
    }
   

    public function getNumero()
    {
        return $this->numero;
    }

    public function getTarifJournalier()
    {
        return $this->tarifJournalier;
    }

    public function getCapacite()
    {
        return $this->capacite;
    }

    public function getType()
    {
        return $this->type;
    }



    public function setNumero( $numero)
    {
        $this->numero = $numero;
    }

    public function setTarifJournalier( $tarifJournalier)
    {
        $this->tarifJournalier = $tarifJournalier;
    }

    public function setCapacite($capacite)
    {
        $this->capacite = $capacite;
    }

    public function setType( $type)
    {
        $this->type = $type;
    }

   
}

