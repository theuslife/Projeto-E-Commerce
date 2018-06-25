<?php

//Usando o Slim framework para routes
use \Slim\Slim;
//Usando a classe Page para gerar o template completo HTML (Header, index, footer)
use \Hcode\PageAdmin;
use \Hcode\Model\User;


//Route admin
$app->get('/admin', function() {

	//Verificando se o usuário está logado
	User::verifyLogin();

	//Chamando a classe page() sem passar parâmetros (Vai configurar um array vazio)
	$page = new PageAdmin();

	//Desenhando o nosso index
	$page->setTpl("index");

});

//Página de login para a rota de administração
$app->get('/admin/login', function(){

	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("login");

});

//Post of login
$app->post('/admin/login', function(){
	
	
	User::login($_POST["login"], $_POST["password"]);
	
	header("Location: /admin");
	
	exit;

});

//Logout of users
$app->get('/admin/logout', function(){
	
	User::logout();
	
	header("Location: /admin/login");
	
	exit;
});

//If user forgets the password
$app->get("/admin/forgot", function(){

	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("forgot");
});

//Post
$app->post("/admin/forgot", function(){

	$user = User::getForgot($_POST["email"]);

	header("Location: /admin/forgot/sent");
	exit;

});

//if user send a email for recovery, from post above
$app->get("/admin/forgot/sent", function(){
	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("forgot-sent");

});

//New User Password 
$app->get("/admin/forgot/reset", function(){

	$user = User::validForgotDecrypt($_GET["code"]);

	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("forgot-reset", array(
		"name"=>$user["desperson"],
		"code"=>$_GET["code"]
	));

});

//Success in password exchange
$app->post("admin/forgot/reset", function(){
	
	$forgot = User::validForgotDecrypt($_POST["code"]);

	User::setForgotUsed($forgot["idrecovery"]);

	$user = new User();

	$user->get((int)$forgot["iduser"]);
	
	password_hash($_POST["password"], PASSWORD_DEFAULT, [
		"cost"=>12
	]);

	$user->setPassword($_POST["password"]);

	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("forgot-reset-sucess");

});
