<?php 

//Sessão no usuário logado
session_start();

//Require dos arquivos composer
require_once("vendor/autoload.php");

//Usando o Slim framework para routes
use \Slim\Slim;

//Iniciando o Slim para rotas
$app = new Slim();

//Funções requiridas
require_once("functions.php");

//Rotas requiridas
require_once("site.php");
require_once("admin-login.php");
require_once("admin-users.php");
require_once("admin-categories.php");
require_once("admin-products.php");


//Mostra os erros caso ocorra
$app->config('debug', true);

//Execute all routes
$app->run();

 ?>