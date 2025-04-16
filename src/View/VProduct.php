<?php

class VProduct {

    private $smarty;

    public function __construct() {
        $this->smarty = StartSmarty::configuration();
    }

    public function listProducts($products, $categories, $brands, $filters, $product_added, $product_modified, $product_deleted) {
        $loginVariables = (new VUser)->checkLogin();
        foreach ($loginVariables as $key => $value) {
            $this->smarty->assign($key, $value);
        }
        $this->smarty->assign('products', $products);
        $this->smarty->assign('categories', $categories);
        $this->smarty->assign('brands', $brands);
        $this->smarty->assign('filters', $filters);
        $this->smarty->assign('product_added', $product_added);
        $this->smarty->assign('product_modified', $product_modified);
        $this->smarty->assign('product_deleted', $product_deleted);
        $this->smarty->display('userinfo.tpl');
    }

    public function addProductForm($array_category) {
        $loginVariables = (new VUser)->checkLogin();
        foreach ($loginVariables as $key => $value) {
            $this->smarty->assign($key, $value);
        }
        $this->smarty->assign('array_category', $array_category);
        $this->smarty->assign('addProductForm', 1);
        $this->smarty->display('userinfo.tpl');
    }

    
    public function modifyProductForm($product, $images) {
        $loginVariables = (new VUser)->checkLogin();
        foreach ($loginVariables as $key => $value) {
            $this->smarty->assign($key, $value);
        }
        $this->smarty->assign('nameProduct', $product->getNameProduct());
        $this->smarty->assign('description', $product->getDescription());
        $this->smarty->assign('brand', $product->getBrand());
        $this->smarty->assign('color', $product->getColor());
        $this->smarty->assign('category', $product->getNameCategory()->getNameCategory());
        $this->smarty->assign('productId', $product->getProductId());
        $this->smarty->assign('images', $images);
        
        $this->smarty->assign('modifyProductForm', 1);
        $this->smarty->display('userinfo.tpl');
    }

    public function errorImageUpload($categories = null){
        $loginVariables = (new VUser)->checkLogin();
        foreach ($loginVariables as $key => $value) {
            $this->smarty->assign($key, $value);
        }
        $this->smarty->assign('array_categorie', $categories);
        $this->smarty->assign('addProductForm', 1);
        $this->smarty->assign('errorImageUpload', 1);
        $this->smarty->display('userinfo.tpl');
    }
    

}