<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * возвращаем массив с категориями
 *
 * @author Тёма
 */
class Category 
{
    public static function getCategoryList()
    {
        $db = Db::getConnection();
        
        $categoryList = array();
        
        $statement = "SELECT * FROM shop_category ORDER BY sort_order ASC";
        
        $result = $db->query($statement);
        
        $i = 0;
        
        while ($row = $result->fetch()){
            $categoryList[$i]['id'] = $row['id'];
            $categoryList[$i]['name'] = $row['name'];
            $categoryList[$i]['sort_order'] = $row['sort_order'];
            $categoryList[$i]['status'] = $row['status'];
            $i++;
        }
//        echo '<pre>';
//        print_r($categoryList);
//        die;
        return $categoryList;
    }
    
    public static function getCategoriesListAdmin()
    {
        $db = Db::getConnection();
        
        $statement = "SELECT * FROM shop_category";
        $result = $db->query($statement);
        
        $categoryList = array();
        $i = 0;
        
        while ($row = $result->fetch()){
            $categoryList[$i]['id'] = $row['id'];
            $categoryList[$i]['name'] = $row['name'];
            $categoryList[$i]['sort_order'] = $row['sort_order'];
            $categoryList[$i]['status'] = $row['status'];
            
            $i++;
        }
        
        return $categoryList;
    }
    // Элемент отображения в админ панели статус
    public static function getStatusText($status)
    {
        switch ($status) {
            case '1':
                return 'Отображается';
                break;
            case '0':
                return 'Скрыта';
                break;
        }
    }
    //Проверку существования порядкового номера в админ панели
    //проверка сущ. email
    public static function checkSortOrderExists($num)
    {
        $db = Db::getConnection();
        
        $statement = "SELECT COUNT(*) FROM shop_category WHERE sort_order = :num";
        
        $result = $db->prepare($statement);
        $result->bindParam(':num', $num, PDO::PARAM_INT);
        $result->execute();
        
//        var_dump($result->execute());
//        die;
        if($result->fetchColumn()){
            return TRUE;
        }  else {
            return FALSE;
        }
    }
    
    //Создание новой категории
    public static function createCategory($options)
    {
        $db = Db::getConnection();
        
        $statement = "
                        INSERT INTO `shop_category` 
                            (`name`, `sort_order`, `status`) 
                        VALUES 
                            (:name, :sort_order, :status);
                    ";
        
        $result = $db->prepare($statement);
        
        $result->bindParam(':name', $options['name'], PDO::PARAM_STR);
        $result->bindParam(':sort_order', $options['sort_order'], PDO::PARAM_INT);
        $result->bindParam(':status', $options['status'], PDO::PARAM_INT);
        
//        var_dump($result->execute());die;
        
        return $result->execute();
    }
    
   //Получаем информацию о категории по id
   public static function getCategoryById($id)
   {
       //echo $id; die;
       $db = Db::getConnection();
       
       $categoryList = array();
       
       $statement = "SELECT * FROM `shop_category` WHERE id = :id";
        
        $result = $db->prepare($statement);
        
        $result->bindParam(':id', $id, PDO::PARAM_INT);      
        
        // Указываем, что хотим получить данные в виде массива
        $result->setFetchMode(PDO::FETCH_ASSOC);

        // Выполняем запрос
        $result->execute();

//        var_dump($result->fetch());die;
        // Возвращаем данные
        return $result->fetch();
    }
    
    public static function updateCategoryById($options, $id)
    {
        //echo $id;die;
        //print_r($options);die;
        $db = Db::getConnection();
        
        $statement = "
                        UPDATE shop_category SET 
                            name = :name, 
                            `sort_order` = :sort_order, 
                            `status` = :status 
                        WHERE 
                            id = :id;
                    ";
        $result = $db->prepare($statement);
        
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':name', $options['name'], PDO::PARAM_STR);
        $result->bindParam(':sort_order', $options['sort_order'], PDO::PARAM_INT);
        $result->bindParam(':status', $options['status'], PDO::PARAM_INT);

        //var_dump($result->execute());die;
        return $result->execute();
    }
    
    public static function deleteCategoryById($id)
    {
        //echo $id;
        
        $db = Db::getConnection();
        
        $statment = "DELETE FROM shop_category WHERE id = :id";
        
        $result = $db->prepare($statment);
        
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        
        return $result->execute();
    }
}
