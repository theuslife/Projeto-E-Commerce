<?php

//Usando o Slim framework para routes
use \Slim\Slim;

//Usando a classe Page para gerar o template completo HTML (Header, index, footer)
use \Hcode\PageAdmin;

//Classe de produtos
use \Hcode\Model\Products;
use \Hcode\Model\User;

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
