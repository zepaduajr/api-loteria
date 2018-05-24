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
        DB::insert('insert into megasena (num_concurso, dat_sorteio, num_1, num_2, num_3, num_4, num_5, num_6, 
                    num_ganhador_sena, vlr_rateio_sena, num_ganhador_quina, vlr_rateio_quina, num_ganhador_quadra, vlr_rateio_quadra, flg_acumulado, vlr_premio_previsto) 
                    values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', $data);
    }

    public function listar()
    {
        $megasena = DB::select('select * from megasena order by num_concurso desc');
        return $megasena;
    }

    public function detalharUltimoConcurso(){
        $megasena = DB::select('SELECT t.* FROM megasena t order by num_concurso desc LIMIT 1');
        return $megasena;
    }
}


