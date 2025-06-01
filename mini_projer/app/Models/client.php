<?php
namespace App\Models;

use JsonSerializable;

class Client implements JsonSerializable
{
    private  $id;
    private  $nom;
    private  $email;
    private  $phone;

    public function __construct( $id,  $nom, $email, $phone)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->email = $email;
        $this->phone = $phone;
    }

     public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'email' => $this->email,
            'phone' => $this->phone
        ];
    }
   

    public function getNom()
    {
        return $this->nom;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    
    

    public function setNom( $nom)
    {
        $this->nom = $nom;
    }

    public function setEmail( $email)
    {
        $this->email = $email;
    }

    public function setPhone( $phone)
    {
        $this->phone = $phone;
    }

   
}