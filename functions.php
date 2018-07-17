<?php 

use \Hcode\Model\User;

function formatPrice($vlprice)
{

    return number_format($vlprice, 2, "," , ".");

}

function getUserName()
{

    $user = User::getFromSession();
    return $user->getdesperson();

}

function checkLogin($inadmin = true)
{

    return User::checkLogin($inadmin);

}

?>