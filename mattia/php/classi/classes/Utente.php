<?php 
class Utente{
    //ATTRIBUTI
    private $nome;
    private $cognome;
    private $email;
    private $password;
    private $cf;

    /**
     * Costruttore
     */
    public function __construct($nome, $cognome, $email, $password, $cf){
        $this->nome = $nome;
        $this->cognome = $cognome;
        $this->email = $email;
        $this->password = $this->hashMd5($password);
        $this->cf = $cf;
    }

    //METODI SETTER
    public function setNome($nome){$this->nome = $nome;}
    public function setCognome($cognome){$this->cognome = $cognome;}
    public function setEmail($email){$this->email = $email;}
    public function setPassword($password){$this->password = $this->hashMd5($password, date("dmY"));}
    public function setCf($cf){$this->cf = $cf;}

    //METODI GETTER
    public function getNome(){return $this->nome;}
    public function getCognome(){return $this->cognome;}
    public function getEmail(){return $this->email;}
    public function getCf(){return $this->cf;}

    //ALTRI METODI
    private function hashMd5($password, $date = ""){
        return md5($password).$date;
    }

}