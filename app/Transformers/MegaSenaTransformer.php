<?php

namespace App\Transformers;


/**
 * Class AnexoTransformer
 * @package namespace CooperacaoFiscal\Transformers;
 */
class MegaSenaTransformer
{
    public function transform(array $data)
    {
        return [
            'num_concurso' => $data[0],
            'dat_sorteio' => $data[1],
            'num_1' => $data[2],
            'num_2' => $data[3],
            'num_3' => $data[4],
            'num_4' => $data[5],
            'num_5' => $data[6],
            'num_6' => $data[7],
        ];
    }
    
}
