<?php

namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;

class Address extends Model 
{

    const SESSION_ERROR = 'AddresError';

    public static function getCep($nrcep)
    {

        $nrcep = str_replace("-", "", $nrcep);

        //Curl iniciado
        $ch = curl_init();

        //Configurações do Curl
        curl_setopt($ch, CURLOPT_URL, "https://viacep.com.br/ws/$nrcep/json/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        //Execução do Curl
        $data = json_decode(curl_exec($ch), true);
        
        //Finalizando ponteiro
        curl_close($ch);

        return $data;

    }

    public function loadFromCep($nrcep)
    {
        $data = Address::getCep($nrcep);

        if(isset($data['logradouro']) && $data['logradouro'])
        {
            $this->setdesaddress($data['logradouro']);
            $this->setdescomplement($data['complemento']);
            $this->setdesdistrict($data['bairro']);
            $this->setdescity($data['localidade']);
            $this->setdesstate($data['uf']);
            $this->setdescountry('Brasil');
            $this->setdeszipcode($nrcep);
        }
        
    }

    public function save()
    {

        $sql = new Sql();

        //Procedimento usado no MySql, para criar um novo usuário caso não exista ou fazer um update caso existente e haja algumas modificações
        $results = $sql->select("CALL sp_addresses_save(:idaddress, :idperson, :desaddress, :descomplement, :descity, :desstate, :descountry, :deszipcode, :desdistrict)", [

            ':idaddress'=>$this->getidaddress(),
            ':idperson'=>$this->getidperson(),
            ':desaddress'=>$this->getdesaddress(),
            ':descomplement'=>$this->getdescomplement(),
            ':descity'=>$this->getdescity(),
            ':desstate'=>$this->getdesstate(),
            ':descountry'=>$this->getdescountry(),
            ':deszipcode'=>$this->getdeszipcode(),
            ':desdistrict'=>$this->getdesdistrict()

        ]);

        //Gere os dados para o objeto
        if(count($results) > 0)
        {   
            return $this->setData($results[0]);
        }

    }

    public static function setMsgError($msg)
    {

        $_SESSION[Address::SESSION_ERROR] = $msg;

    }

    public static function getMsgError()
    {

        $msg = (isset($_SESSION[Address::SESSION_ERROR])) ? $_SESSION[Address::SESSION_ERROR]:'';

        Address::clearMsgError();

        return $msg;

    }

    public static function clearMsgError()
    {

        $_SESSION[Address::SESSION_ERROR] = NULL;

    }
    

}