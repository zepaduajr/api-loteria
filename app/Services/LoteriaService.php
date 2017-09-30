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
        $concurso = "";
        if(!empty($ultimoConcurso) && is_numeric($ultimoConcurso)){
            $concurso = $this->detalharMegaSenaPorId((int)$ultimoConcurso);
            $concurso = (!empty($concurso)) ? (array)$concurso[0] : '';
        }

        if(empty($concurso)){
            $resultado = $this->extrairResultado($conteudo_arquivo, env('NME_ARQUIVO_MEGASENA'), $num_concurso);
            if(isset($resultado['error'])){
                return $resultado;
            }
            //Verifica se o concuros retornado está no banco
            $concurso_bd = $this->detalharMegaSenaPorId($resultado[0]);
            if(empty($concurso_bd)){
                if(is_numeric($resultado[0])){
                    $dataMegaSena = [(int)$resultado[0],$resultado[1],(int)$resultado[2],(int)$resultado[3],(int)$resultado[4],(int)$resultado[5],(int)$resultado[6],(int)$resultado[7]];
                    $this->inserirMegaSena($dataMegaSena);
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
                    return $arrayTd;
                }
            }

            $zip->close();
            return ['error'=>'Concurso não encontrado.'];
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