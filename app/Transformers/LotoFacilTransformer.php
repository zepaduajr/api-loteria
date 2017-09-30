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
            'num_concurso' => $data['num_concurso'],
            'dat_sorteio' => $data['dat_sorteio'],
            'num_1' => $data['num_1'],
            'num_2' => $data['num_2'],
            'num_3' => $data['num_3'],
            'num_4' => $data['num_4'],
            'num_5' => $data['num_5'],
            'num_6' => $data['num_6'],
            'num_7' => $data['num_7'],
            'num_8' => $data['num_8'],
            'num_9' => $data['num_9'],
            'num_10' => $data['num_10'],
            'num_11' => $data['num_11'],
            'num_12' => $data['num_12'],
            'num_13' => $data['num_13'],
            'num_14' => $data['num_14'],
            'num_15' => $data['num_15'],
        ];
    }
    
}
