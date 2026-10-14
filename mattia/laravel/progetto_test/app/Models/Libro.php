<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    protected $table = 'libri';//Nome della tabella

    //Specifica la chiave primaria (fallo solo se NON si chiama 'id')
    protected $primaryKey = 'identificativo';

    //Disattiva i timestamp se la tabella non ha le colonne created_at e updated_at
    //public $timestamps = false;


    //Tutti i campi della tabella che devo utilizzare
    protected $fillable = [
        'titolo',
        'isbn',
        'prezzo',
    ];

    /**
     * Casting automatico dei tipi di dati.
     * 'pubblicato' sarà sempre un bool in PHP, non un int 0/1.
     */
    protected $casts = [
        'created_at'    => 'datetime',
        'update_at'     => 'datetime',
    ];
}
