<?php

//include_once ROOT.'/models/Category.php';
//include_once ROOT.'/models/Product.php';
//include_once ROOT.'/components/Pagination.php';

class catalogController 
{
    public function actionIndex()
    {
        $categories = array();
        $categories = Category::getCategoryList();
        
        $latestProducts  = array();
        //аргумент кол-во вывод на экран
        $latestProducts = Product::getLatestProducts(6);
        
        require_once ROOT.'/views/catalog/index.php';
        
        return TRUE;
    }
    
    public function actionCategory($categoryId, $page = 1)
    {        
        $categories = array();
        $categories = Category::getCategoryList();
        
        $catalogProducts = array();
        $categoryProducts = Product::getProductsListByCategory($categoryId, $page);
        
        //пагинация
        //Общее кол-во товаров
        $total = Product::getTotalProductsInCategory($categoryId);
        
        $pagination = new Pagination($total, $page, Product::SHOW_BY_DEFAULT, 'page-');
        require_once ROOT.'/views/catalog/category.php';
        
        return TRUE;        
    }
    
}
