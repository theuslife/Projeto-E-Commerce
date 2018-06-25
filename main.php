<?php

//Usando o Slim framework para routes
use \Slim\Slim;
use \Hcode\Page;
use \Hcode\Model\Category;
use \Hcode\Model\Products;

//Route padrão
$app->get('/', function() {

	$products = Products::read();

	//Chamando a classe page() sem passar parâmetros (Vai configurar um array vazio)
	$page = new Page();

	//Desenhando o nosso index
	$page->setTpl("index", array(
		"products"=>Products::checkList($products)
	));

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