<?php

class CartController
{
    public function actionAdd($id)
    {
        //Добавляем товар в карзину(в сессию)
        Cart::addProduct($id);
        
        //Возврат на исходную страницу
//        echo '<pre>';
//        var_dump($_SERVER);
        $referer = $_SERVER["HTTP_REFERER"];
        header("location:$referer");        
    }
    
    public function actionIndex()
    {
        $categories = array();
        $categories = Category::getCategoryList();
        
        //Получем информацию из корзины
        $productsInCart = Cart::getProducts();
        
        if($productsInCart){
            //Информация для списка
            //Массив с id
//            print_r($productsInCart);
            //выбераем ID
            $productsId = array_keys($productsInCart);
//            echo 'hr/';
//            print_r($productsId);
            //Получаем инфо о товаре по ID
            $products = Product::getProductsById($productsId);
            //Получаем общую стоимость товара
//            echo '<pre>';
//            var_dump($products);die;
            $totalPrice = Cart::getTotalPrice($products);
        }
        
        require_once ROOT.'/views/cart/index.php';
        return TRUE;
    }
    //Информация о покупателе в корзине
    public function actionCheckout()
    {
        //список категории
        $categories = array();
        $categories = Category::getCategoryList();
        
        //Массив заказа
        $result = FALSE;
        
        //Форма отправления
        if(isset($_POST['submit'])){
//            echo '<pre>';
//            var_dump($_POST);
            $userName = $_POST["userName"];
            $userPhone = $_POST["userPhone"];
            $userComment = $_POST["userComment"];
            
            $errors = array();
            
            if (!User::checkName($userName))    
                $errors[] = 'Имя не должно быть не короче 2-х символов';
            
            if (!User::checkPhone($userPhone))    
                $errors[] = 'Номер не должно быть короче 10 символов';
            
            if($errors == FALSE){
                // Ошибок НЕТ
                //Собираем инфо о заказе
                $productsInCart = Cart::getProducts();
                //Проверяем авторизацию
                if(User::isGuest()){
                    $userId = FALSE;
                }  else {
                    $userId = User::checkLogged();
                }
//                print_r($userId);
//                echo '<br>';
//                print_r($userName);
//                echo '<br>';
//                print_r($userPhone);
//                echo '<br>';
//                print_r($userComment);
//                echo '<br>';
//                print_r($productsInCart);
//                echo '<br>';
                
                //die;
                //Метка для View
                //Сохраняем заказ в БД
                $result = Order::save($userName, $userPhone, $userComment, $userId, $productsInCart);
                if($result){
                    
                    
                    //Оповещение
                    $adminEmail = "tema-87@inbox.ru";
                    $message = "пришел новый закза http://afanasov.adr.com.ua/admin/orders";
                    $subject = "Новый заказ";
                    
                    mail($adminEmail, $subject, $message);
                    
                    //Очищаем корзину
                    Cart::clear();                                        
                }                
            }  else {
    //          print_r($errors);
                //ошибки есть
    //            echo 'neverno ошибки есть';die;
                $productsInCart = Cart::getProducts();
                //выбераем ID
                $productsId = array_keys($productsInCart);
    //            echo 'hr/';
    //            print_r($productsId);
                //Получаем инфо о товаре по ID
                $products = Product::getProductsById($productsId);
                //Получаем общую стоимость товара
    //            echo '<pre>';
    //            var_dump($products);die;
                $totalPrice = Cart::getTotalPrice($products);
                //Общее кол-во товара
                $totalQuantity = Cart::countItem();
                }

        }  else {
            //Получаем товар из корзины
            $productsInCart = Cart::getProducts();
            //наличие товарав в корзине
            if($productsInCart == FALSE){
                header("Location: /");
            }  else {
                //В корзине есть товар
                //получаем массив с id
                $productsId = array_keys($productsInCart);
    //            echo 'hr/';
    //            print_r($productsId);
                //Получаем инфо о товаре по ID
                $products = Product::getProductsById($productsId);
                
    //            echo '<pre>';
    //         var_dump($products);die;
                //Получаем общую стоимость товара
                $totalPrice = Cart::getTotalPrice($products);              
    //          echo $totalPrice;
                //Общие кол-во товара
                $totalQuantity = Cart::countItem();
    //            print_r($totalQuantity);
                
                $userName = '';
                $userPhone = '';
                $userComment = '';
                
                //Проверяем авторизацию
                if(!User::isGuest()){
    //              echo 'avtor';
                    //Получаем  id
                    $userId = User::checkLogged();
    //              var_dump($userId);
                    //Получаем информацию
                    $user = User::getUserById($userId);
                    //var_dump($user);
                    //Заполняем переменные
                    $userName = $user["name"];
                    $userPhone = $user["phone"];
                }
            }
        }
        
        
        
        require_once ROOT.'/views/cart/checkout.php';
        return TRUE;
    }
    
    //Метод удаления товаров из корзины
    public static function actionDelete($id)
    {
        //Удаляем товар из корзины
        //print_r($id) ;
        Cart::delete($id);
        
        header("Location: /cart/");
    }
}