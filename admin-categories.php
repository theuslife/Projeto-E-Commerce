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

