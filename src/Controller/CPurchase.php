<?php

class CPurchase {

    public static function shop() {

        $view = new VPurchase();

        $filters = [
            'query' => isset($_GET['query']) ? $_GET['query'] : '',
            'category' => isset($_GET['category']) ? $_GET['category'] : '',
            'brand' => isset($_GET['brand']) ? $_GET['brand'] : '',
            'prezzo_max' => isset($_GET['prezzo_max']) ? (int)$_GET['prezzo_max'] : 5000,
        ];

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        if(isset($_GET['query']) || isset($_GET['category']) || isset($_GET['brand']) || isset($_GET['prezzo_max'])){
            $product = FPersistentManager::getInstance()->getProductFiltered($filters, $page);
        }else{
            $product = FPersistentManager::getInstance()->getAllProducts($page);
        }
        $categories = FPersistentManager::getInstance()->getAllCategories();
        $brands= FPersistentManager::getInstance()->getAllBrands();
        
        $view->shop($product, $categories, $brands, $filters);

    }

    public static function viewProduct($productId) {
        $view = new VPurchase();
        $product = FPersistentManager::getInstance()->find(EProduct::class, $productId);
        $images = FPersistentManager::getInstance()->getAllImages($product);

        if (!$product) {
            $_SESSION['product_error'] = "Prodotto non trovato.";
            header("Location: /EpTech/purchase/shop");
            return;
        }

        $reviews_page = isset($_GET['reviews_page']) ? (int)$_GET['reviews_page'] : 1;
        $products_same_page = isset($_GET['products_same_page']) ? (int)$_GET['products_same_page'] : 1;

        $itemsPerPage = 2; // Numero di recensioni per pagina

        $reviews = FPersistentManager::getInstance()->getReviewsProduct($product, $reviews_page, $itemsPerPage);
        $same_cat_products = FPersistentManager::getInstance()->getAllSameCatProducts($product->getNameCategory()->getNameCategory(), $productId, $products_same_page);
        $view = new VPurchase();

        $review_user = null;
        $can_review = false;
        if (isset($_SESSION['user']) && $_SESSION['user'] instanceof ERegisteredUser) {
            $registeredUser = FPersistentManager::getInstance()->find(ERegisteredUser::class, $_SESSION['user']->getIdRegisteredUser());
            $can_review = FPersistentManager::getInstance()->hasPurchasedProduct($productId);
            if ($can_review) {
                $review_user = FPersistentManager::getInstance()->getReviewUser($registeredUser, $product);
            }
        }

        // Recupera i messaggi dalla sessione
        $successMessage = isset($_SESSION['review_success']) ? $_SESSION['review_success'] : null;
        $errorMessage = isset($_SESSION['review_error']) ? $_SESSION['review_error'] : null;

        // Rimuovi i messaggi dalla sessione dopo averli recuperati
        unset($_SESSION['review_success']);
        unset($_SESSION['review_error']);

        $view->viewProduct($product, $images, $reviews, $same_cat_products, $can_review, $review_user, $successMessage, $errorMessage);
    }

    public static function addToCart($productId)
    {
        if (!(isset($_COOKIE['cart']))) {
            setcookie('cart', 0, time() + (86400 * 30), "/"); // 30 giorni
        }
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $quantity = 1;
        } else {
            $quantity = $_POST['quantity'];
        }
        $cart = json_decode($_COOKIE['cart'], true);
        if (!(empty($cart)) || array_key_exists($productId, $cart)) {
            $cart[$productId] += $quantity;
        } else {
            $cart[$productId] = $quantity;
        }
        $found_product = FPersistentManager::getInstance()->find(EProduct::class, $productId);
        $max_quantity = $found_product->getAvQuantity();
        if($cart[$productId] > $max_quantity) {
            $cart[$productId] = $max_quantity;
            $_SESSION['max_quantity_reached'] = true;
        }
        json_encode($cart);
        setcookie('cart', json_encode($cart), time() + (86400 * 30), "/");

        $_SESSION['added_to_cart'] = isset($_SESSION['max_quantity_reached']) && $_SESSION['max_quantity_reached'] ? false : true;
        header('Location: /EpTech/user/home');
    }


    public static function removeFromCart($productId){
        $cart = json_decode($_COOKIE['cart'], true);
        unset($cart[$productId]);
        json_encode($cart);
        setcookie('cart', json_encode($cart), time() + (300), "/");
        $_SESSION['removed_from_cart'] = true;
        header('Location: /EpTech/user/home');
    }

    public static function emptyCart() {
        if (isset($_COOKIE['cart'])) {
            setcookie('cart', json_encode([]), time() - 3600, "/"); 
            $_SESSION['cart_emptied'] = true;
        }
        header('Location: /EpTech/user/home');
    }

    public static function showCart(){
        if (!(isset($_COOKIE['cart']))) {
            setcookie('cart', json_encode([]), time() + (300), "/");  // 5 minuti
        }
        $view_cart = new VPurchase();
        $view_cart->cart(); 
    }


    public static function updateQuantity($productId){
        if (!isset($_COOKIE['cart'])) {
            setcookie('cart', json_encode([]), time() + (300), "/"); // 5 minuti
        }
        $cart = json_decode($_COOKIE['cart'], true);
        $newQuantity = $_POST['quantity'];
        // Aggiorna la quantità nel carrello
        $cart[$productId] = (int)$newQuantity;

        // Verifica che la nuova quantità non superi la quantità disponibile
        $found_product = FPersistentManager::getInstance()->find(EProduct::class, $productId);
        $max_quantity = $found_product->getAvQuantity();

        if ($cart[$productId] > $max_quantity) {
            $cart[$productId] = $max_quantity;
            $_SESSION['max_quantity_reached'] = true;
        } else {
            $_SESSION['max_quantity_reached'] = false;
        }

        // Se la nuova quantità è 0, rimuovi il prodotto dal carrello
        if ($cart[$productId] == 0) {
            unset($cart[$productId]);
        }

        // Aggiorna il cookie del carrello
        $new_cart = json_encode($cart);
        setcookie('cart', $new_cart, time() + (86400 * 30), "/");
        $_SESSION['qty_updated'] = true;
        header('Location: /EpTech/purchase/showCart');
    }
    
    public static function checkout(){
        $view = new VPurchase();
    
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            
            // Recupera gli indirizzi e le carte di credito dell'utente
            $shipping = FPersistentManager::getInstance()->getAllShippingUser($_SESSION['user']);
            $creditCards = FPersistentManager::getInstance()->getAllCreditCardUser($_SESSION['user']);
            
            // Recupera i prodotti nel carrello
            $cart = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : [];
            $products_cart = [];
            $total_cart = 0;
            
            foreach ($cart as $productId => $quantity) {
                $product = FPersistentManager::getInstance()->find(EProduct::class, $productId);
                if ($product) {
                    $products_cart[] = [
                        'product' => $product,
                        'quantity' => $quantity
                    ];
                    $total_cart += $product->getPriceProduct() * $quantity;
                }
            }
            
            $view->viewCheckoutForm($shipping, $creditCards, $products_cart, $total_cart);
        } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Gestisci il completamento dell'ordine
            $shipping_id = $_POST['shipping'];
            $creditCard_id = $_POST['creditCard'];
            $cart = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : [];
            // Crea un nuovo ordine
            $order = FPersistentManager::getInstance()->newOrder($_SESSION['user'], $shipping_id, $creditCard_id, $cart);
            // Aggiungi i prodotti all'ordine
            $cart = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : [];
            foreach ($cart as $productId => $quantity) {
                $product = FPersistentManager::getInstance()->find(EProduct::class, $productId);
                if ($product) {
                    FPersistentManager::getInstance()->addProductOrder($order, $product, $quantity);
                }
            }
            
            // Svuota il carrello
            setcookie('cart', json_encode([]), time() - 3600, "/");
            
            // Mostra la pagina di conferma dell'ordine
            $view->viewConfirmOrder($order);
        }
    }
    
    
    public static function completeOrder(){
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            header('Location: /EpTech/user/home');
            exit;
        }

        $view = new VPurchase();

        try {
            // Recupera i dati dal form
            $shipping = explode('|', $_POST['shipping']);
            $cardNumber = $_POST['creditCard'];

            // Recupera il carrello dalla sessione o dal cookie
            $cart = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : [];

            if (empty($cart)) {
                throw new \Exception("Il carrello è vuoto");
            }

            // Crea l'ordine
            $order = FPersistentManager::getInstance()->newOrder($shipping[0], $shipping[1], $cardNumber, $cart);

            if (!$order) {
                throw new \Exception("Errore nella creazione dell'ordine");
            }

            // Svuota il carrello
            setcookie('cart', json_encode([]), time() - 3600, "/");

            // Mostra la pagina di conferma dell'ordine
            $view->viewConfirmOrder($order);

        } catch (\Exception $e) {
            // Gestione degli errori
            error_log("Errore durante il completamento dell'ordine: " . $e->getMessage());
            
            // Reindirizza l'utente a una pagina di errore o al carrello con un messaggio di errore
            $_SESSION['error_order'] = "Si è verificato un errore durante il completamento dell'ordine. " . $e->getMessage();
            header('Location: /EpTech/purchase/errorOrder');
            exit;
        }
    }

    public static function detailOrder($orderId)
    {
        $view_user = new VPurchase();
        $order = FPersistentManager::getInstance()->find(EOrder::class, $orderId);

        if ($order && isset($_SESSION['user'])) {
            if ($_SESSION['user'] instanceof ERegisteredUser && $order->getRegisteredUser()->getIdRegisteredUser() == $_SESSION['user']->getIdRegisteredUser()) {
                // Utente registrato: carica i dettagli dell'ordine
                foreach ($order->getItemOrder() as $item) {
                    $item->getProduct()->getImages();
                }
                $view_user->detailOrder($order);
            } elseif ($_SESSION['user'] instanceof EAdmin) {
                // Admin: consenti l'accesso ai dettagli dell'ordine
                foreach ($order->getItemOrder() as $item) {
                    $item->getProduct()->getImages();
                }
                $view_user->detailOrder($order);
            } else {
                // Utente non autorizzato
                header('Location: /EpTech/user/userHistoryOrders');
                exit;
            }
        } else {
            // Ordine non trovato o utente non loggato
            header('Location: /EpTech/user/userHistoryOrders');
            exit;
        }

        /*if ($order && $order->getRegisteredUser()->getIdRegisteredUser() == $_SESSION['user']->getIdRegisteredUser()) {
            // Forza il caricamento delle immagini
            foreach ($order->getItemOrder() as $item) {
                $item->getProduct()->getImages();
            }
            $view_user->detailOrder($order);
        } else {
            header('Location: /EpTech/user/userHistoryOrders');
            exit;
        }*/
    }
}