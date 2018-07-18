<?php

namespace Hcode\Model;
use \Hcode\DB\Sql;
use \Hcode\Model;
use \Hcode\Mailer;

class User extends Model
{

    const SESSION = "User";
    const SESSION_ERROR = "UserError";
    const SECRET = "HcodePhp7_Secret";
    const SESSION_REGISTER = "UserErrorRegister";

    public static function getFromSession()
    {   
        
        $user = new User();

        if (isset($_SESSION[user::SESSION]) && (int)$_SESSION[User::SESSION]['iduser'] > 0)
        {
            $user->setData($_SESSION[User::SESSION]);
        }

        return $user;

    }


    public static function checkLogin($inadmin = true)
    {

        if (!isset($_SESSION[user::SESSION])  || !$_SESSION[User::SESSION] || !(int)$_SESSION[User::SESSION]["iduser"] > 0)
        {
            return false;
        }
        else 
        {
            if($inadmin === true && (bool)$_SESSION[User::SESSION]['inadmin'] === true)
            {
                return true;
            } 
            else if ($inadmin === false) 
            {
                return true;
            } 
            else 
            {
                return false;
            }
        }

    }

    //Login
    public static function login($login, $password)
    {

        $sql = new Sql();
        
        $results = $sql->select("SELECT * FROM tb_users a 
        INNER JOIN tb_persons b
        WHERE a.idperson = b.idperson
        AND a.deslogin = :LOGINN", array(
            ":LOGINN"=>$login
        ));
        
        if(count($results) === 0 )
        {
            throw new \Exception("Usuário inexistente ou senha inválida", 1);
        }
        
        $data = $results[0];
        
        /*
         //Configurando o Login do usuário
        if($data["despassword"] == $password)
        {
            $user = new User();
            $user->setData($data);
    
            //Sessão para pegar os dados de login do usuário
            $_SESSION[User::SESSION] = $user->getValues();
            return $user;

        } 
        else 
        {
            throw new \Exception("Usuário inexistente ou senha inválida", 1);
        }
        */

        
            if(password_verify($password, $data["despassword"]))
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
     

    }

    //Checks user login
    public static function verifyLogin($inadmin = true)
    {
        if(!User::checkLogin($inadmin))
        {
            if($inadmin)
            {
                header("Location: /admin/login");
                exit;
            } 
            else 
            {
                header("Location: /login");
                exit;
            }
        }
    }

    //User logout
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
            ":despassword"=>password_hash($this->getdespassword(), PASSWORD_DEFAULT),
            ":desemail"=>$this->getdesemail(),
            ":nrphone"=>$this->getnrphone(),
            ":inadmin"=>$this->getinadmin()
        ));
        
        $this->setData($results[0]);

    }

    //Function ''get'' id from user
    public function get($iduser)
    {
        
        $sql = new Sql();

        $results = $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) WHERE a.iduser = :iduser", array(
            ":iduser"=>$iduser
        ));

        $data = $results[0];

        $this->setData($data);

    }

    //User update
    public function update()
    {
        
        $sql = new Sql();

        $results = $sql->select("CALL sp_usersupdate_save(:iduser, :desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", array(
            ":iduser"=>$this->getiduser(),
            ":desperson"=>$this->getdesperson(),
            ":deslogin"=>$this->getdeslogin(),
            ":despassword"=>password_hash($this->getdespassword(), PASSWORD_DEFAULT),
            ":desemail"=>$this->getdesemail(),
            ":nrphone"=>$this->getnrphone(),
            ":inadmin"=>$this->getinadmin()
        ));

        $this->setData($results[0]);

    }

    //User exclusion
    public function delete()
    {
        
        $sql = new Sql();

        $results = $sql->query("CALL sp_users_delete(:iduser)", array(
            ":iduser"=>$this->getiduser()
        ));

    }

    //Get user email
    public static function getForgot($email)
    {

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM tb_users a 
        INNER JOIN tb_persons b 
        USING(idperson) 
        WHERE b.desemail = :email;", array(
           ":email" => $email
        ));

        if(count($results) === 0)
        {
            throw new \Exception("Não foi possível recuperar a senha");
        } 
        else 
        {

            $data = $results[0];

            $recoveryResults = $sql->select("CALL sp_userspasswordsrecoveries_create(:iduser, :desip)", array(
                ":iduser"=>$data["iduser"],
                ":desip"=>$_SERVER["REMOTE_ADDR"]
            ));

            if(count($recoveryResults) === 0)
            {
                throw new \Exception("Não foi possível recuperar a senha");
            } 
            else 
            {
                
                $dataRecovery = $recoveryResults[0];

                $iv = random_bytes(openssl_cipher_iv_length('aes-256-cbc'));
                
                $code = openssl_encrypt($dataRecovery["idrecovery"], 'aes-256-cbc', USER::SECRET, 0, $iv);

                $link = "http://www.e-commerce.com.br/admin/forgot/reset?code=$code";

                $mailer = new Mailer($data["desemail"], $data["desperson"], "Redefinir Senha da Hcode Store", "forgot", array(
                    "name"=>$data["desperson"],
                    "link"=>$link
                ));

                $mailer->send();
                return $data;

            }

        }

    }

    public static function validForgotDecrypt($code)
    {
        
        $code = base64_decode($code);
        
        //Encrypt and Decrypt
        $code = mb_substr($code, openssl_cipher_iv_length('aes-256-cbc'), null, '8bit');
        
        $iv = mb_substr($code, 0, openssl_cipher_iv_length('aes-256-cbc'), '8bit');

        $idrecovery = openssl_decrypt($code, 'aes-256-cbc', User::SECRET, 0, $iv);

        $sql = new Sql;

        $results = $sql->select("SELECT *
        FROM tb_userspasswordsrecoveries a
        INNER JOIN tb_users b USING(iduser)
        INNER JOIN tb_persons c USING(idperson)
        WHERE a.idrecovery = :idrecovery
        AND a.dtrecovery IS NULL
        AND DATE_ADD(a.dtregister, INTERVAL 1 HOUR) >= NOW();", array(
            ":idrecovery"=>$idrecovery
        ));

        if(count($results) === 0)
        {
            throw new \Exception("Não foi possível recuperar a senha");
        } 
        else 
        {
            return $results[0];
        }

    }

    public static function setForgotUsed($idrecovery)
    {
        
        $sql = new Sql;
        $sql->query("UPDATE tb_userspasswordsrecoveries SET dtrecovery = NOW() WHERE idrecovery = :idrecovery", array(
            ":idrecovery"=>$idrecovery
        ));
    
    }

    public function setPassword($password)
    {
        $sql = new Sql();

        $sql->query("UPDATE tb_users SET despassword = :passwordd WHERE iduser = :iduser", array(
            ":passwordd"=>$password,
            ":iduser"=>$this->getiduser()
        ));
    }

    public static function setMsgError($msg)
    {

        $_SESSION[Cart::SESSION_ERROR] = $msg;

    }

    public static function getMsgError()
    {

        $msg = (isset($_SESSION[Cart::SESSION_ERROR])) ? $_SESSION[Cart::SESSION_ERROR]:'';

        User::clearMsgError();

        return $msg;

    }

    public static function clearMsgError()
    {

        $_SESSION[Cart::SESSION_ERROR] = NULL;

    }

    public static function setErrorRegister($msg)
    {

        $_SESSION[User::SESSION_REGISTER] = $msg;

    }

    public static function getErrorRegister()
    {

        $msg = (isset($_SESSION[User::SESSION_REGISTER])) ? $_SESSION[User::SESSION_REGISTER]:'';

        User::clearErrorRegister();

        return $msg;

    }

    public static function clearErrorRegister()
    {

        $_SESSION[User::SESSION_REGISTER] = NULL;

    }

    public static function checkLoginExists($login)
    {
        $sql = new Sql();

        $result = $sql->select("SELECT * FROM tb_users WHERE deslogin = :deslogin", array(
            ':deslogin'=>$login
        ));

        return (count($result) > 0);

    }

}

?>