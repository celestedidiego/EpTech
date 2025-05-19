<?php

/**
 * Class VPurchase
 * Gestisce la visualizzazione e le operazioni di acquisto tramite Smarty.
 */
class VPurchase {

    /**
     * @var Smarty
     */
    private $smarty;

    /**
     * VPurchase constructor.
     * Inizializza la configurazione di Smarty e assegna le variabili di carrello.
     */
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

    /**
     * Mostra la pagina dello shop con prodotti, categorie, brand e filtri applicati.
     * @param array $products Lista dei prodotti.
     * @param array $categories Lista delle categorie.
     * @param array $brands Lista dei brand.
     * @param array $applied_filters Filtri applicati.
     * @return void
     */
    public function shop($products, $categories, $brands, $applied_filters) {
        // Recupera le variabili di sessione relative all'utente loggato
        $loginVariables = (new VUser)->checkLogin();
        
        // Assegna le variabili di sessione relative all'utente loggato alle variabili Smarty
        foreach ($loginVariables as $key => $value) {
            $this->smarty->assign($key, $value);
        }
        
        // Assegna l'array dei prodotti alla variabile Smarty 'array_products'
        $this->smarty->assign('array_products', $products);
        
        // Assegna l'array delle categorie alla variabile Smarty 'array_category'
        $this->smarty->assign('array_category', $categories);
        
        // Assegna l'array delle marche alla variabile Smarty 'brands'
        $this->smarty->assign('brands', $brands);
        
        // Assegna i filtri applicati alla variabile Smarty 'applied_filters'
        $this->smarty->assign('applied_filters', $applied_filters);
        
        // Assegna una variabile Smarty 'search_bar' che indica se visualizzare la barra di ricerca
        $this->smarty->assign('search_bar', 1);
        
        // Assegna una variabile Smarty 'shop' che indica se visualizzare la pagina di acquisto
        $this->smarty->assign('shop', 1);
        
        // Visualizza il template 'userinfo.tpl'
        $this->smarty->display('userinfo.tpl');
    }

    /**
     * Mostra la pagina di dettaglio di un prodotto.
     * @param object $product Prodotto da visualizzare.
     * @param array $images Immagini del prodotto.
     * @param array $reviews Recensioni del prodotto.
     * @param array $same_cat_products Prodotti della stessa categoria.
     * @param bool $can_review Indica se l'utente può recensire.
     * @param mixed $user_review Recensione dell'utente.
     * @param string $successMessage Messaggio di successo.
     * @param string $errorMessage Messaggio di errore.
     * @return void
     */
    public function viewProduct($product, $images, $reviews, $same_cat_products, $can_review, $user_review, $successMessage, $errorMessage) {
        // Recupera le variabili di sessione relative all'utente loggato
        $loginVariables = (new VUser)->checkLogin();
        
        // Assegna le variabili di sessione relative all'utente loggato alle variabili Smarty
        foreach ($loginVariables as $key => $value) {
            $this->smarty->assign($key, $value);
        }
        
        // Assegna l'array dei prodotti della stessa categoria alla variabile Smarty 'same_cat_products'
        $this->smarty->assign('same_cat_products', $same_cat_products);
        
        // Assegna il nome del prodotto alla variabile Smarty 'product_name'
        $this->smarty->assign('nameProduct', $product->getNameProduct());
        
        // Assegna la descrizione del prodotto alla variabile Smarty 'description'
        $this->smarty->assign('description', $product->getDescription());
        
        // Assegna la marca del prodotto alla variabile Smarty 'brand'
        $this->smarty->assign('brand', $product->getBrand());
        
        // Assegna il modello del prodotto alla variabile Smarty 'model'
        $this->smarty->assign('model', $product->getModel());
        
        // Assegna il colore del prodotto alla variabile Smarty 'color'
        $this->smarty->assign('color', $product->getColor());

        $this->smarty->assign('priceProduct', $product->getPriceProduct());

        $this->smarty->assign('avQuantity', $product->getAvQuantity());

        $this->smarty->assign('images', $images);
        $this->smarty->assign('productId', $product->getProductId());

        $this->smarty->assign('reviews', $reviews);

        $this->smarty->assign('can_review', $can_review);

        $this->smarty->assign('review_user', $user_review);

        $this->smarty->assign('successMessage', $successMessage);

        $this->smarty->assign('errorMessage', $errorMessage);
        
        // Assegna la categoria del prodotto alla variabile Smarty 'category'
        $this->smarty->assign('category', $product->getNameCategory()->getNameCategory());

        $this->smarty->display('infoProduct.tpl');
    }

    /**
     * Mostra la pagina del carrello.
     * @return void
     */
    public function cart(){
        $loginVariables = (new VUser)->checkLogin();
        foreach ($loginVariables as $key => $value) {
            $this->smarty->assign($key, $value);
        }
        $this->smarty->assign('qty_updated', 0);
        $qty_updated = isset($_SESSION['qty_updated']) && $_SESSION['qty_updated'];
        unset($_SESSION['qty_updated']);
        if($qty_updated) {
            $this->smarty->assign('qty_updated', 1);
        }
        $this->smarty->display('cart.tpl');
    }

    /**
     * Mostra il form di checkout.
     * @param array $shipping Dati di spedizione.
     * @param array $creditCards Carte di credito disponibili.
     * @param array $products_cart Prodotti nel carrello.
     * @param float $total_cart Totale del carrello.
     * @return void
     */
    public function viewCheckoutForm($shipping, $creditCards, $products_cart, $total_cart) {
        $loginVariables = (new VUser)->checkLogin();
        foreach ($loginVariables as $key => $value) {
            $this->smarty->assign($key, $value);
        }

        $this->smarty->assign('shipping', $shipping);
        $this->smarty->assign('creditCards', $creditCards);
        $this->smarty->assign('products_cart', $products_cart);
        $this->smarty->assign('total_cart', $total_cart);

        $this->smarty->display('checkout.tpl');
    }

    /**
     * Mostra la pagina di conferma ordine.
     * @param array $order Dati dell'ordine.
     * @return void
     */
    public function viewConfirmOrder($order) {
        $loginVariables = (new VUser)->checkLogin();
        foreach ($loginVariables as $key => $value) {
            $this->smarty->assign($key, $value);
        }

        $this->smarty->assign('order', $order);

        $this->smarty->display('orderCompleted.tpl');
    }

    /**
     * Mostra il dettaglio di un ordine.
     * @param array $order Dati dell'ordine.
     * @return void
     */
    public function detailOrder($order){
        $loginVariables = (new VUser)->checkLogin();
        foreach ($loginVariables as $key => $value) {
            $this->smarty->assign($key, $value);
        }
        $expired = false;
        // Gestione sia oggetto che array
        if (is_object($order) && method_exists($order, 'getOrderStatus')) {
            $orderStatus = $order->getOrderStatus();
            $deliveredAt = $order->getDeliveredAt();
        } elseif (is_array($order)) {
            $orderStatus = $order['orderStatus'] ?? null;
            $deliveredAt = $order['deliveredAt'] ?? null;
        } else {
            $orderStatus = null;
            $deliveredAt = null;
        }
        if ($orderStatus === 'Consegnato') {
            if ($deliveredAt instanceof \DateTime) {
                $now = new \DateTime();
                $interval = $now->getTimestamp() - $deliveredAt->getTimestamp();
                if ($interval > 120) {
                    $expired = true;
                }
            } elseif (is_string($deliveredAt)) {
                $now = new \DateTime();
                $deliveredDate = new \DateTime($deliveredAt);
                $interval = $now->getTimestamp() - $deliveredDate->getTimestamp();
                if ($interval > 120) {
                    $expired = true;
                }
            } else {
                $expired = true;
            }
        }
        $this->smarty->assign('order', $order);
        $this->smarty->assign('refund_expired', $expired);
        $this->smarty->assign('detailOrder', 1);
        $this->smarty->display('userinfo.tpl');
    }

    /**
     * Mostra la pagina di errore ordine.
     * @return void
     */
    public function errorOrder() {
        $loginVariables = (new VUser)->checkLogin();
        foreach ($loginVariables as $key => $value) {
            $this->smarty->assign($key, $value);
        }
        $this->smarty->assign('error_order', 0);
        if(isset($_SESSION['error_order'])) {
            $this->smarty->assign('error_order', $_SESSION['error_order']);
            unset($_SESSION['error_order']);
        }

        $this->smarty->display('errorOrder.tpl');
    }
}