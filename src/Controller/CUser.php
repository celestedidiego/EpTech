<?php

class CUser {

    // Questo metodo gestisce la visualizzazione della homepage. Controlla se l'utente è loggato e mostra la homepage appropriata per utenti registrati, amministratori o utenti non loggati. 
    // Inoltre, imposta un cookie per il carrello se non esiste già.
    public static function home() {
        $view_home = new VUser();
        //$array_product = FPersistentManager::getInstance()->getAllProducts();
        $array_product = FPersistentManager::getInstance()->getLatestProductsHome();
        $array_category = FPersistentManager::getInstance()->getAllCategories();

        if (!isset($_COOKIE['cart'])) {
            setcookie('cart', json_encode([]), time() + (300), "/"); // 5 minuti
        }

        if (static::isLogged()) {
            if ($_SESSION['user'] instanceof ERegisteredUser) {
                $view_home->loginSuccessUser($array_product, $array_category);
            } else if ($_SESSION['user'] instanceof EAdmin) {
                $view_home->loginSuccessAdmin();
            }
        } else {
            $view_home->logout($array_product, $array_category);
        }
        
    }
    
    // Gestisce il processo di login. Se la richiesta è GET, mostra il form di login. Se la richiesta è POST, verifica le credenziali dell'utente e, se corrette, imposta la sessione e i cookie appropriati
    public static function login() {
        $view = new VUser();
        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            if (static::isLogged()) {
                header('Location: /EpTechProva/user/home');
            } else {
                $view->showLoginForm();
            }
        } elseif ($_SERVER['REQUEST_METHOD'] == "POST") {
            $email = $_POST['email-log']; // Recupera l'email dal form
            $password = $_POST['password-log']; // Recupera la password dal form
            $user = FPersistentManager::getInstance()->findUtente($email); // Cerca l'utente nel database
            if ($user == null) {
                // Login fallito
                $view->loginError(); // Mostra errore se l'utente non esiste
            } else if (password_verify($password, $user[0]->getPassword())) {
                $_SESSION['user'] = $user[0];
                if ($_SESSION['user'] instanceof ERegisteredUser) {
                    $_SESSION['role'] = 'registered_user'; // Imposta il ruolo per gli utenti registrati
                } elseif ($_SESSION['user'] instanceof EAdmin) {
                    $_SESSION['role'] = 'admin'; // Imposta il ruolo per gli amministratori
                }

                // Imposta un cookie di autenticazione
                if (!isset($_COOKIE['auth'])) {
                    setcookie('auth', base64_encode($user[0]->getEmail()), time() + (300), "/"); // 5 minuti
                }

                // Reindirizza alla home
                header('Location: /EpTechProva/user/home');
                exit; // Assicurati di terminare lo script dopo il reindirizzamento
            } else {
                $view->loginError();
            }
        }
    }
    
    // Controlla se l'utente è loggato verificando l'esistenza di un cookie di sessione e una variabile di sessione.
    public static function isLogged()
    {
        $identificato = false;
        // Controlla se il cookie di sessione esiste
        if (isset($_COOKIE['PHPSESSID'])) {
            // Avvia la sessione se non è già avviata
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            // Controlla se l'utente è loggato basandosi su una variabile di sessione specifica
            if (isset($_SESSION['user'])) {
                $identificato = true;
            }
        }
        return $identificato;
    }

    // Gestisce il logout dell'utente, distruggendo la sessione e reindirizzando alla homepage.
    public static function logout(){
        session_unset();
        session_destroy();
        header('Location: /EpTechProva/user/home');
    }

    public static function signUp(){
        $view_registeredUser = new VUser();
        if($_SERVER['REQUEST_METHOD'] == "GET"){
            $view_registeredUser->signUp();
        } elseif ($_SERVER['REQUEST_METHOD'] == "POST"){
            $postData = $_POST;
            foreach ($postData as $key => $value) {
                $array_data[$key] = $value;
            }
    
            // Convert birthDate to DateTime object
            $birthDate = \DateTime::createFromFormat('Y-m-d', $array_data['birthDate']);
            if (!$birthDate) {
                $view_registeredUser->signUpError(); // Handle invalid date format
                return;
            }
            
            // Crea un nuovo utente registrato
            $new_user = new ERegisteredUser(
                $array_data['name'],
                $array_data['surname'],
                $array_data['email'],
                $birthDate,
                $array_data['username'],
                password_hash($array_data['password'], PASSWORD_DEFAULT),
            );
    
            // Crea un oggetto temporaneo per verificare l'esistenza dell'email
            $temp = new ERegisteredUser(
                null, // Default date if no valid date is provided
                null,
                $new_user->getEmail(),
                new \DateTime('1900-01-01'),
                null,
                null,
            );
    
            // Controlla se l'email esiste già
            $same_class_new_user = FPersistentManager::getInstance()->findUtente($new_user);
            $check_email = FPersistentManager::getInstance()->findUtente($temp);
    
            if ($check_email != null || ($check_email == null && $same_class_new_user != null)) {
                // Se l'email esiste già, ricarica la form per la registrazione con un errore
                $view_registeredUser->signUpError();
                return;
            }

            // Salva l'utente nel database
            FPersistentManager::getInstance()->insertNewUtente($new_user);

            // Genera un token di conferma
            $confirmationToken = bin2hex(random_bytes(32));
            $new_user->setConfirmationToken($confirmationToken);
            FPersistentManager::getInstance()->update($new_user);

            // Invia l'email di conferma
            $mailer = new UEMailer();
            $confirmationLink = "http://localhost/EpTechProva/user/confirmEmail?token=" . $confirmationToken;
            $mailer->sendEmailConfirmation($new_user->getEmail(), $confirmationLink);

            // Mostra un messaggio di successo
            $view_registeredUser->signUpSuccess("Registrazione completata. Controlla la tua email per confermare l'account.");
        }
    }

    public static function confirmEmail() {
        if (isset($_GET['token'])) {
            $token = $_GET['token'];
            $user = FPersistentManager::getInstance()->findOneBy(ERegisteredUser::class, ['confirmationToken' => $token]);
    
            if ($user) {
                $user->setConfirmationToken(null); // Rimuovi il token
                $user->setEmailConfirmed(true); // Imposta l'email come confermata
                FPersistentManager::getInstance()->update($user);
    
                $_SESSION['message'] = "Email confermata con successo. Ora puoi accedere.";
                header('Location: /EpTechProva/user/login');
            } else {
                $_SESSION['error'] = "Token non valido o scaduto.";
                header('Location: /EpTechProva/user/login');
            }
        } else {
            $_SESSION['error'] = "Token non fornito.";
            header('Location: /EpTechProva/user/login');
        }
    }

    // Mostra il form per modificare i dati dell'utente se l'utente è loggato, altrimenti reindirizza al form di login.
    public static function userDataForm()
    {
        $view_user = new VUser();
        if (static::isLogged()) {
            $view_user->userDataForm();
        } else {
            header('Location: /EpTechProva/user/login');
        }
    }

    // Mostra la sezione dei dati dell'utente se l'utente è loggato, altrimenti reindirizza al form di login.
    public static function userDataSection()
    {
        $view_user = new VUser();
        if (static::isLogged()) {
            $view_user->userDataSection();
        } else {
            header('Location: /EpTechProva/user/login');
        }
    }

    // Mostra la cronologia degli ordini dell'utente.
    public static function userHistoryOrders()
    {
        $view_user = new VUser();
        $orders = FPersistentManager::getInstance()->getOrderUser();
        $view_user->userHistoryOrders($orders);
    }

    // Elimina l'account dell'utente loggato e distrugge la sessione, reindirizzando alla homepage.
    public static function deleteAccount()
    {
        $user = $_SESSION['user'];
        FPersistentManager::getInstance()->deleteUtente($user);
        session_unset();
        session_destroy();
        header('Location: /EpTechProva/user/home');
    }

    public static function changePass() {
        $view = new VUser();
        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            if(isset($_SESSION['user'])){
                $view->changePass();
            }else{
                header('Location: /EpTechProva/user/login');
            }
        } elseif ($_SERVER['REQUEST_METHOD'] == "POST") {
            $password_old = $_POST['password'];
            if (password_verify($password_old, $_SESSION['user']->getPassword())) {
                $new_password = $_POST['new-password'];
                $confirm_password = $_POST['new-confirm-password'];
                if ($new_password != $password_old) {
                    if ($new_password == $confirm_password) {
                        FPersistentManager::getInstance()->updatePass($_SESSION['user'], $new_password);
                        $_SESSION['changepasswordsucces'] = true;
                        header('Location: /EpTechProva/user/userDataSection');
                    } else {
                        $view->errorPassUpdate();
                    }
                } elseif ($new_password == $password_old) {
                    $view->equalPasswordError();
                } 
            } else {
                $view->errorOldPass();
            }
        }
    }

    public static function changeUserData()
    {
        $view = new VUser();
        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            if(isset($_SESSION['user'])){
                $view->userDataForm();
            }else{
                header('Location: /EpTechProva/user/login');
            }
        } elseif ($_SERVER['REQUEST_METHOD'] == "POST") {
            $postData = $_POST;
            foreach ($postData as $key => $value) {
                $array_data[$key] = $value;
            }
            FPersistentManager::getInstance()->updateUtente($_SESSION['user'], $array_data);

            //Aggiorno la sessione con i nuovi dati aggiornati
            $updated_cliente = FPersistentManager::getInstance()->findUser($_SESSION['user']);
            $_SESSION['user'] = $updated_cliente[0];
            $_SESSION['changeuserdatasucces'] = true;
            header('Location: /EpTechProva/user/userDataSection');
        }
    }

    public static function shipping() {
        $view_user = new VUser();
        $array_shipping = FPersistentManager::getInstance()->getAllShippingUser($_SESSION['user']);
        
        $messages = [];
        if (isset($_SESSION['address_deleted'])) {
            $messages['success'] = "L'indirizzo è stato eliminato con successo.";
            unset($_SESSION['address_deleted']);
        }
        if (isset($_SESSION['address_added'])) {
            $messages['success'] = "L'indirizzo è stato aggiunto con successo.";
            unset($_SESSION['address_added']);
        }
        if (isset($_SESSION['address_reactivated'])) {
            $messages['success'] = "L'indirizzo è stato riattivato con successo.";
            unset($_SESSION['address_reactivated']);
        }
        if (isset($_SESSION['address_soft_deleted'])) {
            $messages['info'] = "L'indirizzo è stato nascosto ma non completamente eliminato poiché è associato a ordini esistenti.";
            unset($_SESSION['address_soft_deleted']);
        }
        if (isset($_SESSION['address_error'])) {
            $messages['error'] = $_SESSION['address_error'];
            unset($_SESSION['address_error']);
        }
        
        $view_user->shipping($array_shipping, $messages);
    }

    public static function addShipping() {
        $view = new VUser();
        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            $view->addShipping();
        } elseif ($_SERVER['REQUEST_METHOD'] == "POST") {
            $postData = $_POST;
            $errors = [];

            // Validazione dei campi
            if (empty($postData['address'])) {
                $errors[] = "L'indirizzo è obbligatorio.";
            }
            if (empty($postData['cap']) || !preg_match('/^\d{5}$/', $postData['cap'])) {
                $errors[] = "Il CAP deve essere composto da 5 cifre.";
            }
            if (empty($postData['city'])) {
                $errors[] = "La città è obbligatoria.";
            }
            if (empty($postData['recipientName'])) {
                $errors[] = "Il nome del destinatario è obbligatorio.";
            }
            if (empty($postData['recipientSurname'])) {
                $errors[] = "Il cognome del destinatario è obbligatorio.";
            }

            if (empty($errors)) {
                // Passa i dati al metodo insertShipping
                FPersistentManager::getInstance()->insertShipping($postData);
                $_SESSION['address_added'] = true;
                header('Location: /EpTechProva/user/shipping');
            } else {
                // Mostra errori
                $view->addShippingWithError($errors);
            }
        }
    }

    public static function deleteShipping($address, $cap) {
        $found_shipping = FPersistentManager::getInstance()->findShipping($address, $cap);
        
        if ($found_shipping) {
            if (FPersistentManager::getInstance()->canShippingBeHardDeleted($address, $cap)) {
                FPersistentManager::getInstance()->deleteShipping($found_shipping[0]);
                $_SESSION['address_deleted'] = true;
            } else {
                FPersistentManager::getInstance()->softDeleteShipping($found_shipping[0]);
                $_SESSION['address_soft_deleted'] = true;
            }
        } else {
            $_SESSION['address_error'] = "Errore: l'indirizzo non è stato trovato.";
        }
        
        header('Location: /EpTechProva/user/shipping');
        exit();
    }

    public static function reactivateShipping($address, $cap) {
        $found_shipping = FPersistentManager::getInstance()->findShipping($address, $cap);
        
        if ($found_shipping) {
            FPersistentManager::getInstance()->reactivateShipping($found_shipping[0]);
            $_SESSION['address_reactivated'] = true;
        } else {
            $_SESSION['address_error'] = "Errore: l'indirizzo non è stato trovato.";
        }
        
        header('Location: /EpTechProva/user/shipping');
        exit();
    }

    public static function addCards() {
        $view = new VUser();
        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            $view->addCards();
        } elseif ($_SERVER['REQUEST_METHOD'] == "POST") {
            $postData = $_POST;
            $errors = self::validateCreditCardData($postData);
            
            if (empty($errors)) {
                try {
                    FPersistentManager::getInstance()->insertCreditCard($postData);
                    $_SESSION['credit_card_added'] = true;
                    header('Location: /EpTechProva/user/creditCards');
                    exit;
                } catch (Exception $e) {
                    $errors[] = "Errore durante l'inserimento della carta: " . $e->getMessage();
                }
            }
            
            if (!empty($errors)) {
                $view->addCardsWithErrors($errors);
            }
        }
    }

    private static function validateCreditCardData($data) {
        $errors = [];

        // Validazione nome e cognome
        if (!preg_match("/^[a-zA-Z\s]+$/", $data['cardHolderName'])) {
            $errors[] = "Il nome e il cognome devono contenere solo lettere e spazi.";
        }

        // Validazione numero carta
        if (!preg_match("/^\d{16}$/", $data['cardNumber'])) {
            $errors[] = "Il numero della carta deve essere composto da 16 cifre.";
        }

        // Validazione scadenza
        if (!preg_match("/^(0[1-9]|1[0-2])\/\d{2}$/", $data['endDate'])) {
            $errors[] = "La data di scadenza deve essere nel formato MM/YY.";
        } else {
            $expiration = \DateTime::createFromFormat('m/y', $data['endDate']);
            if (!$expiration) {
                $errors[] = "Formato data non valido.";
            } else {
                $now = new \DateTime();
                if ($expiration < $now) {
                    $errors[] = "La carta di credito è scaduta.";
                }
            }
        }

        // Validazione CVV
        if (!preg_match("/^\d{3}$/", $data['cvv'])) {
            $errors[] = "Il CVV deve essere composto da 3 cifre.";
        }

        return $errors;
    }

    public static function creditCards() {
        $view_user = new VUser();
        $cards = FPersistentManager::getInstance()->getAllCreditCardUser($_SESSION['user']);
        
        $messages = [];
        if (isset($_SESSION['card_added'])) {
            $messages['success'] = "La carta di credito è stato aggiunta con successo.";
            unset($_SESSION['card_added']);
        }
        if (isset($_SESSION['card_deleted'])) {
            $messages['success'] = "La carta di credito è stata eliminata con successo.";
            unset($_SESSION['card_deleted']);
        }
        if (isset($_SESSION['card_reactivated'])) {
            $messages['success'] = "La carta di credito è stata riattivata con successo.";
            unset($_SESSION['card_reactivated']);
        }
        if (isset($_SESSION['card_soft_deleted'])) {
            $messages['info'] = "La carta di credito è stata nascosta ma non completamente eliminata poiché è associata a ordini esistenti.";
            unset($_SESSION['card_soft_deleted']);
        }
        if (isset($_SESSION['card_error'])) {
            $messages['error'] = $_SESSION['card_error'];
            unset($_SESSION['card_error']);
        }
        
        $view_user->creditCards($cards, $messages);
    }

    public static function deleteCreditCard($number) {
        $found_card = FPersistentManager::getInstance()->findCreditCard($number);
        
        if ($found_card) {
            if (FPersistentManager::getInstance()->canCreditCardBeHardDeleted($number)) {
                FPersistentManager::getInstance()->deleteCreditCard($found_card[0]);
                $_SESSION['card_deleted'] = true;
            } else {
                FPersistentManager::getInstance()->softDeleteCreditCard($found_card[0]);
                $_SESSION['card_soft_deleted'] = true;
            }
        } else {
            $_SESSION['card_error'] = "Errore: la carta di credito non è stata trovata.";
        }
        
        header('Location: /EpTechProva/user/creditCards');
        exit();
    }

    public static function reactivateCreditCard($number) {
        $found_card = FPersistentManager::getInstance()->findCreditCard($number);
        
        if ($found_card) {
            FPersistentManager::getInstance()->reactivateCreditCard($found_card[0]);
            $_SESSION['card_reactivated'] = true;
        } else {
            $_SESSION['card_error'] = "Errore: la carta di credito non è stata trovata.";
        }
        
        header('Location: /EpTechProva/user/creditCards');
        exit();
    }

    public static function searchProducts() {
        if (!isset($_SESSION['user']) || !($_SESSION['user'] instanceof EAdmin)) {
            header('Location: /EpTechProva/user/login');
            exit;
         }
     
         $view_admin = new VAdmin();
     
         if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['search'])) {
             $search_term = $_POST['search'];
             $products = FPersistentManager::getInstance()->getProductById($search_term);
             $view_admin->displaySearchResults($products);
         } 
     }

    public static function deleteProduct($id) {
        if (!isset($_SESSION['user']) || !($_SESSION['user'] instanceof EAdmin)) {
            header('Location: /EpTechProva/user/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == "POST") {

            $result = FPersistentManager::getInstance()->deleteProduct($id);
            if ($result) {
                $_SESSION['product_deleted'] = true;
            } else {
                $_SESSION['product_delete_error'] = true;
            }
            header('Location: /EpTechProva/user/manageProducts');
            exit;
        }
    }

}