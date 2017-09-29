<?php
/**
 * Created by PhpStorm.
 * User: jgpadua
 * Date: 28/09/2017
 * Time: 14:20
 */

namespace App\Services;


use GrahamCampbell\Flysystem\FlysystemManager;
use Illuminate\Support\Facades\Storage;

class LoteriaService
{
    protected $flysystem;

    public function __construct(FlysystemManager $flysystem)
    {
        $this->flysystem = $flysystem;
    }

    public function obterResultadoMegaSena($num_concurso = "")
    {
        $conteudo_arquivo = getFileUrl(env('URL_ZIP_MEGASENA'));
        return $this->extrairResultado($conteudo_arquivo, env('NME_ARQUIVO_MEGASENA'), $num_concurso);
    }

    public function obterResultadoLotoFacil($num_concurso = "")
    {
        $conteudo_arquivo = getFileUrl(env('URL_ZIP_LOTOFACIL'));
        return $this->extrairResultado($conteudo_arquivo, env('NME_ARQUIVO_LOTOFACIL'), $num_concurso);
    }

    protected function extrairResultado($conteudo_arquivo, $nme_arquivo, $num_concurso = "")
    {
        $this->flysystem->put('resultado.zip', $conteudo_arquivo);

        $arquivo = storage_path('files/resultado.zip');
        $destino = storage_path('files');

        $zip = new \ZipArchive();
        $zip->open($arquivo);
        if($zip->extractTo($destino)){
            $arquivo = $this->flysystem->read($nme_arquivo);
            $dom = new \DOMDocument();
            @$dom->loadHtml($arquivo);
            $arrayTr = array();
            $totalLinhas = $dom->getElementsByTagName('tr')->length;

            for ($i=0; $i < $totalLinhas; $i++) {
                $conteudoTd = $dom->getElementsByTagName('tr')->item($i)->textContent;
                $arrayTd = explode("\r\n",$conteudoTd);
                $arrayTd = array_map('removeCaractereArray', $arrayTd);
                if($num_concurso != ""){
                    if($arrayTd[0] == $num_concurso){
                        array_push($arrayTr, $arrayTd);
                    }
                }else{
                    array_push($arrayTr, $arrayTd);
                }
            }
            $qtdLinhas = count($arrayTr);

            $zip->close();

            return $arrayTr[$qtdLinhas-1];
        }else{
            $zip->close();

            return ['error'=>'O Arquivo não pode ser descompactado.'];
        }

    }
}