<?php
<?php

class CPurchase {

    public static function shop() {
        $view = new VPurchase();

        $filters = [
            'query' => isset($_GET['query']) ? $_GET['query'] : '',
            'category' => isset($_GET['category']) ? $_GET['category'] : '',
            'brand' => isset($_GET['brand']) ? $_GET['brand'] : '',
            'prezzo_max' => isset($_GET['prezzo_max']) ? (int)$_GET['prezzo_max'] : 5000,
            'order_by' => isset($_GET['order_by']) ? $_GET['order_by'] : '',
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

        $successMessage = isset($_SESSION['review_success']) ? $_SESSION['review_success'] : null;
        $errorMessage = isset($_SESSION['review_error']) ? $_SESSION['review_error'] : null;

        unset($_SESSION['review_success']);
        unset($_SESSION['review_error']);

        $view->viewProduct($product, $images, $reviews, $same_cat_products, $can_review, $review_user, $successMessage, $errorMessage);
    }

    // Aggiunge un prodotto al carrello
    public static function addToCart($productId)
    {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $quantity = 1;
        } else {
            $quantity = $_POST['quantity'];
        }
        $cart = $_SESSION['cart'];
        if (isset($cart[$productId])) {
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
        $_SESSION['cart'] = $cart;

        $_SESSION['added_to_cart'] = isset($_SESSION['max_quantity_reached']) && $_SESSION['max_quantity_reached'] ? false : true;
        header('Location: /EpTech/purchase/viewProduct/' . $productId);
    }

    // Rimuove un prodotto dal carrello
    public static function removeFromCart($productId){
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        $cart = $_SESSION['cart'];
        unset($cart[$productId]);  //rimuove il prodotto dal carrello
        $_SESSION['cart'] = $cart;
        $_SESSION['removed_from_cart'] = true;

        header('Location: /EpTech/purchase/showCart');
        exit;
    }

    // Svuota il carrello
    public static function emptyCart() {
        $_SESSION['cart'] = [];
        $_SESSION['cart_emptied'] = true;
        header('Location: /EpTech/user/home');
    }

    // Mostra la pagina del carrello
    public static function showCart(){
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        $view_cart = new VPurchase();
        $view_cart->cart(); 
    }

    // Aggiorna la quantità di un prodotto nel carrello
    public static function updateQuantity($productId){
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        $cart = $_SESSION['cart'];
        $newQuantity = $_POST['quantity'];
        $cart[$productId] = (int)$newQuantity;

        $found_product = FPersistentManager::getInstance()->find(EProduct::class, $productId);
        $max_quantity = $found_product->getAvQuantity();

        if ($cart[$productId] > $max_quantity) {
            $cart[$productId] = $max_quantity;
            $_SESSION['max_quantity_reached'] = true;
        } else {
            $_SESSION['max_quantity_reached'] = false;
        }

        if ($cart[$productId] == 0) {
            unset($cart[$productId]);
        }

        $_SESSION['cart'] = $cart;
        $_SESSION['qty_updated'] = true;
        header('Location: /EpTech/purchase/showCart');
    }
    
    // Gestisce la visualizzazione e il completamento del checkout
    public static function checkout(){
        $view = new VPurchase();

        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $shipping = FPersistentManager::getInstance()->getAllShippingUser($_SESSION['user']);
            $creditCards = FPersistentManager::getInstance()->getAllCreditCardUser($_SESSION['user']);
            $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
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
            try {
                $shipping_id = $_POST['shipping'];
                $creditCard_id = $_POST['creditCard'];
                $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
                $order = FPersistentManager::getInstance()->newOrder($_SESSION['user'], $shipping_id, $creditCard_id, $cart);
                $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
                foreach ($cart as $productId => $quantity) {
                    $product = FPersistentManager::getInstance()->find(EProduct::class, $productId);
                    if ($product) {
                        FPersistentManager::getInstance()->addProductOrder($order, $product, $quantity);
                    }
                }
                $_SESSION['cart'] = [];
                $view->viewConfirmOrder($order);
            } catch (\Exception $e) {
                $_SESSION['error_order'] = "Si è verificato un errore durante il completamento dell'ordine. " . $e->getMessage();
                header('Location: /EpTech/purchase/errorOrder');
                exit;
            }
        }
    }
    
    // Completa l’ordine inviato dal form (POST)
    public static function completeOrder(){
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            header('Location: /EpTech/user/home');
            exit;
        }

        $view = new VPurchase();

        try {
            $shipping = explode('|', $_POST['shipping']);
            $cardNumber = $_POST['creditCard'];
            $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

            if (empty($cart)) {
                throw new \Exception("Il carrello è vuoto");
            }

            $order = FPersistentManager::getInstance()->newOrder($shipping[0], $shipping[1], $cardNumber, $cart);

            if (!$order) {
                throw new \Exception("Errore nella creazione dell'ordine");
            }

            if (isset($_SESSION['user']) && $_SESSION['user'] instanceof ERegisteredUser) {
                $mailer = new UEMailer();
                $mailer->sendOrderConfirmationEmail($_SESSION['user']->getEmail(), $order);
            }

            $_SESSION['cart'] = [];

            $view->viewConfirmOrder($order);

        } catch (\Exception $e) {
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
                foreach ($order->getItemOrder() as $item) {
                    $item->getProduct()->getImages();
                }
                $view_user->detailOrder($order);
            } elseif ($_SESSION['user'] instanceof EAdmin) {
                foreach ($order->getItemOrder() as $item) {
                    $item->getProduct()->getImages();
                }
                $view_user->detailOrder($order);
            } 
        } else {
            header('Location: /EpTech/user/userHistoryOrder');
            exit;
        }
    }

    public static function errorOrder() {
        $view = new VOrder();
        $errorMessage = isset($_SESSION['error_order']) ? $_SESSION['error_order'] : "Si è verificato un errore sconosciuto.";
        unset($_SESSION['error_order']);
        $view->showOrderError($errorMessage);
    }
}