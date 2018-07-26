<?php
//Usando a classe Page para gerar o template completo HTML (Header, index, footer)
use \Hcode\PageAdmin;

//Classe de produtos
use \Hcode\Model\Products;
use \Hcode\Model\User;

$app->get("/admin/products", function(){

	User::verifyLogin();

	//Pesquisa
	$search = (isset($_GET['search']))? $_GET['search'] : ""; 

	//Página atual
	$page = (isset($_GET['page'])) ? (int) $_GET['page'] : 1;

	//Pesquisa e paginação
	if($search != '')
	{
		$pagination = Products::getPageSearch($search, $page, 10);
	}
	else 
	{
		$pagination = Products::getPage($page, 10);
	}

	$pages = [];

	//Mandando os dados
	for ($i=0; $i < $pagination['pages'] ; $i++)
	{ 
		array_push($pages, [
			'href'=>'/admin/users?' . http_build_query([
				'page'=>$i+1,
				'search'=>$search
			]),
			'text'=>$i+1
		]);
	}
	
	$page = new PageAdmin();
	
	$page->setTpl("products", array(
		"products"=>$pagination['data'],
		"search"=>$search,
		"pages"=>$pages
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
