<?php
//include_once ROOT.'/models/Category.php';
//include_once ROOT.'/models/Product.php';
class SiteController 
{
    public function actionIndex()
    {
        //вызов категории
        $categories = array();        
        $categories = Category::getCategoryList();
        
        //вызов общего списка товаров
        $latestProducts  = array();
        //Аргумент Кол-во товаров на странице
        $latestProducts = Product::getLatestProducts(6);
        //Карусель для главной страницы
        $siderProducts = Product::getRecommendedProducts();
        
        require_once ROOT.'/views/site/index.php';
        
        return TRUE;
    }
    
    public function actionContact()
    {
        $userEmail = '';
        $userSubject = '';
        $userText = '';
        $result = FALSE;
        
        if(isset($_POST['submit'])){
            $userEmail = $_POST['userEmail'];
            $userText = $_POST['userText'];
            $userSubject = $_POST['userSubject'];
            
            $errors = array();
            
            if(!User::checkEmail($userEmail))
                $errors[] = 'Некоррктный Email';
            
            if(!User::checkTextEmail($userSubject))
                $errors[] = 'Тема сообщения должен быть длинее шести симвалов';
            
            if(!User::checkTextEmail($userText))
                $errors[] = 'Текст сообщения должен быть длинее шести симвалов';
            
//            print_r($errors);
//            die;
            if($errors == FALSE){
                $adminEmail = 'tema-87@inbox.ru';               
                $subject = "Tема письма: {$userSubject}";
                $message = "Tекст письма: {$userText}. От: {$userEmail}";
                
                $result = mail($adminEmail, $subject, $message);
                $result = TRUE; 
            }
        }
        
       require_once ROOT.'/views/site/contact.php';        
       return TRUE;
    }
    
}
