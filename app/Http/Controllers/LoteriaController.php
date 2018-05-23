<?php

namespace App\Http\Controllers;

use App\Services\LoteriaService;
use App\Transformers\MegaSenaTransformer;

class LoteriaController extends Controller
{
    /**
     * @var LoteriaService
     */
    private $loteriaService;
    /**
     * @var MegaSenaTransformer
     */
    private $megaSenaTransformer;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(LoteriaService $loteriaService, MegaSenaTransformer $megaSenaTransformer)
    {
        $this->loteriaService = $loteriaService;
        $this->megaSenaTransformer = $megaSenaTransformer;
    }

    public function resumo()
    {
        $resumo = [];
        $retorno = $this->loteriaService->obterResultadoMegaSena();
        if(isset($retorno['error'])){
            return response()->json($retorno);
        }
        $resumo['megasena'] = $retorno;

        $retorno = $this->loteriaService->obterResultadoLotoFacil();
        if(isset($retorno['error'])){
            return response()->json($retorno);
        }
        $resumo['lotofacil'] = $retorno;

        return response()->json($resumo);
    }
}
