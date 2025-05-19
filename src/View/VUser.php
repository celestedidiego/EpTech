<?php

use Doctrine\ORM\Query\Expr;

/**
 * Class VUser
 * Gestisce la visualizzazione e le operazioni relative all'utente tramite Smarty.
 */
class VUser {
    /**
     * @var Smarty
     */
    private $smarty;

    /**
     * VUser constructor.
     * Inizializza la configurazione di Smarty e assegna le variabili di carrello.
     */
    public function __construct() {
        // Configura Smarty utilizzando il metodo statico configuration della classe StartSmarty
        $this->smarty = StartSmarty::configuration();

        // Assegna la quantità totale di articoli nel carrello alla variabile Smarty 'cart_quantity'
        $this->smarty->assign('cart_quantity', self::countItemCart());
        
        // Recupera i dati del carrello utilizzando il metodo cart_header
        $data = self::cart_header();
        
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
     * Gestisce il contenuto del carrello dell'utente.
     * @return array Array contenente i prodotti nel carrello, il subtotale e il carrello.
     */
    public function cart_header(){
        if (!isset($_COOKIE['cart'])) {
            setcookie('cart', json_encode([]), time() + (300), "/"); // 5 minuti
        } // Controllo e creazione del cookie cart
        // Viene inizializzato un array $array_carrello vuoto e viene decodificato il contenuto del cookie cart in un array associativo $carrello
        $array_cart = [];
        $cart = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : [];

        // Recupero dei dettagli dei prodotti nel carrello
        
        if($cart){
            foreach($cart as $id => $qty){
                $product = FPersistentManager::getInstance()->find(EProduct::class, $id);
                $array_cart[] = [
                    'product' => $product,
                    'quantity' => $qty
                ];
            } 
        }

        // Calcolo del subtotale del carrello
        $subtotal = 0;
        if(!empty($array_cart)){
            foreach($array_cart as $item){
                $subtotal += $item['product']->getPriceProduct() * $item['quantity'];
            }
        }
        // Restituzione dell'array del carrello, del subtotale e del carrello
        return [
            'array_cart' => $array_cart ? $array_cart : [],
            'subtotal' => $subtotal,
            'cart' => $cart
        ];
    }

    /**
     * Conta il numero totale di articoli presenti nel carrello dell'utente.
     * @return int Numero totale di articoli nel carrello.
     */
    public function countItemCart()
    {
        if (!(isset($_COOKIE['cart']))) {
            return 0;
        }
        $cart = json_decode($_COOKIE['cart']);
        $cont = 0;
        foreach ($cart as $id => $quantity) {
            $cont += $quantity;
        }
        return $cont;
    }

    /**
     * Verifica lo stato di login dell'utente e restituisce le variabili di login.
     * @return array Variabili di login.
     */
    public function checkLogin()
    {
        $loginVariables = [
            'check_login_registered_user' => 0,
            'check_login_admin' => 0,
            'user_not_logged' => 1
        ];

        if (isset($_SESSION['user'])) {
            $loginVariables['user_not_logged'] = 0;
            if ($_SESSION['user'] instanceof ERegisteredUser) {
                $loginVariables['check_login_registered_user'] = 1;
            } else if ($_SESSION['user'] instanceof EAdmin) {
                $loginVariables['check_login_admin'] = 1;
            }
        }
        return $loginVariables;
    }

    /**
     * Mostra la pagina di accesso negato.
     * @return void
     */
    public function accessDenied()
    {
        $this->smarty->display('accessDenied.tpl');
    }

    /**
     * Mostra la pagina di accesso non autorizzato.
     * @return void
     */
    public function accessUnAuthorized(){
        $this->smarty->display('accessUnAuthorized.tpl');
    }

    /**
     * Mostra il form di login.
     * @return void
     */
    public function showLoginForm(){
        $this->smarty->display('login.tpl');
    }

    /**
     * Gestisce il successo del login di un utente e prepara i dati per la homepage.
     * @param array $array_product Prodotti da mostrare.
     * @param array $array_category Categorie da mostrare.
     * @param array $article Articoli da mostrare.
     * @return void
     */
    public function loginSuccessUser($array_product, $array_category, $article){
        // Recupero delle variabili di login
        $loginVariables = self::checkLogin();
        foreach ($loginVariables as $key => $value) {
            $this->smarty->assign($key, $value);
        }
        // Gestione della variabile di sessione cart_emptied (carrello vuoto)
        $this->smarty->assign('cart_emptied', 0);
        $cart_emptied = isset($_SESSION['cart_emptied']) && $_SESSION['cart_emptied'];
        unset($_SESSION['cart_emptied']);
        if ($cart_emptied) {
            $this->smarty->assign('cart_emptied', 1);
        }
        // Gestione della variabile di sessione removed_from_cart (rimosso dal carrello)
        $this->smarty->assign('removed_from_cart', 0);
        $removed_from_cart = isset($_SESSION['removed_from_cart']) && $_SESSION['removed_from_cart'];
        unset($_SESSION['removed_from_cart']);
        if ($removed_from_cart) {
            $this->smarty->assign('removed_from_cart', 1);
        }
        // Gestione della variabile di sessione added_to_cart (aggiunto al carrello)
        $this->smarty->assign('added_to_cart', 0);
        $added_to_cart = isset($_SESSION['added_to_cart']) && $_SESSION['added_to_cart'];
        unset($_SESSION['added_to_cart']);
        if ($added_to_cart) {
            $this->smarty->assign('added_to_cart', 1);
        }
        // Gestione della variabile di sessione max_quantity_reached (raggiunta la quantità massima)
        $this->smarty->assign('max_quantity_reached', 0);
        $max_quantity_reached = isset($_SESSION['max_quantity_reached']) && $_SESSION['max_quantity_reached'];
        unset($_SESSION['max_quantity_reached']);
        if ($max_quantity_reached) {
            $this->smarty->assign('max_quantity_reached', 1);
        }
        // Assegna le variabili necessarie per la visualizzazione della homepage
        $this->smarty->assign('login_error', 0);
        $this->smarty->assign('search_bar', 1);
        $this->smarty->assign('array_category', $array_category);
        $this->smarty->assign('array_product', $array_product);
        $this->smarty->assign('article', $article);
        $this->smarty->display('homepage.tpl');
    }

    /**
     * Prepara i dati per la visualizzazione dell'admin dopo il login.
     * @return void
     */
    public function loginSuccessAdmin(){
        $loginVariables = self::checkLogin();
        foreach ($loginVariables as $key => $value) {
            $this->smarty->assign($key, $value);
        }
        $this->smarty->assign('name', $_SESSION['user']->getName());
        $this->smarty->assign('surname', $_SESSION['user']->getSurname());
        $this->smarty->assign('email', $_SESSION['user']->getEmail());
        $this->smarty->assign('username', $_SESSION['user']->getUsername());
        $this->smarty->assign('search_form', 1);
        $this->smarty->assign('admin', 1);
        $this->smarty->display('userinfo.tpl');
    }

    /**
     * Gestisce la visualizzazione di un messaggio di errore quando il login fallisce.
     * @return void
     */
    public function loginError(){
        $this->smarty->assign('error_log', 1);
        $this->smarty->display('login.tpl');
    }


    /**
     * Gestisce il logout dell'utente e prepara i dati per la homepage.
     * @param array $array_product Prodotti da mostrare.
     * @param array $array_category Categorie da mostrare.
     * @param array $article Articoli da mostrare.
     * @return void
     */
    public function logout($array_product, $array_category, $article){
        $loginVariables = self::checkLogin();
        foreach ($loginVariables as $key => $value) {
            $this->smarty->assign($key, $value);
        }
        $this->smarty->assign('cart_emptied', 0);
        $cart_emptied = isset($_SESSION['cart_emptied']) && $_SESSION['cart_emptied'];
        unset($_SESSION['cart_emptied']);
        if ($cart_emptied) {
            $this->smarty->assign('cart_emptied', 1);
        }
        $this->smarty->assign('removed_from_cart', 0);
        $removed_from_cart = isset($_SESSION['removed_from_cart']) && $_SESSION['removed_from_cart'];
        unset($_SESSION['removed_from_cart']);
        if ($removed_from_cart) {
            $this->smarty->assign('removed_from_cart', 1);
        }
        $this->smarty->assign('added_to_cart', 0);
        $added_to_cart = isset($_SESSION['added_to_cart']) && $_SESSION['added_to_cart'];
        unset($_SESSION['added_to_cart']);
        if ($added_to_cart) {
            $this->smarty->assign('added_to_cart', 1);
        }
        $this->smarty->assign('max_quantity_reached', 0);
        $max_quantity_reached = isset($_SESSION['max_quantity_reached']) && $_SESSION['max_quantity_reached'];
        unset($_SESSION['max_quantity_reached']);
        if ($max_quantity_reached) {
            $this->smarty->assign('max_quantity_reached', 1);
        }
        $this->smarty->assign('search_bar', 1);
        $this->smarty->assign('array_category', $array_category);
        $this->smarty->assign('array_product', $array_product);
        $this->smarty->assign('article', $article);
        $this->smarty->assign('signUpSuccess', 0);
        // Verifica se il messaggio di successo è presente nella sessione
        $signUpSuccess = isset($_SESSION['signUpSuccess']) && $_SESSION['signUpSuccess'];
    
        // Rimuovi il messaggio di successo dalla sessione
        unset($_SESSION['signUpSuccess']);
        // Controlla se il metodo è stato chiamato dalla form per aggiungere un prodotto
        if ($signUpSuccess) {
            $this->smarty->assign('signUpSuccess', 1);
        }
        $this->smarty->display('homepage.tpl');
    }

    /**
     * Mostra il form di registrazione.
     * @return void
     */
    public function signUp(){
        $this->smarty->display('registration.tpl');
    }

    /**
     * Indica che la password deve essere controllata durante la registrazione.
     * @return void
     */
    public function checkPassSignUp(){
        $this->smarty->assign('check_pass', 1);
        $this->smarty->display('registration.tpl');
    }

    /**
     * Indica che c'è stato un errore durante la registrazione.
     * @return void
     */
    public function signUpError(){
        $this->smarty->assign('errore_r', 1);
        $this->smarty->display('registration.tpl');
    }
    
    /**
     * Verifica se l'utente è loggato, assegna le variabili di login 
     * e i dati dell'utente al template, e visualizza il template dei dati utente.
     * @return void 
     * */ 
    public function userDataForm(){
        $loginVariables = self::checkLogin();
        foreach ($loginVariables as $key => $value) {
            $this->smarty->assign($key, $value);
        }
        $this->smarty->assign('name', $_SESSION['user']->getName());
        $this->smarty->assign('surname', $_SESSION['user']->getSurname());
        // Se l'utente è un amministratore, non visualizzare il campo username
        if(!($_SESSION['user'] instanceof EAdmin)){
            $this->smarty->assign('username', $_SESSION['user']->getUsername());
        }
        $this->smarty->assign('email', $_SESSION['user']->getEmail());
        $this->smarty->assign('userDataForm', 1);
        $this->smarty->display('userinfo.tpl');
    }
    
    /**
    * Verifica se l'utente è loggato, gestisce i messaggi di successo relativi alla modifica dei dati utente e della password, assegna i dati dell'utente al template e visualizza la sezione dei dati utente. 
    * Questo permette di mostrare un form precompilato con le informazioni dell'utente loggato e di indicare eventuali successi nelle operazioni di modifica.
    * @return void
    */
    public function userDataSection(){
        $loginVariables = self::checkLogin();
        foreach ($loginVariables as $key => $value) {
            $this->smarty->assign($key, $value);
        }
        $this->smarty->assign('changeuserdatasucces', 0);
        $this->smarty->assign('changepasswordsucces', 0);
        // Verifica se il messaggio di successo è presente nella sessione
        $changeuserdatasucces = isset($_SESSION['changeuserdatasucces']) && $_SESSION['changeuserdatasucces'];
        $changepasswordsucces = isset($_SESSION['changepasswordsucces']) && $_SESSION['changepasswordsucces'];

        // Rimuovi il messaggio di successo dalla sessione
        unset($_SESSION['changeuserdatasucces']);
        unset($_SESSION['changepasswordsucces']);
        // Controlla se il metodo è stato chiamato dalla form per aggiungere un prodotto
        if ($changeuserdatasucces) {
            $this->smarty->assign('changeuserdatasucces', 1);
        }
        if ($changepasswordsucces) {
            $this->smarty->assign('changepasswordsucces', 1);
        }
        $this->smarty->assign('name', $_SESSION['user']->getName());
        $this->smarty->assign('surname', $_SESSION['user']->getSurname());
        if(!($_SESSION['user'] instanceof EAdmin)){
            $this->smarty->assign('username', $_SESSION['user']->getUsername());
        }
        $this->smarty->assign('email', $_SESSION['user']->getEmail());
        $this->smarty->assign('userDataSection', 1);
        $this->smarty->display('userinfo.tpl');
    }

    /** 
    * Verifica se l'utente è loggato, assegna le variabili di login e la cronologia degli ordini al template, e visualizza la sezione della cronologia degli ordini. 
    * Questo permette di mostrare all'utente una lista dei suoi ordini passati.
    * @param array $orders Cronologia degli ordini dell'utente.
    * @return void
    */
    public function userHistoryOrders($orders){
        $loginVariables = self::checkLogin();
        foreach ($loginVariables as $key => $value) {
            $this->smarty->assign($key, $value);
        }
        $this->smarty->assign('orders', $orders);
        $this->smarty->assign('userHistoryOrders', 1);
        $this->smarty->display('userinfo.tpl');
    }

    /**
     * Mostra il form per cambiare la password.
     * @return void
     */
    public function changePass(){
        $loginVariables = self::checkLogin();
        foreach ($loginVariables as $key => $value) {
            $this->smarty->assign($key, $value);
        }
        $this->smarty->assign('changepass', 1);
        $this->smarty->display('userinfo.tpl');
    }

    /**
     * Mostra il form di cambio password con messaggio di se l'aggiornamento della
     * password non è riuscito.
     * @return void
     */
    public function errorPassUpdate(){
        $loginVariables = self::checkLogin();
        foreach ($loginVariables as $key => $value) {
            $this->smarty->assign($key, $value);
        }
        $this->smarty->assign('changepass', 1);
        $this->smarty->assign('errorpassupdate', 1);
        $this->smarty->display('userinfo.tpl');
    }

    /**
     * Mostra il form di cambio password con errore se il cambio della vecchia password
     * non è riuscito.
     * @return void
     */
    public function errorOldPass(){
        $loginVariables = self::checkLogin();
        foreach ($loginVariables as $key => $value) {
            $this->smarty->assign($key, $value);
        }
        $this->smarty->assign('changepass', 1);
        $this->smarty->assign('erroroldpass', 1);
        $this->smarty->display('userinfo.tpl');
    }

    /**
     * Mostra il form di cambio password con errore se la vecchia password è uguale alla
     * nuova password.
     * @return void
     */
    public function equalPasswordError() {
        $loginVariables = self::checkLogin();
        foreach ($loginVariables as $key => $value) {
            $this->smarty->assign($key, $value);
        }
        $this->smarty->assign('changepass', 1);
        $this->smarty->assign('equalpassworderr', 1);
        $this->smarty->display('userinfo.tpl');
    }

    /**
     * Mostra la sezione degli indirizzi di spedizione dell'utente.
     * @param array $array_shipping Indirizzi di spedizione.
     * @param array $messages Messaggi opzionali.
     * @return void
     */
    public function shipping($array_shipping, $messages = []) {
        $loginVariables = self::checkLogin();
        foreach ($loginVariables as $key => $value) {
            $this->smarty->assign($key, $value);
        }
        $this->smarty->assign('array_shipping', $array_shipping);
        $this->smarty->assign('messages', $messages);
        $this->smarty->assign('shippings', 1);
        $this->smarty->display('userinfo.tpl');
    }

    /**
     * Mostra il form per aggiungere un nuovo indirizzo di spedizione.
     * @return void
     */
    public function addShipping(){
        $loginVariables = self::checkLogin();
        foreach ($loginVariables as $key => $value) {
            $this->smarty->assign($key, $value);
        }
        $this->smarty->assign('addShipping', 1);
        $this->smarty->display('userinfo.tpl');
    }

    /**
     * Mostra il form per aggiungere un nuovo indirizzo di spedizione con eventuali
     * messsaggi di errore.
     * @param array $errors Errori da mostrare.
     * @return void
     */
    public function addShippingWithError($errors) {
        $loginVariables = self::checkLogin();
        foreach ($loginVariables as $key => $value) {
            $this->smarty->assign($key, $value);
        }
        $this->smarty->assign('addShipping', 1);
        $this->smarty->assign('errors', $errors);
        $this->smarty->display('userinfo.tpl');
    }

    /**
     * Mostra la sezione degli indirizzi di spedizione con messaggio di
     * errore se la cancellazione non è riuscita.
     * @return void
     */
    public function errorDeleteShipping() {
        $loginVariables = self::checkLogin();
        foreach ($loginVariables as $key => $value) {
            $this->smarty->assign($key, $value);
        }
        $this->smarty->assign('shipping', 1);
        $this->smarty->assign('errorDeleteShipping', 1);
        $this->smarty->display('userinfo.tpl');
    }

    /**
     * Mostra la sezione delle carte di credito dell'utente.
     * @param array $credit_cards Carte di credito.
     * @param array $messages Messaggi opzionali.
     * @return void
     */
    public function creditCards($credit_cards, $messages = []) {
        $loginVariables = self::checkLogin();
        foreach ($loginVariables as $key => $value) {
            $this->smarty->assign($key, $value);
        }
        $this->smarty->assign('credit_cards', $credit_cards);
        $this->smarty->assign('messages', $messages);
        $this->smarty->assign('creditCards', 1);
        $this->smarty->display('userinfo.tpl');
    }

    /**
     * Mostra la sezione delle carte di credito con errore di cancellazione.
     * @return void
     */
    public function errorDeleteCard(){
        $loginVariables = self::checkLogin();
        foreach ($loginVariables as $key => $value) {
            $this->smarty->assign($key, $value);
        }
        $this->smarty->assign('errorDeleteCard', true);
        $this->smarty->assign('creditCards', true);
        $this->smarty->display('userinfo.tpl');
    }

    /**
     * Mostra il form per aggiungere una nuova carta di credito.
     * @return void
     */
    public function addCards(){
        $loginVariables = self::checkLogin();
        foreach ($loginVariables as $key => $value) {
            $this->smarty->assign($key, $value);
        }
        $this->smarty->assign('addCards', 1);
        $this->smarty->display('userinfo.tpl');
    }
    /**
     * Mostra il form per aggiungere una nuova carta di credito 
     * con eventuali messaggi di errore.
     * @param array $errors Errori da mostrare.
     * @return void
     */
    public function addCardsWithErrors($errors) {
        $this->smarty->assign('errors', $errors);
        $this->smarty->assign('addCards', 1);
        $this->smarty->display('userinfo.tpl');
    }

    /**
     * Mostra la pagina di successo della registrazione.
     * @param string $message Messaggio di successo.
     * @return void
     */
    public function signUpSuccess($message) {
        $this->smarty->assign('success_message', $message);
        $this->smarty->display('signUpSuccess.tpl');
    }
    
    /**
     * Mostra la pagina "Chi siamo".
     * @return void
     */
    public function showAboutUs() {
        $loginVariables=(new VUser)->checkLogin();
        foreach ($loginVariables as $key => $value){
            $this->smarty->assign($key, $value);
        }
        $this->smarty->display('aboutUs.tpl'); // Mostra il template della pagina "Chi siamo"
    }
}