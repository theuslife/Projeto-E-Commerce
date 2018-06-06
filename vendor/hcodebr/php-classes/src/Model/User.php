<?php

namespace Hcode\Model;
use \Hcode\DB\Sql;
use \Hcode\Model;

class User extends Model
{

    const SESSION = "User";

    //Login
    public static function login($login, $password)
    {

        $sql = new Sql();
        
        $results = $sql->select("SELECT * FROM tb_users WHERE deslogin = :LOGINN", array(
            ":LOGINN"=>$login
        ));
        
        if(count($results) === 0 )
        {
            throw new \Exception("Usuário inexistente ou senha inválida", 1);
        }
        
        $data = $results[0];
        
        if(password_verify($password, $data["despassword"]) === true)
        {
            
            //Configurando o Login do usuário
            $user = new User();
            $user->setData($data);

            //Sessão para pegar os dados de login do usuário
            $_SESSION[User::SESSION] = $user->getValues();
            return $user;

        } else 
        {
            throw new \Exception("Usuário inexistente ou senha inválida", 1);
        }
    }

    public static function verifyLogin($inadmin = true)
    {
        if(!isset($_SESSION[user::SESSION])  || !$_SESSION[User::SESSION] || !(int)$_SESSION[User::SESSION]["iduser"] > 0 || (bool)$_SESSION[User::SESSION]["inadmin"] !== $inadmin)
        {
            header("Location: /admin/login");
            exit;
        }
    }

    public static function logout()
    {
        $_SESSION[User::SESSION] = NULL;
    }

}

?>