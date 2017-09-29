<?php

namespace App\Transformers;


/**
 * Class AnexoTransformer
 * @package namespace CooperacaoFiscal\Transformers;
 */
class LotoFacilTransformer
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
            'num_7' => $data[8],
            'num_8' => $data[9],
            'num_9' => $data[10],
            'num_10' => $data[11],
            'num_11' => $data[12],
            'num_12' => $data[13],
            'num_13' => $data[14],
            'num_14' => $data[15],
            'num_15' => $data[16],
        ];
    }
    
}
