<?php

namespace App\Models;

class Suite extends Chambre
{
    private  $surface;
    private  $balcon;
    private  $servicesPremium;

    public function __construct( $id, $numero,  $tarifJournalier,  $capacite,  $surface, $balcon, $servicesPremium = [])
    {
        parent::__construct($id, $numero, $tarifJournalier, $capacite, 'suite');
        $this->surface = $surface;
        $this->balcon = $balcon;
        $this->servicesPremium = $servicesPremium;
    }

     public function jsonSerialize()
    {
        $data = parent::jsonSerialize();
        $data['surface'] = $this->surface;
        $data['balcon'] = $this->balcon;
        $data['servicesPremium'] = $this->servicesPremium;
        return $data;
    }


    public function getSurface()
    {
        return $this->surface;
    }

    public function hasBalcon()
    {
        return $this->balcon;
    }

    public function getServicesPremium()
    {
        return $this->servicesPremium;
    }

    public function setSurface( $surface)
    {
        $this->surface = $surface;
    }

    public function setBalcon( $balcon)
    {
        $this->balcon = $balcon;
    }

    public function setServicesPremium($servicesPremium)
    {
        $this->servicesPremium = $servicesPremium;
    }

   
}