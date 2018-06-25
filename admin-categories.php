<?php

//Usando o Slim framework para routes
use \Slim\Slim;

//Usando a classe Page para gerar o template completo HTML (Header, index, footer)
use \Hcode\PageAdmin;

//Classe de categorias
use \Hcode\Model\Category;
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
