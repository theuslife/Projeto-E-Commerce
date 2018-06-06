<?php 
session_start();
//								 Configurações bases do Index 				
	//Require dos arquivos composer
	require_once("vendor/autoload.php");

	//Usando o Slim framework para routes
	use \Slim\Slim;

	//Usando a classe Page para gerar o template completo HTML (Header, index, footer)
	use \Hcode\Page;
	use \Hcode\PageAdmin;

	//Classe de usuário
	use \Hcode\Model\User;

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

//Route admin
$app->get('/admin', function() {

	//Verificando se o usuário está logado
	User::verifyLogin();

	//Chamando a classe page() sem passar parâmetros (Vai configurar um array vazio)
	$page = new PageAdmin();

	//Desenhando o nosso index
	$page->setTpl("index");

});

$app->get('/admin/login', function(){

	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("login");

});

$app->post('/admin/login', function(){
	
	User::login($_POST["login"], $_POST["password"]);
	header("Location: /admin");
	exit;

});

$app->get('/admin/logout', function(){
	User::logout();
	header("Location: /admin/login");
	exit;
});

//Executa
$app->run();

 ?>