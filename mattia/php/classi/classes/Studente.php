<?php
class Studente extends Utente{
    private $matricola;
    private $corsoDiStudio;

    public function __construct($nome, $cognome, $email, $password, $cf, $matricola, $corso){
        parent::__construct($nome, $cognome, $email, $password, $cf);
        $this->matricola        = $matricola;
        $this->corsoDiStudio    = $corso;
    }

    //public function setMatricola($matricola){$this->matricola = $matricola;}
    public function setCorsoDiStudio($corso){$this->corsoDiStudio = $corso;}

    public function getMatricola(){return $this->matricola;}
    public function getCorsoDiStudio(){return $this->corsoDiStudio;}


    //Overidding di getNome() definito in Utente
    private function getNome(){return parent::getNome() . " " . parent::getCognome();}
}