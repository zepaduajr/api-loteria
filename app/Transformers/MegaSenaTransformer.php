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
            'num_concurso' => $data['num_concurso'],
            'dat_sorteio' => $data['dat_sorteio'],
            'num_1' => $data['num_1'],
            'num_2' => $data['num_2'],
            'num_3' => $data['num_3'],
            'num_4' => $data['num_4'],
            'num_5' => $data['num_5'],
            'num_6' => $data['num_6'],
        ];
    }
    
}
