<?php


namespace App\Models;

class ChambreStandard extends Chambre
{
    private array $servicesInclus;

    public function __construct($id, $numero, $tarifJournalier, $capacite,  $servicesInclus = [])
    {
        parent::__construct($id, $numero, $tarifJournalier, $capacite, 'standard');
        $this->servicesInclus = $servicesInclus;
    }
    
    public function jsonSerialize()
    {
        $data = parent::jsonSerialize();
        $data['servicesInclus'] = $this->servicesInclus;
        return $data;
    }

    public function getServicesInclus(){
        return $this->servicesInclus;
    }

    public function setServicesInclus( $servicesInclus)
    {
        $this->servicesInclus = $servicesInclus;
    }

}



