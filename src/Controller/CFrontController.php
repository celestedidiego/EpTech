<?php   

class CFrontController {
    // Gestisce la richiesta HTTP, determina quale controller e metodo chiamare, e passa i parametri appropriati.
    public function run() {
        session_start(); // Avvia la sessione

        // Ottiene il percorso dell'URL richiesto.
        $URL = parse_url($_SERVER['REQUEST_URI'])['path'];
        $URL = explode('/', $URL); // Divide il percorso in segmenti

        array_shift($URL); // Rimuove il primo elemento dell'array (di solito vuoto)

        // Costruisce il percorso del file del controller e il nome della classe del controller.
        $file = "./src/Controller/C" . ucfirst($URL[1]) . ".php";
        $controllerClass = "C" . ucfirst($URL[1]);
        $methodName = !empty($URL[2]) ? $URL[2] : 'home'; // Determina il metodo da chiamare, di default 'home'

        // Verifica se il file del controller esiste.
        if (file_exists($file)) {
            require_once $file; // Include il file del controller

            // Verifica se il metodo esiste nella classe del controller.
            if (method_exists($controllerClass, $methodName)) {

                // Controlla se il metodo è ad accesso pubblico.
                if (!$this->isPublic($URL[1], $methodName)) {
                    if (!isset($_SESSION['user'])) {
                        // L'utente non è loggato, reindirizza alla pagina di login
                        header('Location: /EpTech/user/login');
                        exit;
                    } else if (!$this->hasPermission($_SESSION['role'], $URL[1], $methodName)) {
                        // L'utente loggato non ha i permessi per accedere a questo metodo
                        http_response_code(403); // Imposta il codice di risposta HTTP a 403 (accesso non autorizzato)
                        $view = new VUser();
                        if ($_SESSION['role'] == "user_blocked") {
                            $view->accessDenied(); // Mostra la pagina di accesso negato
                        } else {
                            $view->accessUnAuthorized(); // Mostra la pagina di accesso non autorizzato
                        }
                        exit;
                    }
                }

                // Chiama il metodo del controller con i parametri opzionali.
                $params = array_slice($URL, 3); // Ottiene i parametri opzionali dall'URL
                $decodedParams = array_map('urldecode', $params); // Decodifica i parametri URL

                call_user_func_array([$controllerClass, $methodName], $decodedParams); // Chiama il metodo con i parametri
            } else {
                // Metodo non trovato, gestisce l'errore (es. mostra una pagina 404)
                header('Location: /EpTech/user/home');
                exit;
            }
        } else {
            // File del controller non trovato, reindirizza alla homepage
            header('Location: /EpTech/user/home');
            exit;
        }
    }

    /** Verifica se il controller e il metodo sono pubblici.
     * 
     * @param string $controller Nome del controller
     * @param string $method Nome del metodo
     * @return bool Restituisce true se il controller e il metodo sono pubblici, false altrimenti.
     */
    private function isPublic($controller, $method) {
        // Definisce le rotte pubbliche
        $publicRoutes = [
            'user' => ['home', 'login', 'logout', 'signUp', 'confirmEmail', 'aboutUs'], // Rotte pubbliche per il controller 'utente'
            'purchase' => ['viewProduct','shop','addToCart', 'showCart', 'removeFromCart', 'emptyCart', 'updateQuantity'], // Rotte pubbliche per il controller 'purchase'
        ];

        // Se l'utente ha il ruolo "user_blocked", ridefinisce le rotte pubbliche
        if(isset($_SESSION['role']) && $_SESSION['role'] == "user_blocked"){
            $publicRoutes = [
                'user' => ['home', 'login', 'logout'], // Rotte pubbliche per gli utenti bloccati
            ];
        }

        // Verifica se il controller e il metodo sono definiti nelle rotte pubbliche
        return isset($publicRoutes[$controller]) && in_array($method, $publicRoutes[$controller]);
    }

    /**
     * Verifica se un determinato ruolo utente ha i permessi per accedere a un metodo specifico.
     *
     * @param string $roleUser Ruolo dell'utente (es. admin, registered_user, user_blocked).
     * @param string $controller Nome del controller richiesto.
     * @param string $method Metodo del controller richiesto.
     * @return bool True se l'utente ha i permessi, false altrimenti.
     */
    private function hasPermission($roleUser, $controller, $method) {
        // Definisce i permessi per ogni ruolo.
        $rolePermissions = [
            'admin' => [
                'user' => ['userDataForm', 'userDataSection', 'deleteAccount', 'changePass', 'changeUserData'],
                'admin' => ['manageUsers','filterUsersPaginated', 'deleteUser', 'blockUser', 'unblockUser', 'manageProducts', 'deleteProduct', 'manageReviews','manageOrders', 'changeOrderStatus', 'manageSection', 'acceptRefund', 'rejectRefund', 'newArticle', 'saveArticle'],
                'product' => ['listProducts', 'addProduct', 'modifyProduct', 'deleteProduct'],
                'order' => ['listOrders', 'viewOrder'],
                'review' => ['listReviews', 'canRespond', 'respondToReview'],
                'shipping' => ['listShipping', 'editShipping', 'deleteShipping'],
                'purchase' => ['detailOrder'],
            ],
            'registered_user' => [
                'user' => ['userDataForm', 'userDataSection', 'userHistoryOrders', 'deleteAccount', 'changePass', 'changeUserData', 'shipping', 'creditCards', 'addCards', 'deleteCreditCard','addShipping', 'deleteShipping', 'reactivateShipping', 'reactiveCreditCard'],
                'purchase' => ['checkout', 'completeOrder', 'errorOrder', 'detailOrder', ],
                'review' => ['add', 'edit'],
                'order' => ['requestRefund'], 
                'shipping' => ['editShipping', 'deleteShipping'],
            ],
            'user_blocked' => [
                'user' => ['home', 'login', 'logout'],
            ],
        ];

        return isset($rolePermissions[$roleUser][$controller]) && in_array($method, $rolePermissions[$roleUser][$controller]);
    }

}