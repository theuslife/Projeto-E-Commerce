<?php 

//								 Configurações bases do Index 				
	//Require dos arquivos composer
	require_once("vendor/autoload.php");

	//Usando o Slim framework para routes
	use \Slim\Slim;

	//Usando a classe Page para gerar o template completo HTML (Header, index, footer)
	use \Hcode\Page;

// 							Finalizado configurações bases do Index 			


//Iniciando o Slim para rotas
$app = new Slim();

//Mostra os erros caso ocorra
$app->config('debug', true);

//Route padrão
$app->get('/', function() {

	//Chamando a classe page() sem passar parâmetros (Vai configurar um array vazio)
	$page = new Page();

	//Desenhando o nosso index
	$page->setTpl("index");

});

//Executa
$app->run();

 ?>