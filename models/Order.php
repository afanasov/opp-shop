<?php

class Order 
{
    public static function save($userName, $userPhone, $userComment, $userId, $products)
    {
        //Определяем тип данных
//        echo gettype($products);
        
//        echo '<pre>';
//        var_dump($products);
//        echo '</pre>';
//        echo '<hr/>';
        
        //Массив в строку Json 
        $products = json_encode($products);
        
        $db = Db::getConnection();

        $statment = 'INSERT INTO shop_product_order (user_name, user_phone, user_comment, user_id, products) '
                . 'VALUES (:user_name, :user_phone, :user_comment, :user_id, :products)';

        $result = $db->prepare($statment);
        $result->bindParam(':user_name', $userName, PDO::PARAM_STR);
        $result->bindParam(':user_phone', $userPhone, PDO::PARAM_STR);
        $result->bindParam(':user_comment', $userComment, PDO::PARAM_STR);
        $result->bindParam(':user_id', $userId, PDO::PARAM_STR);
        $result->bindParam(':products', $products, PDO::PARAM_STR);

        return $result->execute();
    }
    
    //Функция вывода заказов в админ панели
    public static function getOrderList()
    {
        $db = Db::getConnection();
        
        $statement = "SELECT id, user_name, user_phone, date, statys FROM shop_product_order";
        
        $result = $db->query($statement);
        
        $ordersList = array();
        $i = 0;
        while ($row = $result->fetch()) {
            $ordersList[$i]['id'] = $row['id'];
            $ordersList[$i]['user_name'] = $row['user_name'];
            $ordersList[$i]['user_phone'] = $row['user_phone'];
            $ordersList[$i]['date'] = $row['date'];
            $ordersList[$i]['statys'] = $row['statys'];
            $i++;
        }
        return $ordersList;
    }
    //Проверка статуса заказа
    public static function getStatusText($status)
    {
        
        switch ($status) {
            case '1':
                return 'Новый заказ';
                break;
            case '2':
                return 'В обработке';
                break;
            case '3':
                return 'Доставляется';
                break;
            case '4':
                return 'Закрыт';
                break;
        }
        
        return $status;
    }
    //Получаем данные о заказа по id
    public static function getOrdersList($id)
    {
        //echo $id;die;
        $db = Db::getConnection();
        
        $statment = "SELECT * FROM shop_product_order WHERE id = :id";
        
        $result = $db->prepare($statment);
        
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        
        // Указываем, что хотим получить данные в виде массива
        $result->setFetchMode(PDO::FETCH_ASSOC);

        // Выполняем запрос
        $result->execute();

//      var_dump($result->fetch());die;
        // Возвращаем данные
        return $result->fetch();
    }
    //Редактируем Заказа
    public static function updateOrderById($options, $id)
    {
//        echo $id;die;
//        var_dump($options);die;
        
        $db = Db::getConnection();
        
        $statement = "
                        UPDATE shop_product_order 
                            SET 
                                user_name = :userName, 
                                user_phone = :userPhone, 
                                user_comment = :userComment,                                  
                                statys = :status 
                            WHERE 
                                id = :id;
                    ";
        $result = $db->prepare($statement);
        
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':userName', $options['userName'], PDO::PARAM_STR);
        $result->bindParam(':userPhone', $options['userPhone'], PDO::PARAM_STR);
        $result->bindParam(':userComment', $options['userComment'], PDO::PARAM_STR);
        $result->bindParam(':status', $options['status'], PDO::PARAM_INT);

//        var_dump($result->execute());die;
        return $result->execute();
    }
    //Удаление заказа по id
    public static function deleteOrderById($id)
    {
        //echo $id;die;
        // Соединение с БД
        $db = Db::getConnection();

        // Текст запроса к БД
        $statement = 'DELETE FROM shop_product_order WHERE id = :id';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($statement);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        
        //var_dump($result->execute());die;
        return $result->execute();
    }
    //Информация о заказе по id
    public static function getOrderById($id)
    {
        // Соединение с БД
        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = 'SELECT * FROM shop_product_order WHERE id = :id';

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);

        // Указываем, что хотим получить данные в виде массива
        $result->setFetchMode(PDO::FETCH_ASSOC);

        // Выполняем запрос
        $result->execute();

//        print_r($result->fetch());die;
        // Возвращаем данные
        return $result->fetch();
    }
    public static function getProdustsByIds($idsArray)
    {
        // Соединение с БД
        $db = Db::getConnection();

        // Превращаем массив в строку для формирования условия в запросе
        $idsString = implode(',', $idsArray);

        // Текст запроса к БД
        $sql = "SELECT * FROM shop_product WHERE statys ='1' AND id IN ($idsString)";

        $result = $db->query($sql);

        // Указываем, что хотим получить данные в виде массива
        $result->setFetchMode(PDO::FETCH_ASSOC);

        // Получение и возврат результатов
        $i = 0;
        $products = array();
        while ($row = $result->fetch()) {
            $products[$i]['id'] = $row['id'];
            $products[$i]['code'] = $row['code'];
            $products[$i]['name'] = $row['name'];
            $products[$i]['price'] = $row['price'];
            $i++;
        }
        return $products;
    }
}
