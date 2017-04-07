<?php

class AdminProductController extends AdminBase
{
    //просмотр страниц
    public static function actionIndex()
    {
        //Проверка прав доступа
        self::checkAdmin();
        
        //Получаем список товаров
        $productsList = Product::getProductsList();
        
        require_once ROOT.'/views/admin_products/index.php';
        
        die;
    }
    
    //Удаление товаров
    public function actionDelete($id)
    {
        //Проверка прав доступа
        self::checkAdmin();
        
        if(isset($_POST['submit_n']))
            header("location:/admin/product/");
        
        if(isset($_POST['submit_y'])){
            //Удаляем товар
            Product::deleteProductById ($id);
            //возврат в меню
            header("location:/admin/product/");
        }
            
        require_once ROOT.'/views/admin_products/delete.php';        
        return TRUE;
    }
    
    public function actionCreate()
    {
        //Проверка прав доступа
        self::checkAdmin();
        
        //Список категорий для выподающего списка
        $categoriesList = Category::getCategoriesListAdmin();
        
        //print_r($categoriesList);die;
        
        //Отправка формы
        
        if(isset($_POST['submit'])){
//            echo '<pre>';
//            var_dump($_POST);
            
            $options['name'] = $_POST['name'];
            $options['code'] = $_POST['code'];
            $options['price'] = $_POST['price'];
            $options['category_id'] = $_POST['category_id'];
            $options['availability'] = $_POST['availability'];
            $options['description'] = $_POST['description'];
            $options['is_new'] = $_POST['is_new'];
            $options['is_recommended'] = $_POST['is_recommended'];
            $options['status'] = $_POST['status'];
            $options['brand'] = $_POST['brand'];
            
            //массив с ошибками
            $errors = array();
            
            //Валидация
            if(!isset($options['name']) OR empty($options['name']))
                $errors[] = "Заполните поле Название";            
            if(!isset($options['code']) OR empty($options['code']))
                $errors[] = "Заполните поле Артикул";
            if(!isset($options['price']) OR empty($options['price']))
                $errors[] = "Заполните поле Стоимость";
            if(!isset($options['category_id']) OR empty($options['category_id']))
                $errors[] = "Укажите Категорию";
            if(!isset($options['brand']) OR empty($options['brand']))
                $errors[] = "Укажите Бренд";
            if(!isset($options['availability']) OR empty($options['availability']))
                $errors[] = "Укажите Отображение";
            if(!isset($options['description']) OR empty($options['description']))
                $errors[] = "Укажите Описание";
            if(!isset($options['is_new']) OR empty($options['is_new']))
                $errors[] = "Укажите Актуальность";
            if(!isset($options['is_recommended']) OR empty($options['is_recommended']))
                $errors[] = "Укажите Рекомендацию";
            if(!isset($options['status']) OR empty($options['status']))
                $errors[] = "Укажите Статус";
            
            
            if($errors == FALSE){
                //Добавляем новый товар
                $id = Product::createProduct($options);
                //echo $id;
                if($id){
                    //Добавляем картинку                   
//                    echo '<pre>';print_r($_FILES['image']);die;
                    if(is_uploaded_file($_FILES['image']['tmp_name'])){
                        //перемещаем и переиминовываем файл
                        move_uploaded_file($_FILES['image']['tmp_name'], $_SERVER['DOCUMENT_ROOT']."/upload/images/products/{$id}.jpg");
                    }  else {
                        $errors[] = "Не удалось загрузить файл";
                    }
                }    
                header("Location:/admin/product");
            } 
        }
        
        
        require_once ROOT.'/views/admin_products/create.php';        
        return TRUE;
    }
    
    public function actionUpdate($id)
    {
        //Проверка прав доступа
        self::checkAdmin();
        
        //Список категорий для выподающего списка
        $categoriesList = Category::getCategoriesListAdmin();
        
        //Получаем данные по id
        $product = Product::getProductByIds($id);
        //var_dump($product);die;
        
        //Отправка формы
        if(isset($_POST['submit'])){          
            //var_dump($_POST);die;

            $options['name'] = $_POST['name'];
            $options['code'] = $_POST['code'];
            $options['price'] = $_POST['price'];
            $options['category_id'] = $_POST['category_id'];
            $options['availability'] = $_POST['availability'];
            $options['description'] = $_POST['description'];
            $options['is_new'] = $_POST['is_new'];
            $options['is_recommended'] = $_POST['is_recommended'];
            $options['status'] = $_POST['status'];
            $options['brand'] = $_POST['brand'];
            
            //массив с ошибками
            $errors = array();
            
            //Валидация
            if(!isset($options['name']) OR empty($options['name']))
                $errors[] = "Заполните поле Название";            
            if(!isset($options['code']) OR empty($options['code']))
                $errors[] = "Заполните поле Артикул";
            if(!isset($options['price']) OR empty($options['price']))
                $errors[] = "Заполните поле Стоимость";
            if(!isset($options['category_id']) OR empty($options['category_id']))
                $errors[] = "Укажите Категорию";
            if(!isset($options['brand']) OR empty($options['brand']))
                $errors[] = "Укажите Бренд";            
            if(!isset($options['description']) OR empty($options['description']))
                $errors[] = "Укажите Описание";  
            
            if($errors == FALSE){
                if(Product::updateProductById($id, $options)){
                 
                //Добавляем картинку                   
//              echo '<pre>';print_r($_FILES['image']);die;
                if(is_uploaded_file($_FILES['image']['tmp_name'])){
                    //перемещаем и переиминовываем файл
                    move_uploaded_file($_FILES['image']['tmp_name'], $_SERVER['DOCUMENT_ROOT']."/upload/images/products/{$id}.jpg");
                }  else {
                    $errors[] = "Не удалось загрузить файл";
                }
                }
            }
        }
        
        //Удалание фото
        if(isset($_POST['delete'])){
            if(file_exists(ROOT."/upload/images/products/{$id}.jpg"))
                unlink (ROOT."/upload/images/products/{$id}.jpg");
        }
        require_once ROOT.'/views/admin_products/update.php';        
        return TRUE;
    }
}
