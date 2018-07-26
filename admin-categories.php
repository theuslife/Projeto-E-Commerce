<?php

//Usando a classe Page para gerar o template completo HTML (Header, index, footer)
use \Hcode\PageAdmin;

//Classe de categorias
use \Hcode\Model\Category;
use \Hcode\Model\Products;
use \Hcode\Model\User;

//Categories
$app->get("/admin/categories", function(){
	
	User::verifyLogin();

	//Pesquisa
	$search = (isset($_GET['search']))? $_GET['search'] : ""; 

	//Página atual
	$page = (isset($_GET['page'])) ? (int) $_GET['page'] : 1;

	//Pesquisa e paginação
	if($search != '')
	{
		$pagination = Category::getPageSearch($search, $page, 10);
	}
	else 
	{
		$pagination = Category::getPage($page, 10);
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
	
	$page->setTpl("categories", array(
		"categories"=>$pagination['data'],
		"search"=>$search,
		"pages"=>$pages
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

$app->get("/admin/categories/:idcategory/products", function($idcategory){

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$page = new PageAdmin();

	$page->setTpl("categories-products", array(
		"category"=>$category->getValues(),
		"productsRelated"=>$category->getProducts(),
		"productsNotRelated"=>$category->getProducts(false)
	));

});

$app->get("/admin/categories/:idcategory/products/:idproduct/add", function($idcategory, $idproduct){

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$product = new Products();

	$product->getProduct((int)$idproduct);

	$category->addProduct($product);

	header("Location: /admin/categories/" .$idcategory. "/products");

	exit;

});

$app->get("/admin/categories/:idcategory/products/:idproduct/remove", function($idcategory, $idproduct){

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$product = new Products();

	$product->getProduct((int)$idproduct);

	$category->removeProduct($product);

	header("Location: /admin/categories/" .$idcategory. "/products");

	exit;

});

