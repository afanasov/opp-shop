<?php

class Product
{
    const SHOW_BY_DEFAULT = 6;
    
    public static function getLatestProducts($count = self::SHOW_BY_DEFAULT)
    {

        $count = intval($count);
        
        $db = Db::getConnection();
        
        $productsList = array();
        
        $statement = "SELECT id, name, price, image, is_new FROM shop_product WHERE statys = 1 ORDER BY id DESC LIMIT $count";
        
        $result = $db->query($statement);
        
        $i = 0;
        
        while ($row = $result->fetch()){
            $productsList[$i]['id'] = $row['id'];
            $productsList[$i]['name'] = $row['name'];
            $productsList[$i]['price'] = $row['price'];
            $productsList[$i]['image'] = $row['image'];
            $productsList[$i]['is_new'] = $row['is_new'];
            $i++;                        
        }
         
        return $productsList;
    }
    
    public static function getProductsListByCategory($categoryId = false, $page = 1)
    {
        if($categoryId){
            
            $categoryId = intval($categoryId);
            
            $page = intval($page);            
            $offset = ($page - 1) * self::SHOW_BY_DEFAULT;
            
            $db = Db::getConnection();
            
            $products = array();
            
            $statement = "SELECT id, name, price, image, is_new FROM shop_product WHERE statys = 1 AND category_id = $categoryId ORDER BY id DESC LIMIT ".self::SHOW_BY_DEFAULT." OFFSET $offset";
            
            $result = $db->query($statement);
                        
            $i = 0;
            while ($row = $result->fetch()){
                $products[$i]['id'] = $row['id'];
                $products[$i]['name'] = $row['name'];
                $products[$i]['price'] = $row['price'];
                $products[$i]['image'] = $row['image'];
                $products[$i]['is_new'] = $row['is_new'];
                $i++;
            }

            return $products;
            
        }
    }
    
    public static function getProductByIds($id)
    {
//        echo $id; die;
        $id = intval($id);
        
//        echo $id; die;
        if ($id) {                        
            $db = Db::getConnection();
            
            $result = $db->query("SELECT * FROM shop_product WHERE id=$id");
            $result->setFetchMode(PDO::FETCH_ASSOC);
            
            //print_r($result->fetch);die;
            return $result->fetch();
        }
    }
    
    public static function getTotalProductsInCategory($categoryId)
    {
        $db = Db::getConnection();
        
        $statement = "SELECT COUNT(id) AS COUNT FROM shop_product WHERE statys = 1 AND category_id = $categoryId";
        
        $result = $db->query($statement);
        
        $result->setFetchMode(PDO::FETCH_ASSOC);
        
        $row = $result->fetch();
        
        return $row['COUNT'];        
    }
    // по id информацию о продукте
    public static function getProductsById($idsArray)
    {
//        print_r($idsArray);
        $products = array();
        
        $db = Db::getConnection();
        //получаем числа
//        var_dump($idsArray); die;
        $idsString = implode(',', $idsArray);        
//        print_r($idsString);
        $statement = "SELECT id,code,name,price FROM shop_product WHERE statys = 1 AND id IN ($idsString)";
        
        $result = $db->query($statement);
        
        $result->setFetchMode(PDO::FETCH_ASSOC);

        $i = 0;
        while ($row = $result->fetch()){
            $products[$i]['id'] = $row['id'];
            $products[$i]['code'] = $row['code'];
            $products[$i]['name'] = $row['name'];
            $products[$i]['price'] = $row['price'];
            $i++;
        }
//        echo '<pre>';
//        print_r($products);
//        die;
        return $products;
    }
    
    //Метод для карусели в главном меню
     public static function getRecommendedProducts()
    {
        $db = Db::getConnection();

        $productsList = array();

        $result = $db->query("SELECT id, name, price, image, is_new FROM shop_product WHERE statys = 1 AND is_recommended = 1 ORDER BY id DESC");

        $i = 0;
        while ($row = $result->fetch()) {
            $productsList[$i]['id'] = $row['id'];
            $productsList[$i]['name'] = $row['name'];
            $productsList[$i]['image'] = $row['image'];
            $productsList[$i]['price'] = $row['price'];
            $productsList[$i]['is_new'] = $row['is_new'];
            $i++;
        }

        return $productsList;
    }
    public static function getProductsList()
    {
        $db = Db::getConnection();
        
        $statement = "SELECT id,name, price, code FROM shop_product ORDER BY id ASC";
        $result = $db->query($statement);
        
        $productslist = array();
        $i = 0;
        
        while ($row = $result->fetch()){
            $productslist[$i]['id'] = $row['id'];
            $productslist[$i]['name'] = $row['name'];
            $productslist[$i]['price'] = $row['price'];
            $productslist[$i]['code'] = $row['code'];
            
            $i++;
        }
//        echo '<pre>';
//        print_r($productslist);die;
        return $productslist;
    }
    //Удаляем товар по id
    public static function deleteProductById($id)
    {
        $db = Db::getConnection();
        
        $statment = "DELETE FROM shop_product WHERE id = :id";
        
        $result = $db->prepare($statment);
        
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        
        return $result->execute();
    }
    
    public static function createProduct($options)
    {
//        echo '<pre>';
//        print_r($options);
//        die;
        $db = Db::getConnection();
        
        $statement = "INSERT INTO shop_product(name, category_id, code, price, availability, brand,  description, is_new, is_recommended, statys) "
                                    . "VALUES (:name, :category_id, :code, :price, :availability, :brand, :description, :is_new, :is_recommended, :status)";
        $result = $db->prepare($statement);
        
        $result->bindParam(':name', $options['name'], PDO::PARAM_STR);
        $result->bindParam(':category_id', $options['category_id'], PDO::PARAM_INT);
        $result->bindParam(':code', $options['code'], PDO::PARAM_STR);
        $result->bindParam(':price', $options['price'], PDO::PARAM_STR);
        $result->bindParam(':availability', $options['availability'], PDO::PARAM_INT);
        $result->bindParam(':brand', $options['brand'], PDO::PARAM_STR);
        $result->bindParam(':description', $options['description'], PDO::PARAM_STR);
        $result->bindParam(':is_new', $options['is_new'], PDO::PARAM_INT);
        $result->bindParam(':is_recommended', $options['is_recommended'], PDO::PARAM_INT);
        $result->bindParam(':status', $options['status'], PDO::PARAM_INT);        
        
        if($result->execute()){
            return $db->lastInsertId();
        }  else {
            return 0;
        }
    }
    
    public static function updateProductById($id, $options)
    {
//        echo $id;
//        print_r($options);
//        $id = intval($id);     
        $db = Db::getConnection();
        
        $statement = "
                        UPDATE shop_product SET 
                            name = :name, 
                            category_id = :category_id, 
                            code = :code, 
                            price = :price, 
                            availability = :availability, 
                            brand = :brand, 
                            `description` = :description, 
                            `is_new` = :is_new, 
                            `is_recommended` = :is_recommended, 
                            `statys` = :status 
                        WHERE 
                            id = :id;
                    ";
        $result = $db->prepare($statement);
        
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':name', $options['name'], PDO::PARAM_STR);
        $result->bindParam(':category_id', $options['category_id'], PDO::PARAM_INT);
        $result->bindParam(':code', $options['code'], PDO::PARAM_STR);
        $result->bindParam(':price', $options['price'], PDO::PARAM_STR);
        $result->bindParam(':availability', $options['availability'], PDO::PARAM_INT);
        $result->bindParam(':brand', $options['brand'], PDO::PARAM_STR);
        $result->bindParam(':description', $options['description'], PDO::PARAM_STR);
        $result->bindParam(':is_new', $options['is_new'], PDO::PARAM_INT);
        $result->bindParam(':is_recommended', $options['is_recommended'], PDO::PARAM_INT);
        $result->bindParam(':status', $options['status'], PDO::PARAM_INT); 
        
        return $result->execute();
    }
    
    //Вывод изображений
    public static function getImage($id)
    {
        //Имя изображения по умалчанию
        $noImage = "no-image.jpg";
        
        //Путь к папке с товарами
        $path = "/upload/images/products/";
        
        //Путь к изображению товара
        $pathToProductImage = $path. $id. ".jpg";
        
        //Проверяем существует ли файл
        if(file_exists(ROOT.$pathToProductImage)){
            return $pathToProductImage;
        }  else {            
//            echo $pathToProductImage;
            return $path. $noImage;
        }
    }
}