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
        

         //Configurando o Login do usuário
        if($data["despassword"] == $password)
        {
            $user = new User();
            $user->setData($data);
    
            //Sessão para pegar os dados de login do usuário
            $_SESSION[User::SESSION] = $user->getValues();
            return $user;
        } else 
        {
            throw new \Exception("Usuário inexistente ou senha inválida", 1);
        }

           
        /*  Login verificando a senha criptografada
            if(password_verify($password, $data["despassword"]) === true)
            {
                $user = new User();
                $user->setData($data);
        
                //Sessão para pegar os dados de login do usuário
                $_SESSION[User::SESSION] = $user->getValues();
                return $user;
            } else 
            {
                throw new \Exception("Usuário inexistente ou senha inválida", 1);
            }
        */
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

    //List all users
    public static function listAll()
    {

        $sql = new Sql();
        
        //Command "Join" is used in this line below
        return $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) ORDER BY b.desperson");

    }

    //Save the new user
    public function save()
    {
        $sql = new Sql;

        $results = $sql->select("CALL sp_users_save(:desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", array(
            ":desperson"=>$this->getdesperson(),
            ":deslogin"=>$this->getdeslogin(),
            ":despassword"=>$this->getdespassword(),
            ":desemail"=>$this->getdesemail(),
            ":nrphone"=>$this->getnrphone(),
            ":inadmin"=>$this->getinadmin()
        ));
        $this->setData($results[0]);

    }

    public function get($iduser)
    {
        
        $sql = new Sql();

        $results = $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) WHERE a.iduser = :iduser", array(
            ":iduser"=>$iduser
        ));

        $this->setData($results[0]);

    }

    public function update()
    {
        
        $sql = new Sql();

        $results = $sql->select("CALL sp_usersupdate_save(:iduser, :desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", array(
            ":iduser"=>$this->getiduser(),
            ":desperson"=>$this->getdesperson(),
            ":deslogin"=>$this->getdeslogin(),
            ":despassword"=>$this->getdespassword(),
            ":desemail"=>$this->getdesemail(),
            ":nrphone"=>$this->getnrphone(),
            ":inadmin"=>$this->getinadmin()
        ));

        $this->setData($results[0]);

    }

    public function delete()
    {
        
        $sql = new Sql();

        $results = $sql->query("CALL sp_users_delete(:iduser)", array(
            ":iduser"=>$this->getiduser()
        ));

    }

}

?>