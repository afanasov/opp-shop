<?php

class AdminCategoryController extends AdminBase
{
    //Индексная страница
    public function actionIndex()
    {
        //Проверка доступа
        self::checkAdmin();
        
        //Вывод категорий
        $categoriesList = Category::getCategoryList();
//        print_r($categoriesList); die;
         
        require_once ROOT.'/views/admin_category/index.php';
        return TRUE;
    }
    
    public function actionCreate()
    {
        //Проверка доступа
        self::checkAdmin();
        
        if(isset($_POST['submit'])){
            //var_dump($_POST);
            $options['name'] = $_POST['name'];
            $options['sort_order'] = $_POST['sort_order'];
            $options['status'] = $_POST['status'];
            
            //Флаг ошибок
            $errors = array();
            
            //Валидация
            if(!isset($options['name']) OR empty($options['name']))
                $errors[] = "Заполните поле Название";            
            if(!isset($options['sort_order']) OR empty($options['sort_order']))
                $errors[] = "Заполните поле Порядковый номер"; 
            if(Category::checkSortOrderExists($options['sort_order']))
                $errors[] = "Парядковый номер $options[sort_order] уже существует"; 
            
//          print_r($errors);
            if($errors == FALSE){
               //Отправка данных в БД
               Category::createCategory($options);

               header("Location: /admin/category");                
            }   
        }

        require_once ROOT.'/views/admin_category/create.php';
        return TRUE;
    }
    
    public static function actionUpdate($id)
    {
        //echo $id;die;
        
        //Проверка доступа
        self::checkAdmin();
        
        //Получаем данные по категории по id
        $category = Category::getCategoryById($id);
//        print_r($category);        die;
        
        if(isset($_POST['submit'])){
//            var_dump($_POST);die;
            $options['name'] = $_POST['name'];
            $options['sort_order'] = $_POST['sort_order'];
            $options['status'] = $_POST['status'];
            
            //Флаг ошибок
            $errors = array();
            
            //Валидация
            if(!isset($options['name']) OR empty($options['name']))
                $errors[] = "Заполните поле Название";            
            if(!isset($options['sort_order']) OR empty($options['sort_order']))
                $errors[] = "Заполните поле Порядковый номер"; 
            
            if($errors == FALSE){
                //Сохраняем изменения в Бд
                Category::updateCategoryById($options, $id);
                
                header("Location: /admin/category");
            }
        }
            
        require_once(ROOT . '/views/admin_category/update.php');
        return TRUE;
    }
    
    //Удаление категории
    public static function actionDelete($id)
    {
//      echo 'delete';die;
//      echo $id;
        
        //Проверка доступа
        self::checkAdmin();
        
        if(isset($_POST['submit_n']))
            header("location:/admin/category");
        
        if(isset($_POST['submit_y'])){
            //Удаляем категорию
            Category::deleteCategoryById($id);
            
            header("location:/admin/category/"); 
        }    
        require_once(ROOT . '/views/admin_category/delete.php');
        return true;
    }
    
}
