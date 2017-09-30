<?php

namespace App\Repositories;


use Illuminate\Support\Facades\DB;

class LotoFacilRepository
{
    public function detalharPorNumConcurso($num_concurso)
    {
        $lotofacil = DB::select('select * from lotofacil where num_concurso = ?', [$num_concurso]);
        return $lotofacil;
    }

    public function inserir(array $data)
    {
        DB::insert('insert into lotofacil (num_concurso, dat_sorteio, num_1, num_2, num_3, num_4, num_5, num_6, num_7, num_8, num_9, num_10, num_11, num_12, num_13, num_14, num_15) 
                    values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', $data);
    }
}


