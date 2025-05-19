<?php

/**
 * Class VProduct
 * Gestisce la visualizzazione dei prodotti tramite Smarty.
 */
class VProduct {

    /**
     * @var Smarty
     */
    private $smarty;

    /**
     * VProduct constructor.
     * Inizializza la configurazione di Smarty.
     */
    public function __construct() {
        $this->smarty = StartSmarty::configuration();
    }

    /**
     * Mostra la lista dei prodotti con filtri e messaggi di stato.
     * @param array $products Lista dei prodotti.
     * @param array $categories Lista delle categorie.
     * @param array $brands Lista dei brand.
     * @param array $filters Filtri applicati.
     * @param bool $product_added Indica se un prodotto è stato aggiunto.
     * @param bool $product_modified Indica se un prodotto è stato modificato.
     * @param bool $product_deleted Indica se un prodotto è stato eliminato.
     * @return void
     */
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

    /**
     * Mostra il form per aggiungere un nuovo prodotto.
     * @param array $array_category Lista delle categorie.
     * @return void
     */
    public function addProductForm($array_category) {
        $loginVariables = (new VUser)->checkLogin();
        foreach ($loginVariables as $key => $value) {
            $this->smarty->assign($key, $value);
        }
        $this->smarty->assign('array_category', $array_category);
        $this->smarty->assign('addProductForm', 1);
        $this->smarty->display('userinfo.tpl');
    }

    /**
     * Mostra il form per modificare un prodotto esistente.
     * @param object $product Prodotto da modificare.
     * @param array $images Immagini associate al prodotto.
     * @return void
     */
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

    /**
     * Mostra un errore di caricamento immagine durante l'aggiunta di un prodotto.
     * @param array|null $categories Lista delle categorie (opzionale).
     * @return void
     */
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

    /**
     * Mostra i dettagli di un prodotto e le sue recensioni.
     * @param object $product Prodotto da visualizzare.
     * @param array $reviews Recensioni associate al prodotto.
     * @return void
     */
    public function showProductDetails($product, $reviews) {
        $averageRating = 0;
        if ($reviews['n_reviews'] > 0) {
            foreach ($reviews['items'] as $review) {
                $averageRating += $review->getVote();
            }
            $averageRating /= $reviews['n_reviews'];
        }
        $this->smarty->assign('averageRating', $averageRating);
        $this->smarty->assign('reviews', $reviews);
        $this->smarty->display('infoProduct.tpl');
    }
}