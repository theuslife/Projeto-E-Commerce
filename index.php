<?php 

require_once("vendor/autoload.php");

$app = new \Slim\Slim();

//Mostra os erros caso ocorra
$app->config('debug', true);

//Route padrão
$app->get('/', function() {
    
	$sql = new Hcode\DB\Sql();
	$results = $sql->select("SELECT * FROM tb_users");
	echo json_encode($results);

});

//Executa
$app->run();

 ?>