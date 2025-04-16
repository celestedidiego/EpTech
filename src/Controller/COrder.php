<?php

class COrder
{
    // Metodo per aggiungere un ordine
    public static function addOrder()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $orderData = $_POST['orderData']; // Si assume che i dati dell'ordine siano inviati tramite POST

                // Invece di creare un'istanza di EOrder, passiamo direttamente i dati al gestore persistente
                $userId = $_SESSION['user_id']; // Assuming user ID is stored in session
                FPersistentManager::getInstance()->addOrderData($orderData, $userId);

                $_SESSION['order_success'] = "L'ordine è stato aggiunto con successo!";
            } catch (Exception $e) {
                $_SESSION['order_error'] = "Si è verificato un errore durante l'aggiunta dell'ordine: " . $e->getMessage();
            }

            // Reindirizza alla lista degli ordini
            header("Location: /EpTechProva/order/list");
        }
    }

    // Metodo per modificare un ordine
    public static function editOrder($orderId)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $order = FPersistentManager::getInstance()->find(EOrder::class, $orderId);

                if (!$order) {
                    $_SESSION['order_error'] = "Ordine non trovato.";
                    header("Location: /EpTechProva/order/list");
                    return;
                }

                $orderData = $_POST['orderData']; // Si assume che i dati dell'ordine siano inviati tramite POST

                $order->setOrderData($orderData);

                FPersistentManager::getInstance()->update($order);

                $_SESSION['order_success'] = "L'ordine è stato modificato con successo!";
            } catch (Exception $e) {
                $_SESSION['order_error'] = "Si è verificato un errore durante la modifica dell'ordine: " . $e->getMessage();
            }

            // Reindirizza alla lista degli ordini
            header("Location: /EpTechProva/order/list");
        }
    }

    // Metodo per eliminare un ordine
    public static function deleteOrder($orderId)
    {
        try {
            $order = FPersistentManager::getInstance()->find(EOrder::class, $orderId);

            if (!$order) {
                $_SESSION['order_error'] = "Ordine non trovato.";
                header("Location: /EpTechProva/order/list");
                return;
            }

            FPersistentManager::getInstance()->deleteOrder($order);
            $_SESSION['order_success'] = "L'ordine è stato eliminato con successo!";
        } catch (Exception $e) {
            $_SESSION['order_error'] = "Si è verificato un errore durante l'eliminazione dell'ordine: " . $e->getMessage();
        }

        // Reindirizza alla lista degli ordini
        header("Location: /EpTechProva/order/list");
    }

    // Metodo per elencare gli ordini
    public static function listOrders()
    {
        $page = isset($_GET['orders_page']) ? (int)$_GET['orders_page'] : 1;
        $itemsPerPage = 10;

        $orders = FPersistentManager::getInstance()->getOrders($page, $itemsPerPage);

        $view = new VOrder();
        $view->showOrders($orders, $page, $itemsPerPage);
    }

    // Metodo per visualizzare un ordine
    public static function viewOrder($orderId)
    {
        $order = FPersistentManager::getInstance()->find(EOrder::class, $orderId);

        if (!$order) {
            $_SESSION['order_error'] = "Ordine non trovato.";
            header("Location: /EpTechProva/order/list");
            return;
        }

        $view = new VOrder();
        $view->showOrder($order);
    }

    /*
    public static function requestRefund($orderId) {
        if (!isset($_SESSION['user']) || !($_SESSION['user'] instanceof ERegisteredUser)) {
            header('Location: /EpTechProva/user/login');
            exit;
        }
    
        $order = FPersistentManager::getInstance()->find(EOrder::class, $orderId);
        if ($order && $order->getOrderStatus() === 'Consegnato') {
            FPersistentManager::getInstance()->addRefundRequest($order);
            $_SESSION['success_message'] = "Richiesta di reso o rimborso inviata con successo.";
        } else {
            $_SESSION['error_message'] = "Non è possibile richiedere un reso o rimborso per questo ordine.";
        }
    
        header('Location: /EpTechProva/user/userHistoryOrders');
        exit;
    }
    */

    public static function requestRefund($orderId) {
        if (!isset($_SESSION['user']) || !($_SESSION['user'] instanceof ERegisteredUser)) {
            header('Location: /EpTechProva/user/login');
            exit;
        }

        $order = FPersistentManager::getInstance()->find(EOrder::class, $orderId);
        if ($order && $order->getOrderStatus() === 'Consegnato') {
            if ($order->hasRefundRequest()) {
                $_SESSION['error_message'] = "Hai già effettuato una richiesta di reso o rimborso per questo ordine.";
            } else {
                FPersistentManager::getInstance()->addRefundRequest($order);
                $_SESSION['success_message'] = "Richiesta di reso o rimborso inviata con successo.";
            }
        } else {
            $_SESSION['error_message'] = "Non è possibile richiedere un reso o rimborso per questo ordine.";
        }

        header('Location: /EpTechProva/user/userHistoryOrders');
        exit;
    }
}


