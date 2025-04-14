<?php

class VReview {

    private $smarty;

    public function __construct() {
        // Configura Smarty utilizzando il metodo statico configuration della classe StartSmarty
        $this->smarty = StartSmarty::configuration();

        // Assegna la quantità totale di articoli nel carrello alla variabile Smarty 'cart_quantity'
        $this->smarty->assign('cart_quantity', (new VUser)->countItemCart());
        
        // Recupera i dati del carrello utilizzando il metodo cart_header
        $data = (new VUser)->cart_header();
        
        // Assegna i prodotti del carrello alla variabile Smarty 'prodotti_carrello'
        $this->smarty->assign('products_cart', $data['array_cart']);
        
        // Assegna il subtotale del carrello alla variabile Smarty 'subtotal'
        $this->smarty->assign('subtotal', $data['subtotal']);
        
        // Assegna l'array del carrello alla variabile Smarty 'carrello'
        $this->smarty->assign('cart', $data['cart']);
        
        // Assegna una variabile Smarty 'is_cart_empty' che indica se il carrello è vuoto
        $this->smarty->assign('is_cart_empty', !isset($_COOKIE['cart']) || empty($data['cart']) ? 1 : 0);
    
    }

    public function showProductReviews($product, $reviews, $canReview) {
        // Recupera le variabili di sessione relative al login
        $loginVariables = (new VUser)->checkLogin();
        
        // Assegna le variabili di sessione relative al login alle variabili Smarty
        foreach ($loginVariables as $key => $value) {
            $this->smarty->assign($key, $value);
        }
        
        // Assegna il prodotto alla variabile Smarty 'product'
        $this->smarty->assign('product', $product);
        
        // Assegna le recensioni alla variabile Smarty 'reviews'
        $this->smarty->assign('reviews', $reviews);
        
        // Assegna la variabile Smarty 'canReview' che indica se l'utente può recensire il prodotto
        $this->smarty->assign('canReview', $canReview);
        
        // Visualizza il template recensioniProdotto.tpl
        $this->smarty->display('productReviews.tpl');
    }

    public function showAdminReviews($reviews) {
        // Recupera le variabili di sessione relative al login
        $loginVariables = (new VUser)->checkLogin();
        
        // Assegna le variabili di sessione relative al login alle variabili Smarty
        foreach ($loginVariables as $key => $value) {
            $this->smarty->assign($key, $value);
        }
        
        // Se è presente un errore nella sessione, assegna la variabile Smarty 'error'
        if (isset($_SESSION['error'])) {
            $this->smarty->assign('error', $_SESSION['error']);
        }
        
        // Se è presente un messaggio di successo nella sessione, assegna la variabile Smarty 'success'
        if (isset($_SESSION['success'])) {
            $this->smarty->assign('success', $_SESSION['success']);
        }
        
        // Cancella le variabili di sessione 'error' e 'success'
        unset($_SESSION['error']);
        unset($_SESSION['success']);
        
        // Assegna le recensioni alla variabile Smarty 'reviews'
        $this->smarty->assign('reviews', $reviews);
        
        // Visualizza il template recensioniAdmin.tpl
        $this->smarty->display('adminReviews.tpl');
    }

    public function showReviewForm($product) {
        // Recupera le variabili di sessione relative al login
        $loginVariables = (new VUser)->checkLogin();
        
        // Assegna le variabili di sessione relative al login alle variabili Smarty
        foreach ($loginVariables as $key => $value) {
            $this->smarty->assign($key, $value);
        }
        
        // Assegna il prodotto alla variabile Smarty 'product'
        $this->smarty->assign('product', $product);
        
        // Visualizza il template formRecensione.tpl
        $this->smarty->display('reviewForm.tpl');
    }

    public function showReplyForm($review) {
        // Recupera le variabili di sessione relative al login
        $loginVariables = (new VUser)->checkLogin();
        
        // Assegna le variabili di sessione relative al login alle variabili Smarty
        foreach ($loginVariables as $key => $value) {
            $this->smarty->assign($key, $value);
        }
        
        // Assegna la recensione alla variabile Smarty 'review'
        $this->smarty->assign('review', $review);
        
        // Visualizza il template formRisposta.tpl
        $this->smarty->display('replyForm.tpl');
    }

}