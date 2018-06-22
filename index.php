<?php 

//								 Configurações bases do Index 				
	
	session_start();

	//Require dos arquivos composer
	require_once("vendor/autoload.php");

	//Usando o Slim framework para routes
	use \Slim\Slim;

	//Usando a classe Page para gerar o template completo HTML (Header, index, footer)
	use \Hcode\Page;
	use \Hcode\PageAdmin;

	//Classe de usuário
	use \Hcode\Model\User;

	//Classe de categorías
	use \Hcode\Model\Category;

	//Classe de produtos
	use \Hcode\Model\Products;

	/* 
		Algumas classes e trechos de códigos possuem comentários em inglês 
		enquanto outras não. Durante o progresso do projeto tive por preferência 
		tentar treinar o meu inglês :)
	*/

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

//Users screen
$app->get("/admin/users", function(){

	//Check user login
	User::verifyLogin();

	//All list of users selected from the database
	$users = User::listAll();

	$page = new PageAdmin();
	
	//Draw the users page
	$page->setTpl("users", array(
		"users"=>$users
	));

});

//Creation route
$app->get("/admin/users/create", function(){

	User::verifyLogin();
	
	$page = new PageAdmin();
	
	$page->setTpl("users-create");

});

//Users delete
$app->get("/admin/users/:iduser/delete", function($iduser){
	
	User::verifyLogin();
	
	$user = new User();

	$user->get((int)$iduser);

	$user->delete();
	
	header("Location: /admin/users");
	exit;

});

//Users list
$app->get("/admin/users/:iduser", function($iduser){

	User::verifyLogin();
	
	$user = new User();

	$user->get((int)$iduser);

	$page = new PageAdmin();

	$page->setTpl("users-update", array(
		"user"=>$user->getValues()
	));

});

//This route will send a post to another route
$app->post("/admin/users/create", function(){
	
	User::verifyLogin();
	
	$user = new User();
	
	$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;
	
	$user->setData($_POST);
	
	$user->save();
	
	header("Location: /admin/users");
	
	exit;

});

//This route will send a post to another route too
$app->post("/admin/users/:iduser", function($iduser){
	
	User::verifyLogin();

	$user = new User();
	
	$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;

	$user->get((int)$iduser);

	$user->setData($_POST);

	$user->update();

	header("Location: /admin/users");

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

//Categories
$app->get("/admin/categories", function(){
	
	User::verifyLogin();

	$categories = Category::listAll();

	$page = new PageAdmin();
	
	$page->setTpl("categories", array(
		"categories"=>$categories
	));

});

//Create a new category
$app->get("/admin/categories/create", function(){
	
	User::verifyLogin();

	$page = new PageAdmin();
	
	$page->setTpl("categories-create");

});

//Receives a post from the route above
$app->post("/admin/categories/create", function(){
	
	User::verifyLogin();

	$category = new Category();

	$category->setData($_POST);
	
	$category->save();
	
	header("Location: /admin/categories");
	
	exit;

});

//Update category
$app->get("/admin/categories/:idcategory", function($idcategory){

	User::verifyLogin();

	$category = new Category();

	$category->get((int) $idcategory);

	$page = new PageAdmin();

	$page->setTpl("categories-update", array(
		"category"=>$category->getValues()
	));

});

//Save
$app->post("/admin/categories/:idcategory", function($idcategory){

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$category->setData($_POST);

	$category->save();

	header("Location: /admin/categories");
	
	exit;

});


//Delete category
$app->get("/admin/categories/:idcategory/delete", function($idcategory){

	User::verifyLogin();

	$category = new Category();
	
	$category->get((int) $idcategory);

	$category->delete();

	header("Location: /admin/categories");

	exit;

});

//Take a category according to ID
$app->get("/categories/:idcategory", function($idcategory){

	$category = new Category();

	$category->get((int)$idcategory);

	$page = new Page();

	$page->setTpl("category", array(
		"category"=>$category->getValues(),
		"products"=>[]
	));

});

$app->get("/admin/products", function(){

	User::verifyLogin();

	$products = new Products();

	$products = Products::read();

	$page = new PageAdmin();
	
	$page->setTpl("products", array(
		"products"=>$products
	));

});

$app->get("/admin/products/create", function(){

	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("products-create");

});

$app->post("/admin/products/create", function(){

	User::verifyLogin();

	$product = new Products();

	$product->setData($_POST);

	$product->create();

	Header("Location: /admin/products");

	exit;

});

$app->get("/admin/products/:idproduct", function($idproduct){

	User::verifyLogin();

	$product = new Products();

	$product->getProduct((int) $idproduct);

	$page = new PageAdmin();

	$page->setTpl("products-update", array(

		"product"=>$product->getValues()
	
	));

});

$app->post("/admin/products/:idproduct", function($idproduct){

	$product = new Products();

	$product->getProduct((int)$idproduct);

	$product->setData($_POST);

	$product->update();

	if($_FILES["file"]["name"] !== "")
	{
		$product->setPhoto($_FILES["file"]);
	}

	Header("Location: /admin/products");
	
	exit;

});

$app->get("/admin/products/:idproduct/delete", function($idproduct){

	$product = new Products();

	$product->getProduct((int)$idproduct);

	$product->delete();

	Header("Location: /admin/products");
	
	exit;
	
});


//Execute all routes
$app->run();

 ?>