<?php

namespace App\Repositories;


use Illuminate\Support\Facades\DB;

class MegaSenaRepository
{
    public function detalharPorNumConcurso($num_concurso)
    {
        $megasena = DB::select('select * from megasena where num_concurso = ?', [$num_concurso]);
        return $megasena;
    }

    public function inserir(array $data)
    {
        DB::insert('insert into megasena (num_concurso, dat_sorteio, num_1, num_2, num_3, num_4, num_5, num_6) values (?, ?, ?, ?, ?, ?, ?, ?)', $data);
    }
}


