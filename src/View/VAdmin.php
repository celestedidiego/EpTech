<?php

/**
 * Class VAdmin
 * Gestisce la visualizzazione delle pagine di amministrazione tramite Smarty.
 */
class VAdmin {

    /**
     * @var Smarty
     */
    private $smarty;

    /**
     * VAdmin constructor.
     * Inizializza la configurazione di Smarty.
     */
    public function __construct()
    {
        $this->smarty = StartSmarty::configuration();
    }

    /**
     * Mostra la pagina di gestione prodotti per l'admin.
     * @param array $array_products Lista dei prodotti.
     * @param array $categories Lista delle categorie.
     * @param array $brand Lista dei brand.
     * @param bool $product_added Indica se un prodotto è stato aggiunto con successo.
     * @param bool $product_modified Indica se un prodotto è stato modificato con successo.
     * @param bool $product_deleted Indica se un prodotto è stato eliminato con successo.
     * @return void
     */
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

    /**
     * Mostra la pagina di gestione utenti per l'admin.
     * @param array $users_info Informazioni sugli utenti.
     * @return void
     */
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

    /**
     * Mostra solo i prodotti filtrati dall'admin.
     * @param array $products Prodotti filtrati.
     * @return void
     */
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

    /**
     * Mostra solo gli utenti filtrati dall'admin.
     * @param array $users Utenti filtrati.
     * @return void
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

    /**
     * Mostra tutte le recensioni gestite dall'admin.
     * @param array $reviews Lista delle recensioni.
     * @return void
     */
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

    /**
     * Mostra solo le recensioni filtrate dall'admin.
     * @param array $reviews Recensioni filtrate.
     * @return void
     */
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

    /**
     * Mostra la pagina di gestione ordini per l'admin.
     * @param array $orders Lista degli ordini.
     * @return void
     */
    public function showManageOrders($orders) {
        $loginVariables = (new VUser)->checkLogin();
        foreach ($loginVariables as $key => $value) {
            $this->smarty->assign($key, $value);
        }
        $this->smarty->assign('orders', $orders);
        $this->smarty->display('manageOrders.tpl');
    }

    /**
     * Mostra la sezione di gestione dell'admin.
     * @return void
     */
    public function showManageSection() {
        $loginVariables = (new VUser)->checkLogin();
        foreach ($loginVariables as $key => $value) {
            $this->smarty->assign($key, $value);
        }
        $this->smarty->assign('manageSection', 1);
        $this->smarty->display('userinfo.tpl');
    }

    /**
     * Mostra la pagina per la creazione di un nuovo articolo.
     * @param array $articles Lista degli articoli (opzionale).
     * @return void
     */
    public function showNewArticle($articles = []) {
        $loginVariables=(new VUser)->checkLogin();
        foreach ($loginVariables as $key => $value){
            $this->smarty->assign($key, $value);
        }
        $this->smarty->assign('articles', $articles);
        $this->smarty->display('newArticle.tpl');
    }
}