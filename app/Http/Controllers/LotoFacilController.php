<?php

namespace App\Http\Controllers;

use App\Services\LoteriaService;
use App\Transformers\LotoFacilTransformer;

class LotoFacilController extends Controller
{
    /**
     * @var LoteriaService
     */
    private $loteriaService;
    /**
     * @var LotoFacilTransformer
     */
    private $lotoFacilTransformer;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(LoteriaService $loteriaService, LotoFacilTransformer $lotoFacilTransformer)
    {
        //
        $this->loteriaService = $loteriaService;
        $this->lotoFacilTransformer = $lotoFacilTransformer;
    }

    public function ultimoConcurso()
    {
        $retorno = $this->loteriaService->obterResultadoLotoFacil();
        return response()->json($this->lotoFacilTransformer->transform($retorno));
    }

    public function buscarConcurso($num_concurdo)
    {
        $retorno = $this->loteriaService->obterResultadoLotoFacil($num_concurdo);
        return response()->json($this->lotoFacilTransformer->transform($retorno));
    }
}
