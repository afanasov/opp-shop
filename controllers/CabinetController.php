<?php

class CabinetController
{


    public function actionIndex()
    {
        //Получаем идентификатор из сессии
        $userId = User::checkLogged();
        
        //Получаем информацию о пользователе из Бд
        
        $user = User::getUserById($userId);

        require_once ROOT.'/views/cabinet/index.php';;
        
        return TRUE;
    }
    
    public function actionEdit()
    {
        //получаем id пользователе        
        $userId = User::checkLogged();
        
        //получаем информацию из БД
        $user = User::getUserById($userId);
        
        $name = $user['name'];
        $phone = $user['phone'];
        $password = $user['password'];
        $email = $user['email'];
        
        $result = FALSE;
        
        if(isset($_POST['submit'])){
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $password = $_POST['password'];
            $email = $_POST['email'];
//            print_r($userId);
//            echo '<br>';
            $errors = FALSE;

            if(!User::checkName($name))     
                $errors[] = 'Имя не должно быть не короче 2-х символов';
            if(!User::checkPhone($phone))     
                $errors[] = 'Номер не должно быть короче 10 символов';
            if (!User::checkPassword($password))    
                $errors[] = 'Пароль не должен быть короче 6-ти символов';
            if (!User::checkEmail($email))      
                $errors[] = 'Неправильный email';
            if (User::checkEmailExists($email))     
                $errors[] = 'Такой email уже используется';
                        
            if($errors == FALSE)    
                $result = User::edit ($userId, $name, $phone, $password, $email);
        }

        require_once ROOT.'/views/cabinet/edit.php';        
        return TRUE;
    }
}