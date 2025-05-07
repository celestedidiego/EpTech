<?php

class CAdmin {

    // Prende i dati degli utenti dal PersistentManager per darli a VAdmin
    public static function manageUsers() {
        $view = new VAdmin();
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $itemsPerPage = 9; // Numero di utenti per pagina
        
        $users_info = FPersistentManager::getInstance()->getAllUsersPaginated($page, $itemsPerPage);
        $view->manageUsers($users_info);
    }

    public static function filterUsersPaginated()
    {
        $page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $itemsPerPage = 9;

        if (isset($_POST['adminId']) && !empty($_POST['adminId'])) {
            $adminId = $_POST['adminId'];
            $users = FPersistentManager::getInstance()->getFilteredUsersPaginated($adminId, $page, $itemsPerPage);
        } else {
            $users = FPersistentManager::getInstance()->getAllUsersPaginated($page, $itemsPerPage);
        }

        $view = new VAdmin();
        $view->displayFilteredUsers($users);
    }

    // Chiede al PersistentManager di eliminare un utente dati il ruolo e l'ID, invia una mail all'interessato.
    public static function deleteUser($userId) {
        $entityClass = 'ERegisteredUser';
        $user = FPersistentManager::getInstance()->find($entityClass, $userId);
        if ($user) {
            FPersistentManager::getInstance()->softDeleteUser($user);
            
            $mailer = new UEMailer();
            $mailer->sendAccountDeletionEmail($user->getEmail());
            
            $_SESSION['message'] = "L'utente è stato eliminato con successo.";
        } else {
            $_SESSION['error'] = "Utente non trovato.";
        }
        header('Location: /EpTech/admin/manageUsers');
    }

    // Chiede al PersistentManager di bloccare un utente dati il ruolo e l'ID.

    public static function blockUser($userId) {
        $entityClass = 'ERegisteredUser';
        $user = FPersistentManager::getInstance()->find($entityClass, $userId);
        if ($user) {
            $user->setBlocked(true);
            FPersistentManager::getInstance()->update($user);
            $_SESSION['message'] = "L'utente è stato bloccato con successo.";
        } else {
            $_SESSION['error'] = "Utente non trovato.";
        }
        header('Location: /EpTech/admin/manageUsers');
    }

    // Chiede al PersistentManager di sbloccare un utente dati il ruolo e l'ID.
    public static function unblockUser($userId) {
        $entityClass = 'ERegisteredUser';
        $user = FPersistentManager::getInstance()->find($entityClass, $userId);
        if ($user) {
            $user->setBlocked(false);
            FPersistentManager::getInstance()->update($user);
            $_SESSION['message'] = "L'utente è stato sbloccato con successo.";
        } else {
            $_SESSION['error'] = "Utente non trovato.";
        }
        header('Location: /EpTech/admin/manageUsers');
    }

    //chiede al PersistentManager i dati di tutte i prodotti per darli a VAdmin
    public static function manageProducts(){
        $view = new VAdmin();
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        
        // Verifica se ci sono messaggi di successo nella sessione
        $product_added = isset($_SESSION['product_added']) && $_SESSION['product_added'];
        $product_modified = isset($_SESSION['product_modified']) && $_SESSION['product_modified'];
        $product_deleted = isset($_SESSION['product_deleted']) && $_SESSION['product_deleted'];
        
        // Rimuovi i messaggi di successo dalla sessione
        unset($_SESSION['product_added']);
        unset($_SESSION['product_modified']);
        unset($_SESSION['product_deleted']);

        if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['productId'])){
            $search_term = $_POST['productId'];
        $product = FPersistentManager::getInstance()->getProductById($search_term, $page, $pageSize = 4);
        $categories = FPersistentManager::getInstance()->getAllCategories();
                $brand = FPersistentManager::getInstance()->getAllBrands();
        $view->manageProducts($product, $categories, $brand, $product_added, $product_modified, $product_deleted);
        }else{   
        $array_products = FPersistentManager::getInstance()->getAllProducts($page, $pageSize = 4);
                $categories = FPersistentManager::getInstance()->getAllCategories();
                $brand = FPersistentManager::getInstance()->getAllBrands();
                $view->manageProducts($array_products, $categories, $brand, $product_added, $product_modified, $product_deleted);
        }
    }

    //chiede al PersistentManager di cancellare un prodotto dato l'ID, una volta fatto manda una mail al venditore di riferimento
    public static function deleteProduct($productId) {
        if (!isset($_SESSION['user']) || !($_SESSION['user'] instanceof EAdmin)) {
            header('Location: /EpTech/user/login');
            exit;
        }
        try{
            $result = FPersistentManager::getInstance()->deleteProduct($productId);
            $product= FPersistentManager::getInstance()->find(EProduct::class, $productId);
            $user= $product->getAdmin();

            $mailer = new UEMailer();
                $mailer->sendProductDeletionEmail($user->getEmail(), $product->getNameProduct());
                $_SESSION['message'] = "Prodotto eliminato.";

        }catch(\Exception $e){
            $_SESSION['error'] = "Si è verificato un errore".$e->getMessage();
        }
            
        header('Location: /EpTech/admin/manageProducts');
        exit;
        
    }

    public static function manageOrders() {
        $view = new VAdmin();
        $orders = FPersistentManager::getInstance()->findAll(EOrder::class);
        $view->showManageOrders($orders);
    }

    public static function changeOrderStatus($orderId) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $newStatus = $_POST['orderStatus'];
            $order = FPersistentManager::getInstance()->find(EOrder::class, $orderId);

            if ($order) {
                $order->setOrderStatus($newStatus);
                FPersistentManager::getInstance()->update($order);
                $_SESSION['success_message'] = "Stato dell'ordine aggiornato con successo.";
            } else {
                $_SESSION['error_message'] = "Ordine non trovato.";
            }
        }

        header('Location: /EpTech/admin/manageOrders');
        exit;
    }

    public static function manageReviews() {
        $view = new VAdmin();
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $itemsPerPage = 10;
    
        if (isset($_GET['product_name']) && !empty($_GET['product_name'])) {
            // Filtra per nome prodotto
            $productName = $_GET['product_name'];
            $reviews = FPersistentManager::getInstance()->getReviewsByProductName($productName, $page, $itemsPerPage);
        } else {
            // Recupera tutte le recensioni
            $reviews = FPersistentManager::getInstance()->getAllReviewsPaginated($page, $itemsPerPage);
        }
    
        if (!isset($reviews['items']) || !is_array($reviews['items'])) {
            $reviews['items'] = [];
        }
    
        $view->manageReviews($reviews);
    }

    public static function manageSection() {
        $view = new VAdmin();
        $view->showManageSection();
    }

    public static function acceptRefund($orderId) {
        $order = FPersistentManager::getInstance()->find(EOrder::class, $orderId);
        if ($order && $order->hasRefundRequest()) {
            $refundRequest = $order->getRefundRequests()[0];
            if ($refundRequest->getStatus() === 'pending') {
                $refundRequest->setStatus('accepted');
                FPersistentManager::getInstance()->update($refundRequest);
                $_SESSION['success_message'] = "Richiesta di reso o rimborso accettata.";
            } else {
                $_SESSION['error_message'] = "La richiesta è già stata gestita.";
            }
        } else {
            $_SESSION['error_message'] = "Richiesta non trovata.";
        }
        header('Location: /EpTech/admin/manageOrders');
        exit;
    }

    public static function rejectRefund($orderId) {
        $order = FPersistentManager::getInstance()->find(EOrder::class, $orderId);
        if ($order && $order->hasRefundRequest()) {
            $refundRequest = $order->getRefundRequests()[0];
            if ($refundRequest->getStatus() === 'pending') {
                $refundRequest->setStatus('rejected');
                FPersistentManager::getInstance()->update($refundRequest);
                $_SESSION['success_message'] = "Richiesta di reso o rimborso rifiutata.";
            } else {
                $_SESSION['error_message'] = "La richiesta è già stata gestita.";
            }
        } else {
            $_SESSION['error_message'] = "Richiesta non trovata.";
        }
        header('Location: /EpTech/admin/manageOrders');
        exit;
    }

    public static function newArticle() {
        $view = new VAdmin();
        $articles = json_decode(file_get_contents('./src/Utility/articles.json'), true); // Legge l'articolo salvato
        $view->showNewArticle($articles);
    }

    public static function saveArticle() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $newArticle = [
                'title' => $_POST['title'],
                'content' => $_POST['content'],
            ];
    
            $articles = json_decode(file_get_contents('./src/Utility/articles.json'), true);
            if (!is_array($articles)) {
                $articles = [];
            }
    
            $articles[] = $newArticle; // Aggiungi il nuovo articolo
    
            // Salva tutti gli articoli con JSON_PRETTY_PRINT per formattazione leggibile
            file_put_contents('./src/Utility/articles.json', json_encode($articles, JSON_PRETTY_PRINT));
    
            $_SESSION['message'] = "Articolo salvato con successo!";
            header('Location: /EpTech/admin/newArticle');
            exit;
        }
    }
}