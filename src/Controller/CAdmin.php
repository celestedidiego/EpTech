<?php

class CAdmin {

    // Prende i dati degli utenti dal PersistentManager per darli a VAdmin.
    public static function manageUsers() {
        $view = new VAdmin();
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $itemsPerPage = 9; // Numero di utenti per pagina
        
        $users_info = FPersistentManager::getInstance()->getAllUsersPaginated($page, $itemsPerPage);
        $view->manageUsers($users_info);
    }

    // Gestisce la visualizzazione di una lista di utenti filtrata o paginata, a seconda dei parametri ricevuti.
    public static function filterUsersPaginated()
    {
        $page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $itemsPerPage = 9;

        if (isset($_POST['adminId']) && !empty($_POST['adminId'])) {
            $adminId = $_POST['adminId'];
            $users = FPersistentManager::getInstance()->getFilteredUsersPaginated($adminId);
        } else {
            $users = FPersistentManager::getInstance()->getAllUsersPaginated($page, $itemsPerPage);
        }

        $view = new VAdmin();
        $view->displayFilteredUsers($users);
    }

    /** 
     * Chiede al PersistentManager di eliminare un utente dati il ruolo e l'ID, invia una mail all'interessato.
     * 
     *  @param int $userId ID dell'utente da eliminare.
     */
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

    /** 
     * Chiede al PersistentManager di bloccare un utente dati il ruolo e l'ID.
     * 
     * @param int $userId ID dell'utente da bloccare.
     */
    public static function blockUser($userId) {
        $entityClass = 'ERegisteredUser';
        $user = FPersistentManager::getInstance()->find($entityClass, $userId);
        if ($user) {
            $user->setBlocked(true);
            FPersistentManager::getInstance()->update($user);

            $mailer = new UEMailer();
            $mailer->sendAccountBlockedEmail($user->getEmail());

            $_SESSION['message'] = "L'utente è stato bloccato con successo.";
        } else {
            $_SESSION['error'] = "Utente non trovato.";
        }
        header('Location: /EpTech/admin/manageUsers');
    }

    /** 
     * Chiede al PersistentManager di sbloccare un utente dati il ruolo e l'ID.
     * 
     * @param int $userId ID dell'utente da sbloccare.
     */
    public static function unblockUser($userId) {
        $entityClass = 'ERegisteredUser';
        $user = FPersistentManager::getInstance()->find($entityClass, $userId);
        if ($user) {
            $user->setBlocked(false);
            FPersistentManager::getInstance()->update($user);

            $mailer = new UEMailer();
            $mailer->sendAccountUnblockedEmail($user->getEmail());

            $_SESSION['message'] = "L'utente è stato sbloccato con successo.";
        } else {
            $_SESSION['error'] = "Utente non trovato.";
        }
        header('Location: /EpTech/admin/manageUsers');
    }

    // Chiede al PersistentManager i dati di tutte i prodotti per darli a VAdmin.
    public static function manageProducts(){
        $view = new VAdmin();
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        
        // Verifica se ci sono messaggi di successo nella sessione.
        $product_added = isset($_SESSION['product_added']) && $_SESSION['product_added'];
        $product_modified = isset($_SESSION['product_modified']) && $_SESSION['product_modified'];
        $product_deleted = isset($_SESSION['product_deleted']) && $_SESSION['product_deleted'];
        
        // Rimuovi i messaggi di successo dalla sessione.
        unset($_SESSION['product_added']);
        unset($_SESSION['product_modified']);
        unset($_SESSION['product_deleted']);

        if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['productId'])){
            $search_term = $_POST['productId'];
        $product = FPersistentManager::getInstance()->getProductById($search_term);
        $categories = FPersistentManager::getInstance()->getAllCategories();
                $brand = FPersistentManager::getInstance()->getAllBrands();
        $view->manageProducts($product, $categories, $brand, $product_added, $product_modified, $product_deleted);
        }else{   
        $array_products = FPersistentManager::getInstance()->getAllProducts($page);
                $categories = FPersistentManager::getInstance()->getAllCategories();
                $brand = FPersistentManager::getInstance()->getAllBrands();
                $view->manageProducts($array_products, $categories, $brand, $product_added, $product_modified, $product_deleted);
        }
    }

    /**
     * Chiede al PersistentManager di cancellare un prodotto dato l'ID, una volta fatto manda una mail all'admin.
     * 
     * @param int $productId ID del prodotto da eliminare.
     */
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

    // Recupera tutti gli ordini dal db e li passa alla vista dell'admin per mostrarli.
    public static function manageOrders() {
        $view = new VAdmin();
        $orders = FPersistentManager::getInstance()->findAll(EOrder::class);
        $view->showManageOrders($orders);
    }

    /**
     * Aggiorna lo stato di un ordine specifico.
     * 
     * @param int $orderId L'ID dell'ordine da aggiornare.
     */
    public static function changeOrderStatus($orderId) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $newStatus = $_POST['orderStatus'];
            $order = FPersistentManager::getInstance()->find(EOrder::class, $orderId);

            if ($order) {
                $order->setOrderStatus($newStatus);
                // Se lo stato è "Consegnato", salva il timestamp di consegna
                if ($newStatus === 'Consegnato') {
                    $order->setDeliveredAt(new \DateTime());
                }
                FPersistentManager::getInstance()->update($order);
                $_SESSION['success_message'] = "Stato dell'ordine aggiornato con successo.";

                // Invio mail all'utente quando l'admin cambia lo stato dell'ordine
                $user = $order->getRegisteredUser();
                if ($user && $user->getEmail()) {
                    $mailer = new UEMailer();
                    $mailer->sendOrderStatusUpdateEmail($user->getEmail(), $order, $newStatus);
                }
            } else {
                $_SESSION['error_message'] = "Ordine non trovato.";
            }
        }

        header('Location: /EpTech/admin/manageOrders');
        exit;
    }

    // Recupera e mostra all'admin le recensioni dei prodotti.
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

    // Mostra all'admin la pagina per gestire le sezioni, utilizzando la vista VAdmin.
    public static function manageSection() {
        $view = new VAdmin();
        $view->showManageSection();
    }

    /**
     * Accetta la richiesta di rimborso associata a un ordine, aggiornandone lo stato se è ancora in attesa, 
     * e reindirizza alla pagina di gestione ordini con un messaggio di conferma o errore.
     * 
     * @param int $orderId L'ID dell'ordine da aggiornare.
     */
    public static function acceptRefund($orderId) {
        $order = FPersistentManager::getInstance()->find(EOrder::class, $orderId);
        if ($order && $order->hasRefundRequest()) {
            $refundRequest = $order->getRefundRequests()[0];
            if ($refundRequest->getStatus() === 'in attesa') {
                $refundRequest->setStatus('accettata');
                FPersistentManager::getInstance()->update($refundRequest);

                $user = $order->getRegisteredUser();
                if ($user) {
                    $mailer = new UEMailer();
                    $mailer->sendRefundAcceptedEmail($user->getEmail(), $order->getIdOrder());
                }

                $_SESSION['success_message'] = "Richiesta di reso/rimborso accettata.";
            } else {
                $_SESSION['error_message'] = "La richiesta è già stata gestita.";
            }
        } else {
            $_SESSION['error_message'] = "Richiesta non trovata.";
        }
        header('Location: /EpTech/admin/manageOrders');
        exit;
    }

    /**
     * Rifiuta una richiesta di rimborso associata a un ordine specifico, aggiornandone lo stato, 
     * e reindirizza l’admin alla pagina di gestione ordini mostrando un messaggio di successo o errore.
     * 
     * @param int $orderId L'ID dell'ordine da aggiornare.
     */
    public static function rejectRefund($orderId) {
        $order = FPersistentManager::getInstance()->find(EOrder::class, $orderId);
        if ($order && $order->hasRefundRequest()) {
            $refundRequest = $order->getRefundRequests()[0];
            if ($refundRequest->getStatus() === 'in attesa') {
                $refundRequest->setStatus('rifiutata');
                FPersistentManager::getInstance()->update($refundRequest);

                $user = $order->getRegisteredUser();
                if ($user) {
                    $mailer = new UEMailer();
                    $mailer->sendRefundRejectedEmail($user->getEmail(), $order->getIdOrder());
                }

                $_SESSION['success_message'] = "Richiesta di reso/rimborso rifiutata.";
            } else {
                $_SESSION['error_message'] = "La richiesta è già stata gestita.";
            }
        } else {
            $_SESSION['error_message'] = "Richiesta non trovata.";
        }
        header('Location: /EpTech/admin/manageOrders');
        exit;
    }

    // Mostra la pagina per aggiungere un nuovo articolo, recuperando gli articoli esistenti da un file JSON.
    public static function newArticle() {
        $view = new VAdmin();
        $articles = json_decode(file_get_contents('./src/Utility/articles.json'), true); // Legge l'articolo salvato
        $view->showNewArticle($articles);
    }

    /**
     * Salva un nuovo articolo, aggiungendolo a un file JSON che funge da archivio, 
     * e poi reindirizza l'admin alla pagina di creazione articoli con un messaggio di conferma.
     */ 
    public static function saveArticle() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Crea il nuovo articolo con i dati ricevuti dal form
            $newArticle = [
                'title' => $_POST['title'],
                'content' => $_POST['content'],
            ];
    
            // Legge gli articoli esistenti dal file JSON
            $articles = json_decode(file_get_contents('./src/Utility/articles.json'), true);
            if (!is_array($articles)) {
                $articles = []; // Se non ci sono articoli, inizializza un array vuoto
            }
    
            $articles[] = $newArticle; // Aggiunge il nuovo articolo
    
            // Salva tutti gli articoli nel file JSON con formattazione leggibile
            file_put_contents('./src/Utility/articles.json', json_encode($articles, JSON_PRETTY_PRINT));
    
            $_SESSION['message'] = "Articolo salvato con successo!";
            header('Location: /EpTech/admin/newArticle');
            exit;
        }
    }
}