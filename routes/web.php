<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('resumo', ['as' => 'loteria.resumo', 'uses' => 'LoteriaController@resumo']);

$router->get('megasena', ['as' => 'megasena', 'uses' => 'MegaSenaController@ultimoConcurso']);
$router->get('megasena/sincronizar', ['as' => 'megasena', 'uses' => 'MegaSenaController@sincronizarMegaSena']);
$router->get('megasena/all', ['as' => 'megasena', 'uses' => 'MegaSenaController@listarMegasena']);
$router->get('megasena/{num_concurdo}', ['as' => 'megasena', 'uses' => 'MegaSenaController@buscarConcurso']);

$router->get('lotofacil', ['as' => 'megasena', 'uses' => 'LotoFacilController@ultimoConcurso']);
$router->get('lotofacil/{num_concurdo}', ['as' => 'megasena', 'uses' => 'LotoFacilController@buscarConcurso']);