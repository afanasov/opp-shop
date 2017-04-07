<?php
/**
 * Description of AdminOrderController
 *
 * @author Тёма
 */
class AdminOrderController extends AdminBase
{
    public static function actionIndex()
    {
        //Проверка доступа
        self::checkAdmin();
        
        // Получаем список заказов
        $ordersList = Order::getOrderList();
        
        require_once ROOT.'/views/admin_order/index.php';
        return TRUE;
    }
    
    public static function actionUpdate($id)
    {
//        echo $id;die;
        //Проверка доступа
        self::checkAdmin();
        
        //Получаем данные о заказе по id
        $order = Order::getOrdersList($id);
        
        if(isset($_POST['submit'])){
//            echo '<pre>';var_dump($_POST);
            $options['userName'] = $_POST['userName'];
            $options['userPhone'] = $_POST['userPhone'];
            $options['userComment'] = $_POST['userComment'];
            $options['date'] = $_POST['date'];
            $options['status'] = $_POST['status'];

            //Флаг ошибок
            $errors = array();
            //Валидация
            if (!User::checkName($options['userName']))    
                $errors[] = 'Имя не должно быть не короче 2-х символов';
            
            if (!User::checkPhone($options['userPhone']))    
                $errors[] = 'Номер не должно быть короче 10 символов';
            
            if($errors == FALSE){
                
                //Сохраняем в БД
                Order::updateOrderById($options,$id);
                
                // Перенаправляем пользователя на страницу управлениями заказами
                header("Location: /admin/order/update/$id");
            }
        }
        require_once ROOT.'/views/admin_order/update.php';
        return TRUE;
    }
    
    public static function actionDelete($id)
    {
//        echo $id;die;
        // Проверка доступа
        self::checkAdmin();

        // Обработка формы
        if (isset($_POST['submit_n'])) 
            header("Location: /admin/order");
        
        if (isset($_POST['submit_y'])) {
            // Если форма отправлена
            // Удаляем заказ
            Order::deleteOrderById($id);

            // Перенаправляем пользователя на страницу управлениями товарами
            header("Location: /admin/order");
        }

        // Подключаем вид
        require_once(ROOT . '/views/admin_order/delete.php');
        return true;
    }
    //Вывод информации о заказе
    public function actionView($id)
    {
        // Проверка доступа
        self::checkAdmin();

        // Получаем данные о конкретном заказе
        $order = Order::getOrderById($id);

        // Получаем массив с идентификаторами и количеством товаров
        $productsQuantity = json_decode($order['products'], true);

        // Получаем массив с индентификаторами товаров
        $productsIds = array_keys($productsQuantity);

        // Получаем список товаров в заказе
        $products = Order::getProdustsByIds($productsIds);

        // Подключаем вид
        require_once(ROOT . '/views/admin_order/view.php');
        return true;
    }
}
