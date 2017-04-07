<?php

class UserController
{
    public function actionRegister()
    {
        $name = '';
        $phone = '';
        $email = '';
        $phone = '';
        $password = '';
        $password_s = '';
        $result = FALSE;
        
        if (isset($_POST['submit'])) {
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $password_s = $_POST['password_s'];
            $date = date("Y-m-d");
            
            $errors = array();
            
            if (!User::checkName($name))    
                $errors[] = 'Имя не должно быть не короче 2-х символов';
            
            if (!User::checkPhone($phone))    
                $errors[] = 'Номер должен состоять из 10 символов';
                       
            if (!User::checkEmail($email))      
                $errors[] = 'Неправильный email';

            if (!User::checkPassword($password))    
                $errors[] = 'Пароль не должен быть короче 6-ти символов';
            
            if ($password !== $password_s)      
                $errors[] = 'Пароль должен совпадать';
            
            if (User::checkEmailExists($email))     
                $errors[] = 'Такой email уже используется';
                        
            if ($errors == false)       
                $result = User::register($name, $phone, $email, $password, $date);
            
        }

        require_once(ROOT . '/views/user/register.php');

        return true;
    }
    
    public function actionLogin()
    {
        $email = '';
        $password = '';
        
        if(isset($_POST['submit'])){
//            echo '<pre>';
//            var_dump($_POST);
            $email = $_POST["email"];
            $password = $_POST["password"];
            
            $errors = array();
            
            if(!User::checkEmail($email))   
                $errors[] = 'Неправильный email';
            if (!User::checkPassword($password))    
                $errors[] = 'Пароль не должен быть короче 6-ти символов';
            
            $userId = User::checkUserData($email, $password);   
            
            if($userId == FALSE){
                $errors[] = 'Неверный логин или пароль';
            } else{
                User::auth($userId);
                header("Location:/cabinet/");
            }
        }
        
        require_once(ROOT . '/views/user/login.php');
        return TRUE;
    }
    
    public static function actionLogout()
    {
        session_start();
        unset($_SESSION['user']);
        header("Location: /");
    }
}
