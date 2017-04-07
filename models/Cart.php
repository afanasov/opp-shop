<?php

class Cart 
{
    // добавление товара в корзину
    public static function addProduct($id)
    {
        echo$id = intval($id);

        //создаем массив для хранения
        $productsInCart = array();

        //Проверяем наличие товаров сессии
        if(isset($_SESSION['products']))

            $productsInCart = $_SESSION['products'];
        
        //Увеличиваем кол-во товаров если они там есть если нет добавляем
        if(array_key_exists($id, $productsInCart)){
            $productsInCart[$id] ++;
        }  else {
            // Добавляем новый товар в карзину
            $productsInCart[$id] = 1;
        }
        
        $_SESSION['products'] = $productsInCart;
        //echo '<pre>'; print_r($productsInCart); die;        
    }
    
    //Подсчет кол-во товаров корзине
    public static function countItem()
    {
        if(isset($_SESSION['products'])){
            $count = 0;
            
            //в цикле считаем кол-во товаров
            foreach ($_SESSION['products'] as $id=>$quantity){
                $count = $count + $quantity;
            }
            return $count;
        }  else {
            return 0;
        }
    }
    //Вывод товаров из корзины
    public static function getProducts()
    {
        if(isset($_SESSION['products'])){
            return $_SESSION['products'];
        }  else {
            return FALSE;
        }
    }
    //Общая стоимость товаров
    public static function getTotalPrice($products)
    {
        $productsInCart = self::getProducts();
        
        if($productsInCart){
            $total = 0;
            foreach ($products as $item){
                $total += $item['price'] * $productsInCart[$item['id']];
            }
        }
        
//        echo $total; die;
        return $total;
    }
    //Очитска корзины
    public static function clear()
    {
        if (isset($_SESSION['products'])) {
            unset($_SESSION['products']);
        }
    }
    
    //Удаление из корзины
    public static function delete($id)
    {
        //Получаем массив с товарами
        $productsInCart = self::getProducts();
        
        //print_r($productsInCart);die;
        //Удаляем из массива по id
        unset($productsInCart[$id]);
        //Записываем новый массив в корзину
        $_SESSION['products'] = $productsInCart;
        return TRUE;
    }
}
