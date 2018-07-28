<?php
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

	$page->setTpl("login", [
		'success'=>User::getSucessRegister()
	]);

});

//Post of login
$app->post('/admin/login', function(){
	
	
	User::login($_POST["login"], $_POST["password"]);
	
	header("Location: /admin/users");
	
	exit;

});

//Logout of users
$app->get('/admin/logout', function(){
	
	User::logout();
	
	header("Location: /admin/login");
	
	exit;
});

//Register a new admin member
$app->get("/admin/register", function(){

	$page = new PageAdmin([
		'header'=>false,
		'footer'=>false
	]);

	$page->setTpl("register", [
		'sucess'=>User::getSucessRegister(),
		'error'=>User::getErrorRegister()
	]);

});

$app->post("/admin/register/add", function(){

	$user = new User();

	//Validações
	User::validationRegister($_POST);

	$user->setData([
		'desperson'=>$_POST['desperson'],
		'deslogin'=>$_POST['deslogin'],
		'nrphone'=>$_POST['nrphone'],
		'desemail'=>$_POST['desemail'],
		'despassword'=>$_POST['despassword'],
		'inadmin'=>$_POST['inadmin']
	]);

	$user->save();

	User::setSucessRegister("Cadastro feito com sucesso");
	Header("Location: /admin/login");
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
