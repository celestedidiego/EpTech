<?php
//echo "Sono dentro CShipping.php<br>";
//require_once __DIR__ . '/../../vendor/autoload.php';
//require_once __DIR__ . '/../../config/bootstrap.php';

class CShipping
{

    /**
     * Aggiunge un nuovo indirizzo di spedizione per l'utente corrente
     */

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
            
            header("Location: /EpTechProva/shipping/addresses");
            //exit();
        }
    }
    
    /**
     * Mostra tutti gli indirizzi di spedizione dell'utente corrente
     */

    public static function showShippingAddresses()
    {
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = "Devi effettuare il login.";
            header("Location: /EpTechProva/login");
            exit();
        }
        
        $userId = $_SESSION['user']->getIdRegisteredUser();
        $shippingAddresses = FPersistentManager::getInstance()->findBy(EShipping::class, ['registered_user_id' => $userId]);

        /*
        // Per il testing senza view
        echo "<h1>I tuoi indirizzi di spedizione</h1>";
        
        if (empty($shippingAddresses)) {
            echo "<p>Non hai ancora aggiunto indirizzi di spedizione.</p>";
        } else {
            echo "<ul>";
            foreach ($shippingAddresses as $address) {
                echo "<li>";
                echo "<strong>Indirizzo:</strong> " . $address->getAddress() . ", " . $address->getCap() . " " . $address->getCity() . "<br>";
                echo "<strong>Destinatario:</strong> " . $address->getRecipientName() . " " . $address->getRecipientSurname() . "<br>";
                echo "<a href='/EpTechProva/shipping/edit/" . $address->getIdShipping() . "'>Modifica</a> | ";
                echo "<a href='/EpTechProva/shipping/delete/" . $address->getIdShipping() . "' onclick='return confirm(\"Sei sicuro di voler eliminare questo indirizzo?\")'>Elimina</a>";
                echo "</li>";
            }
            echo "</ul>";
        }
        
        echo "<p><a href='/EpTechProva/shipping/add'>Aggiungi nuovo indirizzo</a></p>";
        */
        
        // Quando avrai implementato la View, sostituisci il codice sopra con:
         $view = new VShipping();
         $view->showAddresses($shippingAddresses);
    }
    
    /**
     * Elimina un indirizzo di spedizione specifico dell'utente
     * @param int $shippingId ID dell'indirizzo da eliminare
     */

    public static function deleteShippingAddress($shippingId)
    {
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = "Devi effettuare il login.";
            header("Location: /EpTechProva/login");
            //exit();
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
        
        header("Location: /EpTechProva/shipping/addresses");
        //exit();
    }
    
    /**
     * Seleziona un indirizzo per la spedizione dell'ordine corrente
     */

    public static function selectShippingAddress()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user'])) {
            $shippingId = $_POST['shipping_id'];
            $shipping = FPersistentManager::getInstance()->find(EShipping::class, $shippingId);
            
            // Verifichiamo che l'indirizzo appartenga all'utente
            $shippingUserId = FPersistentManager::getInstance()->getShippingUserId($shipping);
            
            if (!$shipping || $shippingUserId != $_SESSION['user']->getIdRegisteredUser()) {
                $_SESSION['error'] = "Indirizzo non valido.";
                header("Location: /EpTechProva/shipping/addresses");
                //exit();
            }
            
            $_SESSION['shipping_id'] = $shippingId;
            $_SESSION['success'] = "Indirizzo selezionato per la spedizione.";
            
            header("Location: /EpTechProva/checkout/payment");
            //exit();
        }
    }
    
    /**
     * Modifica un indirizzo di spedizione esistente
     * @param int $shippingId ID dell'indirizzo da modificare
     */

    public static function editShippingAddress($shippingId)
    {
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = "Devi effettuare il login.";
            header("Location: /EpTechProva/login");
            exit();
        }
        
        $userId = $_SESSION['user']->getIdRegisteredUser();
        $shipping = FPersistentManager::getInstance()->find(EShipping::class, $shippingId);
        
        // Verifichiamo che l'indirizzo appartenga all'utente
        $shippingUserId = FPersistentManager::getInstance()->getShippingUserId($shipping);
        
        if (!$shipping || $shippingUserId != $userId) {
            $_SESSION['error'] = "Non hai i permessi per modificare questo indirizzo.";
            header("Location: /EpTechProva/shipping/addresses");
            //exit();
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
                header("Location: /EpTechProva/shipping/addresses");
                //exit();
            } catch (Exception $e) {
                $_SESSION['error'] = "Errore nella modifica dell'indirizzo: " . $e->getMessage();
            }
        } else {

            /*
            // Per il testing senza view
            echo "<h1>Modifica indirizzo</h1>";
            echo "<form method='post' action='/EpTechProva/shipping/edit/" . $shippingId . "'>";
            echo "Indirizzo: <input type='text' name='address' value='" . $shipping->getAddress() . "' required><br>";
            echo "CAP: <input type='text' name='cap' value='" . $shipping->getCap() . "' required><br>";
            echo "Città: <input type='text' name='city' value='" . $shipping->getCity() . "' required><br>";
            echo "Nome destinatario: <input type='text' name='recipientName' value='" . $shipping->getRecipientName() . "' required><br>";
            echo "Cognome destinatario: <input type='text' name='recipientSurname' value='" . $shipping->getRecipientSurname() . "' required><br>";
            echo "<input type='submit' value='Aggiorna'>";
            echo "</form>";
            */
            
            // Quando avrai implementato la View, sostituisci il codice sopra con:
            $view = new VShipping();
            $view->showEditForm($shipping);
        }
    }
    
    /**
     * Visualizza il form per aggiungere un nuovo indirizzo di spedizione
     */
    public static function showAddForm()
    {
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = "Devi effettuare il login.";
            header("Location: /EpTechProva/login");
            exit();
        }
        
        /*
        // Per il testing senza view
        echo "<h1>Aggiungi indirizzo di spedizione</h1>";
        echo "<form method='post' action='/EpTechProva/shipping/add'>";
        echo "Indirizzo: <input type='text' name='address' required><br>";
        echo "CAP: <input type='text' name='cap' required><br>";
        echo "Città: <input type='text' name='city' required><br>";
        echo "Nome destinatario: <input type='text' name='recipientName' required><br>";
        echo "Cognome destinatario: <input type='text' name='recipientSurname' required><br>";
        echo "<input type='submit' value='Salva'>";
        echo "</form>";
        */
        
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