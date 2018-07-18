<?php

//Usando o Slim framework para routes
use \Slim\Slim;
use \Hcode\Page;
use \Hcode\Model\Category;
use \Hcode\Model\Products;
use \Hcode\Model\Cart;
use \Hcode\Model\Address;
use \Hcode\Model\User;

//Rota padrão
$app->get('/', function() {

	$products = Products::read();

	$page = new Page();

	//Desenhando o nosso html
	$page->setTpl("index", array(
		"products"=>Products::checkList($products)
	));

});

//Take a category according to ID
$app->get("/categories/:idcategory", function($idcategory){

	//Page é a variável que indica onde está a nossa atual paginação
	$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;

	$category = new Category();

	//Vai pegar os dados dessa categoria
	$category->get((int)$idcategory);

	$pagination = $category->getProductsPage($page);

	$pages = [];

	for ($i=1; $i <= $pagination['pages'] ; $i++) { 
		array_push($pages, array(
			'link'=>"/categories/" . $category->getidcategory() . '?page=' . $i,
			'page'=>$i
		));
	}

	$page = new Page();

	$page->setTpl("category", array(
		"category"=>$category->getValues(),
		"products"=>$pagination["data"],
		"pages"=>$pages
	));

});

$app->get("/products/:desurl", function($desurl){

	$product = new Products();

	$product->getFromURL($desurl);

	$page = new Page();

	$page->setTpl("product-detail", array(
		"product"=>$product->getValues(),
		"categories"=>$product->getCategories()
	));


});

$app->get("/cart", function(){

	$cart = Cart::getFromSession();

	$page = new Page();

	$page->setTpl("cart", array(

		"cart"=>$cart->getValues(),

		"products"=>$cart->getProducts(),

		'error'=>Cart::getMsgError()

	));


});

$app->get("/cart/:idproduct/add/", function($idproduct){

	$product = new Products();

	$product->getProduct((int)$idproduct);

	$cart = Cart::getFromSession();

	$qtd = (isset($_GET['qtd'])) ? (int)$_GET['qtd']:1;

	for ($i=0; $i < $qtd; $i++) 
	{ 

		$cart->addProduct($product);

	}

	header("Location: /cart");

	exit;

});

$app->get("/cart/:idproduct/minus/", function($idproduct){

	$product = new Products();

	$product->getProduct((int)$idproduct);

	$cart = Cart::getFromSession();

	$cart->removeProduct($product);

	header("Location: /cart");

	exit;

});

$app->get("/cart/:idproduct/remove/", function($idproduct){

	$product = new Products();

	$product->getProduct((int)$idproduct);

	$cart = Cart::getFromSession();

	$cart->removeProduct($product,true);

	header("Location: /cart");

	exit;

});

$app->post("/cart/freight", function(){

	$cart = Cart::getFromSession();

	$cart->setFreight($_POST['zipcode']);

	header("Location: /cart");
	
	exit;

});

$app->get("/checkout", function(){

	User::verifyLogin(false);

	$cart = Cart::getFromSession();

	$address = new Address();
	
	$page = new Page();

	$page->setTpl('checkout', [

		'cart'=>$cart->getValues(),
		'address'=>$address->getValues()

	]);

});

$app->get('/login', function(){

	$page = new Page();
	$page->setTpl('login', [
		'error'=>User::getMsgError(),
		'errorRegister'=>User::getErrorRegister(),
		'registerValues'=>(isset($_SESSION['registerValues']))?$_SESSION['registerValues']:['name'=>'', 'email'=>'', 'phone'=>'', 'password'=>'']
	]);

});

$app->post('/login', function(){

	try
	{
		User::login($_POST['login'], $_POST['password']);
	}
	catch (Exception $e)
	{
		User::setMsgError($e->getMessage());
	}

	header('Location: /checkout');
	exit;

});

$app->get('/logout', function(){

	User::logout();

	header('Location: /');
	exit;

});

$app->post('/register', function(){

	$_SESSION['registerValues'] = $_POST;

	//Validações
	if(!isset($_POST['name']) || $_POST['name'] == '')
	{
		User::setErrorRegister('Nome inválido, preencha o campo novamente');
		Header('Location: /login');
		exit;
	}

	if(!isset($_POST['email']) || $_POST['email'] == '')
	{
		User::setErrorRegister('Email inválido, preencha o campo novamente');
		Header('Location: /login');
		exit;
	}

	if(!isset($_POST['password']) || $_POST['password'] == '')
	{
		User::setErrorRegister('Senha não preenchida. Preencha o campo novamente');
		Header('Location: /login');
		exit;
	}

	if(User::checkLoginExists($_POST['email']) === true)
	{
		User::setErrorRegister('Login já sendo utilizado. Tente novamente');
		Header('Location: /login');
		exit;
	}

	$user = new User();

	$user->setData([

		'inadmin'=>0,
		'desperson'=>$_POST['name'],
		'deslogin'=>$_POST['email'],
		'desemail'=>$_POST['email'],
		'despassword'=>$_POST['password'],
		'nrphone'=>$_POST['phone']

	]);

	$user->save();

	User::login($_POST['email'], $_POST['password']);

	Header('Location: /checkout');
	exit;
});

$app->get('/profile', function(){

	User::verifyLogin(false);

	$user = User::getFromSession();

	$page = new Page();

	$page->setTpl('profile', [
		'user'=>$user->getValues(),
		'profileMsg'=>User::getSucessRegister(),
		'profileError'=>User::getErrorRegister()
	]);

});

$app->post('/profile', function(){

	User::verifyLogin(false);

	$user = User::getFromSession();

	//Validações
	if(!isset($_POST['desperson']) || $_POST['desperson'] === '')
	{
		User::setErrorRegister('Preencha seu nome.');
		Header('Location: /profile');
		exit;
	} 
	if(!isset($_POST['desemail']) || $_POST['desemail'] === '')
	{
		User::setErrorRegister('Preencha seu email');
		Header('Location: /profile');
		exit;
	}
	if($_POST['desemail'] !== $user->getdesemail())
	{
		if(User::checkLoginExists($_POST['desemail']))
		{
			User::setErrorRegister('Este endereço de E-mail já está cadastrado');
			Header('Location: /profile');
			exit;
		}
	}

	$_POST['inadmin'] = $user->getinadmin();
	$_POST['despassword'] = $user->getdespassword();
	$_POST['deslogin'] = $_POST['desemail'];

	$user->setData($_POST);
	$user->save();
	User::setSucessRegister('Dados alterados com sucesso!');

	Header('Location: /profile');
	exit;

});