<?php
class CShipping
{

    public static function addShippingAddress()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user'])) {
            try {
                // Verifica che $_SESSION['user'] sia un array
                if (is_array($_SESSION['user'])) {
                // Converti l'array in un oggetto (esempio con stdClass)
                $_SESSION['user'] = (object) $_SESSION['user'];
            }

                $userId = $_SESSION['user']->getIdRegisteredUser();
                
                $address = $_POST['address'];
                $cap = $_POST['cap'];
                $city = $_POST['city'];
                $recipientName = $_POST['recipientName'];
                $recipientSurname = $_POST['recipientSurname'];
                
                $shipping = new EShipping($address, $cap, $city, $recipientName, $recipientSurname);
                
                // Utilizziamo direttamente l'ID dell'utente registrato
                FPersistentManager::getInstance()->addShippingWithUser($shipping, $userId);
                
                $_SESSION['success'] = "Indirizzo di spedizione aggiunto con successo!";
            } catch (Exception $e) {
                $_SESSION['error'] = "Errore nell'aggiunta dell'indirizzo: " . $e->getMessage();
            }
            
            header("Location: /EpTech/shipping/addresses");
        }
    }

    public static function showShippingAddresses()
    {
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = "Devi effettuare il login.";
            header("Location: /EpTech/login");
            exit();
        }
        
        $userId = $_SESSION['user']->getIdRegisteredUser();
        $shippingAddresses = FPersistentManager::getInstance()->findBy(EShipping::class, ['registered_user_id' => $userId]);
        
        // Quando avrai implementato la View, sostituisci il codice sopra con:
         $view = new VShipping();
         $view->showAddresses($shippingAddresses);
    }

    public static function deleteShippingAddress($shippingId)
    {
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = "Devi effettuare il login.";
            header("Location: /EpTech/login");
        }
        
        $userId = $_SESSION['user']->getIdRegisteredUser();
        $shipping = FPersistentManager::getInstance()->find(EShipping::class, $shippingId);
        
        // Verifichiamo che l'indirizzo appartenga all'utente attraverso l'ID dell'utente associato
        $shippingUserId = FPersistentManager::getInstance()->getShippingUserId($shipping);
        
        if (!$shipping || $shippingUserId != $userId) {
            $_SESSION['error'] = "Non hai i permessi per eliminare questo indirizzo.";
        } else {
            try {
                FPersistentManager::getInstance()->remove($shipping);
                $_SESSION['success'] = "Indirizzo di spedizione eliminato con successo!";
            } catch (Exception $e) {
                $_SESSION['error'] = "Errore nell'eliminazione dell'indirizzo: " . $e->getMessage();
            }
        }
        
        header("Location: /EpTech/shipping/addresses");
    }

    public static function selectShippingAddress()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user'])) {
            $shippingId = $_POST['shipping_id'];
            $shipping = FPersistentManager::getInstance()->find(EShipping::class, $shippingId);
            
            // Verifichiamo che l'indirizzo appartenga all'utente
            $shippingUserId = FPersistentManager::getInstance()->getShippingUserId($shipping);
            
            if (!$shipping || $shippingUserId != $_SESSION['user']->getIdRegisteredUser()) {
                $_SESSION['error'] = "Indirizzo non valido.";
                header("Location: /EpTech/shipping/addresses");
            }
            
            $_SESSION['shipping_id'] = $shippingId;
            $_SESSION['success'] = "Indirizzo selezionato per la spedizione.";
            
            header("Location: /EpTech/checkout/payment");
        }
    }

    public static function editShippingAddress($shippingId)
    {
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = "Devi effettuare il login.";
            header("Location: /EpTech/login");
            exit();
        }
        
        $userId = $_SESSION['user']->getIdRegisteredUser();
        $shipping = FPersistentManager::getInstance()->find(EShipping::class, $shippingId);
        
        // Verifichiamo che l'indirizzo appartenga all'utente
        $shippingUserId = FPersistentManager::getInstance()->getShippingUserId($shipping);
        
        if (!$shipping || $shippingUserId != $userId) {
            $_SESSION['error'] = "Non hai i permessi per modificare questo indirizzo.";
            header("Location: /EpTech/shipping/addresses");
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $shipping->setAddress($_POST['address']);
                $shipping->setCap($_POST['cap']);
                $shipping->setCity($_POST['city']);
                $shipping->setRecipientName($_POST['recipientName']);
                $shipping->setRecipientSurname($_POST['recipientSurname']);
                
                FPersistentManager::getInstance()->update($shipping);
                
                $_SESSION['success'] = "Indirizzo modificato con successo!";
                header("Location: /EpTech/shipping/addresses");
            } catch (Exception $e) {
                $_SESSION['error'] = "Errore nella modifica dell'indirizzo: " . $e->getMessage();
            }
        } else {
            
            // Quando avrai implementato la View, sostituisci il codice sopra con:
            $view = new VShipping();
            $view->showEditForm($shipping);
        }
    }
    
    public static function showAddForm()
    {
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = "Devi effettuare il login.";
            header("Location: /EpTech/login");
            exit();
        }
        
        // Quando avrai implementato la View, sostituisci il codice sopra con:
        $view = new VShipping();
        $view->showAddForm();
    }
    
    /**
     * Recupera un indirizzo di spedizione specifico
     * @param int $shippingId ID dell'indirizzo da recuperare
     * @return EShipping|null L'indirizzo di spedizione o null se non trovato
     */

    public static function getShippingAddress($shippingId)
    {
        if (!isset($_SESSION['user'])) {
            return null;
        }
        
        $shipping = FPersistentManager::getInstance()->find(EShipping::class, $shippingId);
        
        if (!$shipping) {
            return null;
        }
        
        // Verifichiamo che l'indirizzo appartenga all'utente
        $shippingUserId = FPersistentManager::getInstance()->getShippingUserId($shipping);
        
        if ($shippingUserId != $_SESSION['user']->getIdRegisteredUser()) {
            return null;
        }
        
        return $shipping;
    }
}