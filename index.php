<?php 

require_once("vendor/autoload.php");
use \Slim\Slim;
use \Hcode\Page;

$app = new Slim();

//Mostra os erros caso ocorra
$app->config('debug', true);

//Route padrão
$app->get('/', function() {
	
	$page = new Page();
	$page->setTpl("index");

});

//Executa
$app->run();

 ?>