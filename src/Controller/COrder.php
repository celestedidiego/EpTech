<?php

class COrder
{
    /**
     * Metodo per aggiungere un ordine
     * 
     * @return void
     */
    public static function addOrder()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $orderData = $_POST['orderData']; // Si assume che i dati dell'ordine siano inviati tramite POST

                // Invece di creare un'istanza di EOrder, passiamo direttamente i dati al gestore persistente
                $userId = $_SESSION['user_id']; // ID utente dalla sessione
                FPersistentManager::getInstance()->addOrderData($orderData, $userId);

                $_SESSION['order_success'] = "L'ordine è stato aggiunto con successo!";
            } catch (Exception $e) {
                $_SESSION['order_error'] = "Si è verificato un errore durante l'aggiunta dell'ordine: " . $e->getMessage();
            }

            // Reindirizza alla lista degli ordini
            header("Location: /EpTech/order/list");
        }
    }

     /**
     * Modifica un ordine esistente.
     *
     * @param int $orderId ID dell'ordine da modificare
     * @return void
     */
    public static function editOrder($orderId)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $order = FPersistentManager::getInstance()->find(EOrder::class, $orderId);

                if (!$order) {
                    $_SESSION['order_error'] = "Ordine non trovato.";
                    header("Location: /EpTech/order/list");
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
            header("Location: /EpTech/order/list");
        }
    }

    /**
     * Elimina un ordine esistente.
     *
     * @param int $orderId ID dell'ordine da eliminare
     * @return void
     */
    public static function deleteOrder($orderId)
    {
        try {
            $order = FPersistentManager::getInstance()->find(EOrder::class, $orderId);

            if (!$order) {
                $_SESSION['order_error'] = "Ordine non trovato.";
                header("Location: /EpTech/order/list");
                return;
            }

            FPersistentManager::getInstance()->deleteOrder($order);
            $_SESSION['order_success'] = "L'ordine è stato eliminato con successo!";
        } catch (Exception $e) {
            $_SESSION['order_error'] = "Si è verificato un errore durante l'eliminazione dell'ordine: " . $e->getMessage();
        }

        // Reindirizza alla lista degli ordini
        header("Location: /EpTech/order/list");
    }

     /**
     * Elenca tutti gli ordini con paginazione.
     *
     * @return void
     */
    public static function listOrders()
    {
        $page = isset($_GET['orders_page']) ? (int)$_GET['orders_page'] : 1;
        $itemsPerPage = 10;

        $orders = FPersistentManager::getInstance()->getOrders($page, $itemsPerPage);

        $view = new VOrder();
        $view->showOrders($orders, $page, $itemsPerPage);
    }

    /**
     * Visualizza i dettagli di un ordine.
     *
     * @param int $orderId ID dell'ordine da visualizzare
     * @return void
     */
    public static function viewOrder($orderId)
    {
        $order = FPersistentManager::getInstance()->find(EOrder::class, $orderId);

        if (!$order) {
            $_SESSION['order_error'] = "Ordine non trovato.";
            header("Location: /EpTech/order/list");
            return;
        }

        $view = new VOrder();
        $view->showOrder($order);
    }

    /**
     * Invia una richiesta di reso o rimborso per un ordine consegnato.
     *
     * @param int $orderId ID dell'ordine per cui richiedere il rimborso
     * @return void
     */
    public static function requestRefund($orderId) {
        if (!isset($_SESSION['user']) || !($_SESSION['user'] instanceof ERegisteredUser)) {
            header('Location: /EpTech/user/login');
            exit;
        }

        $order = FPersistentManager::getInstance()->find(EOrder::class, $orderId);
        if ($order && $order->getOrderStatus() === 'Consegnato') {
            $deliveredAt = $order->getDeliveredAt();
            $now = new \DateTime();
            $expired = false;
            if ($deliveredAt instanceof \DateTime) {
                $interval = $now->getTimestamp() - $deliveredAt->getTimestamp();
                if ($interval > 120) { // 2 minuti = 120 secondi
                    $expired = true;
                }
            } else {
                $expired = true;
            }

            if ($expired) {
                $_SESSION['error_message'] = "Richiesta scaduta";
            } else if ($order->hasRefundRequest()) {
                $_SESSION['error_message'] = "Hai già effettuato una richiesta di reso o rimborso per questo ordine.";
            } else {
                FPersistentManager::getInstance()->addRefundRequest($order);
                $_SESSION['success_message'] = "Richiesta di reso o rimborso inviata con successo.";
            }
        } else {
            $_SESSION['error_message'] = "Non è possibile richiedere un reso o rimborso per questo ordine.";
        }

        header('Location: /EpTech/user/userHistoryOrders');
        exit;
    }
}