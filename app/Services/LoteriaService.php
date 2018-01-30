<?php

namespace App\Services;


use App\Repositories\LotoFacilRepository;
use App\Repositories\MegaSenaRepository;
use GrahamCampbell\Flysystem\FlysystemManager;
use Illuminate\Support\Facades\Log;

class LoteriaService
{
    protected $flysystem;
    /**
     * @var MegaSenaRepository
     */
    private $megaSenaRepository;
    /**
     * @var LotoFacilRepository
     */
    private $lotoFacilRepository;

    public function __construct(FlysystemManager $flysystem, MegaSenaRepository $megaSenaRepository, LotoFacilRepository $lotoFacilRepository)
    {
        $this->flysystem = $flysystem;
        $this->megaSenaRepository = $megaSenaRepository;
        $this->lotoFacilRepository = $lotoFacilRepository;
    }

    public function obterResultadoMegaSena($num_concurso = "")
    {
        $conteudo_arquivo = getFileUrl(env('URL_ZIP_MEGASENA'));
        $concurso = "";

        if($num_concurso == "") {
            $ultimoConcurso = $this->getNumeroUltimoConcurso(env('URL_CAIXA_MEGASENA'));
            $num_concurso = $ultimoConcurso;
        }

        if(!empty($num_concurso) && is_numeric($num_concurso)){
            $concurso = $this->megaSenaRepository->detalharPorNumConcurso((int)$num_concurso);
            $concurso = (!empty($concurso)) ? (array)$concurso[0] : '';
        }


        if(empty($concurso)){
            $resultado = $this->extrairResultado($conteudo_arquivo, env('NME_ARQUIVO_MEGASENA'), $num_concurso);
            if(isset($resultado['error'])){
                return $resultado;
            }
            //Verifica se o concuros retornado está no banco
            $concurso_bd = $this->megaSenaRepository->detalharPorNumConcurso($resultado[0]);
            if(empty($concurso_bd)){
                if(is_numeric($resultado[0])){
                    $dataMegaSena = [(int)$resultado[0],$resultado[1],(int)$resultado[2],(int)$resultado[3],(int)$resultado[4],(int)$resultado[5],(int)$resultado[6],(int)$resultado[7]];
                    $this->megaSenaRepository->inserir($dataMegaSena);
                }
            }
            $concurso = [
                'num_concurso' => $resultado[0],
                'dat_sorteio' => $resultado[1],
                'num_1' => $resultado[2],
                'num_2' => $resultado[3],
                'num_3' => $resultado[4],
                'num_4' => $resultado[5],
                'num_5' => $resultado[6],
                'num_6' => $resultado[7],
            ];
        }

        return $concurso;

    }

    public function obterResultadoLotoFacil($num_concurso = "")
    {
        $conteudo_arquivo = getFileUrl(env('URL_ZIP_LOTOFACIL'));
        $concurso = "";

        if($num_concurso == "") {
            $ultimoConcurso = $this->getNumeroUltimoConcurso(env('URL_CAIXA_LOTOFACIL'));
            $num_concurso = $ultimoConcurso;
        }

        if(!empty($num_concurso) && is_numeric($num_concurso)){
            $concurso = $this->lotoFacilRepository->detalharPorNumConcurso((int)$num_concurso);
            $concurso = (!empty($concurso)) ? (array)$concurso[0] : '';
        }


        if(empty($concurso)){
            $resultado = $this->extrairResultado($conteudo_arquivo, env('NME_ARQUIVO_LOTOFACIL'), $num_concurso);
            if(isset($resultado['error'])){
                return $resultado;
            }
            //Verifica se o concuros retornado está no banco
            $concurso_bd = $this->lotoFacilRepository->detalharPorNumConcurso($resultado[0]);
            if(empty($concurso_bd)){
                if(is_numeric($resultado[0])){
                    $dataLotoFacil = [
                        (int)$resultado[0],
                        $resultado[1],
                        (int)$resultado[2],
                        (int)$resultado[3],
                        (int)$resultado[4],
                        (int)$resultado[5],
                        (int)$resultado[6],
                        (int)$resultado[7],
                        (int)$resultado[8],
                        (int)$resultado[9],
                        (int)$resultado[10],
                        (int)$resultado[11],
                        (int)$resultado[12],
                        (int)$resultado[13],
                        (int)$resultado[14],
                        (int)$resultado[15],
                        (int)$resultado[16]];
                    $this->lotoFacilRepository->inserir($dataLotoFacil);
                }
            }
            $concurso = [
                'num_concurso' => $resultado[0],
                'dat_sorteio' => $resultado[1],
                'num_1' => $resultado[2],
                'num_2' => $resultado[3],
                'num_3' => $resultado[4],
                'num_4' => $resultado[5],
                'num_5' => $resultado[6],
                'num_6' => $resultado[7],
                'num_7' => $resultado[8],
                'num_8' => $resultado[9],
                'num_9' => $resultado[10],
                'num_10' => $resultado[11],
                'num_11' => $resultado[12],
                'num_12' => $resultado[13],
                'num_13' => $resultado[14],
                'num_14' => $resultado[15],
                'num_15' => $resultado[16],
            ];
        }

        return $concurso;
    }

    protected function getNumeroUltimoConcurso($url = '')
    {
        $conteudo_arquivo = getFileUrl($url);
        $this->flysystem->put(env('NME_ARQUIVO_EXTRACAO'), $conteudo_arquivo);

        $arquivo = $this->flysystem->read(env('NME_ARQUIVO_EXTRACAO'));
        $dom = new \DOMDocument();
        @$dom->loadHtml($arquivo);
        $xp = new \DOMXPath($dom);
        $nodes = $xp->query('//div[@class="title-bar clearfix"]');
        $concurso = '';
        foreach ($nodes as $n)
        {
            $texto = str_replace(' ', ';', $n->textContent);
            $array = explode(';',($texto));
            $concurso = $array[2];
        }

        return $concurso;

    }

    protected function extrairResultado($conteudo_arquivo, $nme_arquivo, $num_concurso = "")
    {
        Log::info('numero -> '.$num_concurso);
        $num_concurso = (is_numeric($num_concurso)) ? $num_concurso : '';
        try {

            $this->flysystem->put('resultado.zip', $conteudo_arquivo);

            $arquivo = storage_path('files/resultado.zip');
            $destino = storage_path('files');
    
            $zip = new \ZipArchive();
            $zip->open($arquivo);
            if($zip->extractTo($destino)){
                $arquivo = $this->flysystem->read($nme_arquivo);
                $dom = new \DOMDocument();
                @$dom->loadHtml($arquivo);
                $totalLinhas = $dom->getElementsByTagName('tr')->length;
    
                for ($i=$totalLinhas-1; $i >= 0; $i--) {
                    $conteudoTd = $dom->getElementsByTagName('tr')->item($i)->textContent;
                    $arrayTd = explode("\r\n",$conteudoTd);
                    $arrayTd = array_map('removeCaractereArray', $arrayTd);
    
                    if($num_concurso != ""){
                        if($arrayTd[0] == $num_concurso){
                            return $arrayTd;
                        }
                    }else{
                        if(is_numeric($arrayTd[0])){
                            return $arrayTd;
                        }
                    }
                }
    
                $zip->close();
                return ['error'=>'Concurso não encontrado.'];
            }else{
                $zip->close();
                return ['error'=>'O Arquivo não pode ser descompactado.'];
            }

        } catch (\Exception $e) {
            return ['error'=>$e->getMessage()];
        }

    }
}