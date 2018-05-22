<?php

namespace App\Http\Controllers;

use App\Services\LoteriaService;
use App\Transformers\MegaSenaTransformer;

class MegaSenaController extends Controller
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

    public function ultimoConcurso()
    {
        $retorno = $this->loteriaService->obterResultadoMegaSena();
        if(isset($retorno['error'])){
            return response()->json($retorno);
        }

        return response()->json($this->megaSenaTransformer->transform($retorno));
    }

    public function buscarConcurso($num_concurdo)
    {
        $retorno = $this->loteriaService->obterResultadoMegaSena($num_concurdo);
        if(isset($retorno['error'])){
            return response()->json($retorno);
        }

        return response()->json($this->megaSenaTransformer->transform($retorno));
    }

    public function sincronizarMegaSena()
    {
        $retorno = $this->loteriaService->sincronizarMegaSena();
        if(isset($retorno['error'])){
            return response()->json($retorno);
        }

        return response()->json($retorno);
    }
}
