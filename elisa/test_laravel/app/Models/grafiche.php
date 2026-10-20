<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class grafiche extends Model
{
    use HasFactory;
    protected $table='grafiche';
    protected $fillable=[
        'titolo',
        'descrizione',
        'categoria',
        'anno',
    ];
}
