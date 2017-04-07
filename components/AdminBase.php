<?php

abstract class AdminBase 
{
    //метод проверки прав пользователя
    public static function checkAdmin()
    {
        //Проверяем авторизирыван ли пользователь
        $userId = User::checkLogged();
        
        //Получаем информацию о пользователе
        $user = User::getUserById($userId);
        
        //Проверяем адимн ли пользователь
        if($user['role'] == 'admin'){
            return TRUE;
        }  else {
            die("Доступ запрещен");
        }
    }
}
