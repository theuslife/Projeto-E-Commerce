<?php

//Usando o Slim framework para routes
use \Slim\Slim;
use \Hcode\Page;
use \Hcode\Model\Category;
use \Hcode\Model\Products;
use \Hcode\Model\Cart;
use \Hcode\Model\Address;
use \Hcode\Model\User;
use \Hcode\Model\Order;
use \Hcode\Model\OrderStatus;

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
	
	$address = new Address();

	$cart = Cart::getFromSession();

	if(isset($_GET['zipcode']))
	{

		//Pega o cep do menu do carrinho
		if($_GET['zipcode'] == '')
		{
			$_GET['zipcode'] = $cart->getdeszipcode();
		}

		$address->loadFromCep($_GET['zipcode']);
		
		$cart->setdeszipcode($_GET['zipcode']);

		$cart->save();

		$cart->getCalculateTotal();
		
	}

	if (!$address->getdesaddress()) $address->setdesaddress('');
	if (!$address->getdesnumber()) $address->setdesnumber('');
	if (!$address->getdescomplement()) $address->setdescomplement('');
	if (!$address->getdesdistrict()) $address->setdesdistrict('');
	if (!$address->getdescity()) $address->setdescity('');
	if (!$address->getdesstate()) $address->setdesstate('');
	if (!$address->getdescountry()) $address->setdescountry('');
	if (!$address->getdeszipcode()) $address->setdeszipcode('');

	$page = new Page();

	$page->setTpl("checkout", [
		"cart"=>$cart->getValues(),
		"address"=>$address->getValues(),
		'products'=>$cart->getProducts(),
		'error'=>Address::getMsgError()
	]);

});

$app->post('/checkout', function(){

	User::verifyLogin(false);
	
	//Validações
	if(!isset($_POST['zipcode']) || $_POST['zipcode'] === '')
	{
		Address::setMsgError('Digite o seu CEP');
		header('Location: /checkout');
		exit;
	}

	if(!isset($_POST['desaddress']) || $_POST['desaddress'] === '')
	{
		Address::setMsgError('Digite o seu endereço');
		header('Location: /checkout');
		exit;
	}

	if(!isset($_POST['desdistrict']) || $_POST['desdistrict'] === '')
	{
		Address::setMsgError('Digite a sua cidade');
		header('Location: /checkout');
		exit;
	}

	if(!isset($_POST['descity']) || $_POST['descity'] === '')
	{
		Address::setMsgError('Digite a sua cidade');
		header('Location: /checkout');
		exit;
	}

	if(!isset($_POST['desstate']) || $_POST['desstate'] === '')
	{
		Address::setMsgError('Digite o seu estado');
		header('Location: /checkout');
		exit;
	}

	if(!isset($_POST['descountry']) || $_POST['descountry'] === '')
	{
		Address::setMsgError('Digite o seu país');
		header('Location: /checkout');
		exit;
	}

	$user = User::getFromSession();
	
	$cart = Cart::getFromSession();

	$cart->getCalculateTotal();

	//Endereço
	$_POST['deszipcode'] = $_POST['zipcode'];
	$_POST['idperson'] = $user->getidperson();

	$address = new Address();

	$address->setData($_POST);

	$address->save();

	//Order
	$order = new Order();

	$order->setData([
		'idcart'=>$cart->getidcart(),
		'idaddress'=>$address->getidaddress(),
		'iduser'=>$user->getiduser(),
		'idstatus'=>OrderStatus::EM_ABERTO,
		'vltotal'=>$cart->getvltotal()
	]);

	$order->save();

	header("Location: /order/".$order->getidorder() . "/pagseguro");
	exit;

});

$app->get("/order/:idorder/pagseguro", function($idorder){

	$order = new Order();

	$order->get((int)$idorder);

	$cart = $order->getCart();

	$page = new Page([
		'header'=>false,
		'footer'=>false
	]);

	$page->setTpl("payment-pagseguro", [
		'order'=>$order->getValues(),
		'cart'=>$cart->getValues(),
		'products'=>$cart->getProducts(),
		'phone'=>[
			'areaCode'=>substr($order->getnrphone(), 0, 2),
			'number'=>substr($order->getnrphone(), 2, strlen($order->getnrphone()))
		]
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

	header('Location: /login');
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

$app->get('/order/:idorder',function($idorder){

	User::verifyLogin(false);

	$order = new Order();

	$order->get((int) $idorder);
	
	$page = new Page();

	$page->setTpl('payment', [
		'order'=>$order->getValues()
	]);

});

$app->get('/boleto/:idorder', function($idorder){

	User::verifyLogin(false);

	$order = new Order();

	$order->get((int) $idorder);

	// DADOS DO BOLETO PARA O SEU CLIENTE
	$dias_de_prazo_para_pagamento = 10;
	$taxa_boleto = 5.00;
	$data_venc = date("d/m/Y", time() + ($dias_de_prazo_para_pagamento * 86400));// Prazo de X dias OU informe data: "13/04/2006"; 
	$valor_cobrado = formatPrice($order->getvltotal()); // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
	$valor_cobrado = str_replace(".", "", $valor_cobrado);
	$valor_cobrado = str_replace(",", ".", $valor_cobrado);
	$valor_boleto=number_format($valor_cobrado+$taxa_boleto, 2, ',', '');

	$dadosboleto["nosso_numero"] = $order->getidorder();  // Nosso numero - REGRA: Máximo de 8 caracteres!
	$dadosboleto["numero_documento"] = $order->getidorder();	// Num do pedido ou nosso numero
	$dadosboleto["data_vencimento"] = $data_venc; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
	$dadosboleto["data_documento"] = date("d/m/Y"); // Data de emissão do Boleto
	$dadosboleto["data_processamento"] = date("d/m/Y"); // Data de processamento do boleto (opcional)
	$dadosboleto["valor_boleto"] = $valor_boleto; 	// Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula

	// DADOS DO SEU CLIENTE
	$dadosboleto["sacado"] = $order->getdesperson();
	$dadosboleto["endereco1"] = $order->getdesaddress() . " - " . $order->getdistrict();
	$dadosboleto["endereco2"] = $order->getdescity() . " - " . $order->getdesstate(). " - " . $order->getdescountry() . " - CEP: " . $order->getdeszipcode();

	// INFORMACOES PARA O CLIENTE
	$dadosboleto["demonstrativo1"] = "Pagamento de Compra na Loja Hcode E-commerce";
	$dadosboleto["demonstrativo2"] = "Taxa bancária - R$ 0,00";
	$dadosboleto["demonstrativo3"] = "";
	$dadosboleto["instrucoes1"] = "- Sr. Caixa, cobrar multa de 2% após o vencimento";
	$dadosboleto["instrucoes2"] = "- Receber até 10 dias após o vencimento";
	$dadosboleto["instrucoes3"] = "- Em caso de dúvidas entre em contato conosco: suporte@hcode.com.br";
	$dadosboleto["instrucoes4"] = "&nbsp; Emitido pelo sistema Projeto Loja Hcode E-commerce - www.hcode.com.br";

	// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
	$dadosboleto["quantidade"] = "";
	$dadosboleto["valor_unitario"] = "";
	$dadosboleto["aceite"] = "";		
	$dadosboleto["especie"] = "R$";
	$dadosboleto["especie_doc"] = "";


	// ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //


	// DADOS DA SUA CONTA - ITAÚ
	$dadosboleto["agencia"] = "1690"; // Num da agencia, sem digito
	$dadosboleto["conta"] = "48781";	// Num da conta, sem digito
	$dadosboleto["conta_dv"] = "2"; 	// Digito do Num da conta

	// DADOS PERSONALIZADOS - ITAÚ
	$dadosboleto["carteira"] = "175";  // Código da Carteira: pode ser 175, 174, 104, 109, 178, ou 157

	// SEUS DADOS
	$dadosboleto["identificacao"] = "Hcode Treinamentos";
	$dadosboleto["cpf_cnpj"] = "24.700.731/0001-08";
	$dadosboleto["endereco"] = "Rua Ademar Saraiva Leão, 234 - Alvarenga, 09853-120";
	$dadosboleto["cidade_uf"] = "São Bernardo do Campo - SP";
	$dadosboleto["cedente"] = "HCODE TREINAMENTOS LTDA - ME";

	// NÃO ALTERAR!]
	
	$path = $_SERVER["DOCUMENT_ROOT"] . DIRECTORY_SEPARATOR . "res" . DIRECTORY_SEPARATOR . "boletophp" . DIRECTORY_SEPARATOR . "include" . DIRECTORY_SEPARATOR;
	
	require_once($path . "funcoes_itau.php");
	require_once($path . "layout_itau.php");

});

$app->get('/profile/orders', function(){

	User::verifyLogin(false);

	$user = User::getFromSession();

	$page = new Page();
	
	$page->setTpl('profile-orders', [
		'orders'=>$user->getOrders()
	]);

});

$app->get("/profile/orders/:idorder", function($idorder){

	User::verifyLogin(false);

	$order = new Order();

	$order->get((int) $idorder);

	$cart = new Cart();

	$cart->get((int) $order->getidcart());

	$cart->getCalculateTotal();

	$page = new Page();
	
	$page->setTpl("profile-orders-detail", [
		'order'=>$order->getValues(),
		'cart'=>$cart->getValues(),
		'products'=>$cart->getProducts()
	]);

});

$app->get("/profile/change-password", function(){

	User::verifyLogin(false);

	$page = new Page();

	$page->setTpl("profile-change-password", [
		'changePassError'=>User::getErrorRegister(),
		'changePassSuccess'=>User::getSucessRegister()
	]);

});

$app->post('/profile/change-password', function(){

	User::verifyLogin(false);

	//Validações
	if (!isset($_POST['current_pass']) || $_POST['current_pass'] === '')
	{
		User::setErrorRegister("Digite sua senha atual.");
		Header("Location: /profile/change-password");
		exit;
	}

	if (!isset($_POST['new_pass']) || $_POST['new_pass'] === '')
	{
		User::setErrorRegister("Digite sua nova senha.");
		Header("Location: /profile/change-password");
		exit;
	}

	if (!isset($_POST['new_pass_confirm']) || $_POST['new_pass_confirm'] === '')
	{
		User::setErrorRegister("Digite sua confirmação de senha");
		Header("Location: /profile/change-password");
		exit;
	}

	if($_POST['current_pass'] === $_POST['new_pass'])
	{
		User::setErrorRegister("Sua nova senha não pode ser a mesma que a atual");
		Header("Location: /profile/change-password");
		exit;
	}

	if($_POST['new_pass'] !== $_POST['new_pass_confirm'])
	{
		User::setErrorRegister("Confirmação de senha diferente");
		Header("Location: /profile/change-password");
		exit;
	}

	$user = User::getFromSession();

	if(!password_verify($_POST['current_pass'], $user->getdespassword()))
	{
		User::setErrorRegister("Senha atual foi digitada incorretamente");
		Header("Location: /profile/change-password");
		exit;
	}

	//Nova senha acrescentada
	$user->setdespassword($_POST['new_pass']);

	//Update realizado
	$user->update();

	User::setSucessRegister("Senha alterada com sucesso");
	Header("Location: /profile/change-password");
	exit;


});