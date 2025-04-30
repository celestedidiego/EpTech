<?php

class VAdmin {

    private $smarty;

    public function __construct()
    {
        $this->smarty = StartSmarty::configuration();
    }

    public function manageProducts($array_products, $categories, $brand, $product_added, $product_modified, $product_deleted){

        $loginVariables=(new VUser)->checkLogin();
        foreach ($loginVariables as $key => $value){
            $this->smarty->assign($key, $value);
        }
        if (isset($_SESSION['message'])) {
            $this->smarty->assign('message', $_SESSION['message']);
            unset($_SESSION['message']);
        }
        if (isset($_SESSION['error'])) {
            $this->smarty->assign('error', $_SESSION['error']);
            unset($_SESSION['error']);
        }

        $this->smarty->assign('array_products', $array_products);
        $this->smarty->assign('array_category', $categories);
        $this->smarty->assign('brand', $brand);
        $this->smarty->assign('listProducts', 1);
        $this->smarty->assign('addedProductSuccess', $product_added);
        $this->smarty->assign('modifiedProductSuccess', $product_modified);
        $this->smarty->assign('deletedProductSuccess', $product_deleted);
        $this->smarty->assign('productFiltered',0);
        $this->smarty->display('manageProducts.tpl');

    }

    public function manageUsers($users_info) {
        $loginVariables=(new VUser)->checkLogin();
        foreach ($loginVariables as $key => $value){
            $this->smarty->assign($key, $value);
        }
        $this->smarty->assign('users_info', $users_info);
        if (isset($_SESSION['message'])) {
            $this->smarty->assign('message', $_SESSION['message']);
            unset($_SESSION['message']);
        }
        if (isset($_SESSION['error'])) {
            $this->smarty->assign('error', $_SESSION['error']);
            unset($_SESSION['error']);
        }
        $this->smarty->display('manageUsers.tpl');
    }

    //mostra solo i prodotti filtrti dall'admin
    public function displaySearchResults($products) {
        $loginVariables=(new VUser)->checkLogin();
        foreach ($loginVariables as $key => $value){
            $this->smarty->assign($key, $value);
        }
                
        $this->smarty->assign('search_results', 1);
        $this->smarty->assign('products', $products);
        $this->smarty->assign('productFiltered', 1);
        $this->smarty->display('manageProducts.tpl');
    }

    /*
    //mostra solo gli utenti filtrati dall'admin
    public function displayFilteredUsers($users){
        $loginVariables=(new VUser)->checkLogin();
        foreach ($loginVariables as $key => $value){
            $this->smarty->assign($key, $value);
        }
        if (!isset($users['items']) || !is_array($users['items'])) {
            $users['items'] = [];
        }
        $this->smarty->assign('users_info', $users);
        $this->smarty->display('manageUsers.tpl');
    }
    */

    public function displayFilteredUsers($users)
    {
        $loginVariables = (new VUser)->checkLogin();
        foreach ($loginVariables as $key => $value) {
            $this->smarty->assign($key, $value);
        }

        if (!isset($users['users']) || !is_array($users['users'])) {
            $users['users'] = [];
        }

        $this->smarty->assign('users_info', $users);
        $this->smarty->display('manageUsers.tpl');
    }

    // Mostra tutte le recensioni gestite dall'admin
    public function manageReviews($reviews) {
        $loginVariables = (new VUser)->checkLogin();
        foreach ($loginVariables as $key => $value){
            $this->smarty->assign($key, $value);
        }
        if (isset($_SESSION['success'])) {
            $this->smarty->assign('success', $_SESSION['success']);
            unset($_SESSION['success']);
        }
        if (isset($_SESSION['error'])) {
            $this->smarty->assign('error', $_SESSION['error']);
            unset($_SESSION['error']);
        }
        $this->smarty->assign('reviews', $reviews);
        $this->smarty->display('manageReviews.tpl');
    }

    // Mostra solo le recensioni filtrate dall'admin
    public function displayFilteredReviews($reviews) {
        $loginVariables = (new VUser)->checkLogin();
        foreach ($loginVariables as $key => $value){
            $this->smarty->assign($key, $value);
        }
        if (!isset($reviews['items']) || !is_array($reviews['items'])) {
            $reviews['items'] = [];
        }
        $this->smarty->assign('reviews', $reviews);
        $this->smarty->display('manageReviews.tpl');
    }

    public function showManageOrders($orders) {
        $loginVariables = (new VUser)->checkLogin();
        foreach ($loginVariables as $key => $value) {
            $this->smarty->assign($key, $value);
        }
        $this->smarty->assign('orders', $orders);
        $this->smarty->display('manageOrders.tpl');
    }

    public function showManageSection() {
        $loginVariables = (new VUser)->checkLogin();
        foreach ($loginVariables as $key => $value) {
            $this->smarty->assign($key, $value);
        }
        $this->smarty->assign('manageSection', 1);
        $this->smarty->display('userinfo.tpl');
    }
}