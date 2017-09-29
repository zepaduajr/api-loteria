<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;


/**
 * Class Anexo
 * @package namespace VentLogos\Entities\Eloquent;
 */
class MegaSena extends Model
{
    protected $table = 'megasena';
    protected $primaryKey = 'id_megasena';


    protected $fillable = [
        'num_concurso','dat_sorteio', 'num_1', 'num_2', 'num_3', 'num_4', 'num_5', 'num_6',
    ];

    
}


