<?php
/**
 * Created by PhpStorm.
 * User: jgpadua
 * Date: 28/09/2017
 * Time: 14:20
 */

namespace App\Services;


use GrahamCampbell\Flysystem\FlysystemManager;
use Illuminate\Support\Facades\DB;
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
        $ultimoConcurso = $this->getNumeroUltimoConcurso(env('URL_CAIXA_MEGASENA'));
        $concurso = $this->detalharMegaSenaPorId((int)$ultimoConcurso);
        if(empty($concurso)){
            return $this->extrairResultado($conteudo_arquivo, env('NME_ARQUIVO_MEGASENA'), $num_concurso);
        }

        return $concurso;

    }

    public function obterResultadoLotoFacil($num_concurso = "")
    {
        $conteudo_arquivo = getFileUrl(env('URL_ZIP_LOTOFACIL'));
        return $this->extrairResultado($conteudo_arquivo, env('NME_ARQUIVO_LOTOFACIL'), $num_concurso);
    }

    protected function getNumeroUltimoConcurso($url = '')
    {
        $conteudo_arquivo = getFileUrl($url);
        $this->flysystem->put('teste.html', $conteudo_arquivo);

        $arquivo = $this->flysystem->read('teste.html');
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

                $concurso = $this->detalharMegaSenaPorId((int)$arrayTd[0]);
                if(empty($concurso)){
                    if(is_numeric($arrayTd[0])){
                        $dataMegaSena = [(int)$arrayTd[0],$arrayTd[1],(int)$arrayTd[2],(int)$arrayTd[3],(int)$arrayTd[4],(int)$arrayTd[5],(int)$arrayTd[6],(int)$arrayTd[7]];
                        $this->inserirMegaSena($dataMegaSena);
                    }
                }

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

    public function detalharMegaSenaPorId($id_megasena)
    {
        $megasena = DB::select('select * from megasena where id_megasena = ?', [$id_megasena]);
        return $megasena;
    }

    public function inserirMegaSena(array $data)
    {
        DB::insert('insert into megasena (num_concurso, dat_sorteio, num_1, num_2, num_3, num_4, num_5, num_6) values (?, ?, ?, ?, ?, ?, ?, ?)', $data);
    }
}