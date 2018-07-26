<?php 

namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;
use \Hcode\Mailer;

class Products extends Model
{


    public static function read()
    {

        $sql = new Sql();
        
        //Command "Join" is used in this line below
        return $sql->select("SELECT * FROM tb_products ORDER BY idproduct");

    }

    public static function checkList($list)
    {

        foreach ($list as &$row) {

            $product = new Products();
            $product->setData($row);
            $row = $product->getValues();

        }

        return $list;

    }

    public function create()
    {
        $sql = new Sql;

        $results = $sql->select("CALL sp_products_save(:idproduct, :desproduct, :vlprice, :vlwidth, :vlheight, :vllength, :vlweight, :desurl)", array(
            ":idproduct"=>$this->getidproduct(),
            ":desproduct"=>$this->getdesproduct(),
            ":vlprice"=>$this->getvlprice(),
            ":vlwidth"=>$this->getvlwidth(),
            ":vlheight"=>$this->getvlheight(),
            ":vllength"=>$this->getvllength(),
            ":vlweight"=>$this->getvlweight(),
            ":desurl"=>$this->getdesurl()
        ));

    }

    public function update()
    {
        
        $sql = new Sql;

        $results = $sql->select("CALL sp_products_save(:idproduct, :desproduct, :vlprice, :vlwidth, :vlheight, :vllength, :vlweight, :desurl)", array(
            ":idproduct"=>$this->getidproduct(),
            ":desproduct"=>$this->getdesproduct(),
            ":vlprice"=>$this->getvlprice(),
            ":vlwidth"=>$this->getvlwidth(),
            ":vlheight"=>$this->getvlheight(),
            ":vllength"=>$this->getvllength(),
            ":vlweight"=>$this->getvlweight(),
            ":desurl"=>$this->getdesurl()
        ));

        $this->setData($results[0]);

    }

    public function delete()
    {
        $sql = new Sql;

        $sql->query("DELETE FROM tb_products WHERE idproduct = :idproduct", array(
            ":idproduct"=>$this->getidproduct()
        ));

    }

    public function getProduct($idproduct)
    {
        $sql = new Sql();
        
        //Command "Join" is used in this line below
        $results = $sql->select("SELECT * FROM tb_products WHERE idproduct = :idproduct", array(
            ":idproduct"=>$idproduct
        ));

        $this->setData($results[0]);
        
    }

    public function checkPhoto()
    {

        if(file_exists($_SERVER["DOCUMENT_ROOT"] . DIRECTORY_SEPARATOR . "res" . DIRECTORY_SEPARATOR . "site" . DIRECTORY_SEPARATOR . "img" . DIRECTORY_SEPARATOR . "products" . DIRECTORY_SEPARATOR . $this->getidproduct() . ".jpg"))
        {
           $url = "/res/site/img/products/" . $this->getidproduct() . ".jpg";
        } 
        else 
        {
            
            $url = "/res/site/img/products.jpg";

        }

        $this->setdesphoto($url);

    }

    public function getValues()
    {
        $this->checkPhoto();

        $values = parent::getValues();
    
        return $values;

    }

    public function setPhoto($file)
    {

        //Explode vai separar em Array onde tiver ponto
        $extension = explode('.', $file["name"]);
        
        //End vai pegar o último dado do array
        $extension = end($extension);

        switch($extension)
        {
            case "jpg":
            case "jpeg":
                //O índice do array $file é o nome do arquivo temporário que está no servidor
                $image = imagecreatefromjpeg($file["tmp_name"]);
                break;

            case "gif":
                $image = imagecreatefromgif($file["tmp_name"]);
                break;

            case "png":
                $image = imagecreatefrompng($file["tmp_name"]);
                break;
        
        }

        $dist = $_SERVER["DOCUMENT_ROOT"] . DIRECTORY_SEPARATOR . "res" . DIRECTORY_SEPARATOR . "site" . DIRECTORY_SEPARATOR . "img" . DIRECTORY_SEPARATOR . "products" . DIRECTORY_SEPARATOR . $this->getidproduct() . ".jpg";

        imagejpeg($image, $dist);
        imagedestroy($image);

        $this->checkPhoto();

    }

    public function getFromURL($desurl)
    {
        $sql = new Sql();

        $rows = $sql->select("SELECT * FROM tb_products WHERE desurl = :desurl LIMIT 1", array(
            ":desurl"=>$desurl
        ));

        $this->setData($rows[0]);

    }

    public function getCategories()
    {
        $sql = new Sql();

        return $sql->select("SELECT * FROM tb_categories a INNER JOIN tb_categoriesproducts b ON a.idcategory = b.idcategory WHERE b.idproduct = :idproduct", array(
            ":idproduct"=>$this->getidproduct()
        ));
        
    }

    public static function getPage($page = 1, $itemsPerPage = 10)
    {

        //Cálculo para colocarmos no LIMIT de nosso select no banco
        $start = ($page - 1) * $itemsPerPage;

        $sql = new Sql();

        //Perceba a variável no ínicio de nosso LIMIT
        $results = $sql->select("SELECT  SQL_CALC_FOUND_ROWS *
        FROM tb_products 
        ORDER BY idproduct
        LIMIT $start, $itemsPerPage;");

        //Contagem de elementos do nosso resultado
        $resultTotal = $sql->select("SELECT FOUND_ROWS() AS nrtotal;");

        return array(
            'data'=>$results,
            'total'=>(int)$resultTotal[0]['nrtotal'],
            'pages'=>ceil($resultTotal[0]['nrtotal'] / $itemsPerPage)
        );
        
    }

    public static function getPageSearch($search, $page = 1, $itemsPerPage = 10)
    {
        //Cálculo para colocarmos no LIMIT de nosso select no banco
        $start = ($page - 1) * $itemsPerPage;

        $sql = new Sql();

        //Perceba a variável no ínicio de nosso LIMIT
        $results = $sql->select("SELECT  SQL_CALC_FOUND_ROWS *
        FROM tb_products 
        WHERE desproduct LIKE :search
        ORDER BY idproduct
        LIMIT $start, $itemsPerPage;", [
            ':search'=> '%' . $search . '%'
        ]);

        //Contagem de elementos do nosso resultado
        $resultTotal = $sql->select("SELECT FOUND_ROWS() AS nrtotal;");

        return array(
            'data'=>$results,
            'total'=>(int)$resultTotal[0]['nrtotal'],
            'pages'=>ceil($resultTotal[0]['nrtotal'] / $itemsPerPage)
        );
    }


}