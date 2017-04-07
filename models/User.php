<?php

class User
{
    //Валидация имяни
    public static function checkName($name)
    {
        if (strlen($name)>= 2){
            return TRUE;
        }  else {
            return FALSE;
        }
    }
    //Валидация номера телефона     
    public static function checkPhone($phone)
    {
        if (strlen($phone) >= 10) {
            return true;
        }
        return false;
    }
    //Валидация email
    public static function checkEmail($email)
    {
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            return TRUE;
        }  else {
            return FALSE;
        }
    }
    //Валидация текста
    public static function checkTextEmail($textEmail)
    {
        if (strlen($textEmail)>= 6){
            return TRUE;
        }  else {
            return FALSE;
        }
    }
    //Валидация пароля
    public static function checkPassword($password)
    {
        if (strlen($password)>= 6){
            return TRUE;
        }  else {
            return FALSE;
        }
    }
    //проверка сущ. email
    public static function checkEmailExists($email)
    {
        $db = Db::getConnection();
        
        $statement = "SELECT COUNT(*) FROM shop_user WHERE email = :email";
        
        $result = $db->prepare($statement);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->execute();
        
        if($result->fetchColumn()){
            return TRUE;
        }  else {
            return FALSE;
        }
    }
    //регистрация
    public static function register($name,$phone, $email, $password, $date)
    {
        $db = Db::getConnection();
        
        $statement = "INSERT INTO shop_user (name, phone, email, password, date) VALUES (:name,:phone, :email, :password, :date)";
        
        $result = $db->prepare($statement);
        
        $result->bindParam(':name', $name, PDO::PARAM_STR);
        $result->bindParam(':phone', $phone, PDO::PARAM_STR);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->bindParam(':password', $password, PDO::PARAM_STR);
        $result->bindParam(':date', $date, PDO::PARAM_STR);
                
        return $result->execute();
    }
    //проверка логина и пароля для авторизации
    public static function checkUserData ($email, $password)
    {
        $db = Db::getConnection();
        
        $statement = "SELECT * FROM shop_user WHERE email = :email AND password = :password";
        
        $result = $db->prepare($statement);
        
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->bindParam(':password', $password, PDO::PARAM_STR);
        $result->execute();
        
        $user = $result->fetch();
        
        if($user)   return $user['id'];
        
        return FALSE;
    }
    // авторизация
    public static function auth($userId)
    {
        $_SESSION['user'] = $userId;
    }
    //проверка авторизации
    public static function checkLogged()
    {
        if(isset($_SESSION['user'])){
            return $_SESSION['user'];
        }  else {
            header("Location: /user/login");
        }
    }
    //проверка авторизации
    public static function isGuest()
    {
        if(isset($_SESSION['user'])){
            return FALSE;
        }  else {
            return TRUE;
        }
    }
    // информация о пользователе
    public static function getUserById($id)
    {
        if($id){
            $db = Db::getConnection();
            
            $statement = "SELECT * FROM `shop_user` WHERE id = :id";
            
            $result = $db->prepare($statement);
            $result->bindParam(':id', $id, PDO::PARAM_INT);
            
            $result->setFetchMode(PDO::FETCH_ASSOC);
            $result->execute();
            
//            print_r($result->fetch()); die;
            return $result->fetch();
        }
    }
    
    public static function edit($userId, $name, $phone, $password, $email)
    {
        $db = Db::getConnection();
        
        $statement = "UPDATE shop_user SET name = :name, phone = :phone, email = :email, password = :password  WHERE id = :id";
        
        $result = $db->prepare($statement);
        $result->bindParam(':id', $userId, PDO::PARAM_INT);
        $result->bindParam(':name', $name, PDO::PARAM_STR);
        $result->bindParam(':phone', $phone, PDO::PARAM_STR);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->bindParam(':password', $password, PDO::PARAM_STR);
        
        return $result->execute();
    }
    
}

