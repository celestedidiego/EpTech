<?php   

class CFrontController {
    // Gestisce la richiesta HTTP, determina quale controller e metodo chiamare, e passa i parametri appropriati.
    public function run() {
        session_start(); // Avvia la sessione

        // Ottiene il percorso dell'URL richiesto
        $URL = parse_url($_SERVER['REQUEST_URI'])['path'];
        $URL = explode('/', $URL); // Divide il percorso in segmenti

        array_shift($URL); // Rimuove il primo elemento dell'array (di solito vuoto)

        // Costruisce il percorso del file del controller e il nome della classe del controller
        $file = "./src/Controller/C" . ucfirst($URL[1]) . ".php";
        $controllerClass = "C" . ucfirst($URL[1]);
        $methodName = !empty($URL[2]) ? $URL[2] : 'home'; // Determina il metodo da chiamare, di default 'home'

        // Verifica se il file del controller esiste
        if (file_exists($file)) {
            require_once $file; // Include il file del controller

            // Verifica se il metodo esiste nella classe del controller
            if (method_exists($controllerClass, $methodName)) {

                // Controlla se il metodo è ad accesso pubblico
                if (!$this->isPublic($URL[1], $methodName)) {
                    if (!isset($_SESSION['user'])) {
                        // L'utente non è loggato, reindirizza alla pagina di login
                        header('Location: /EpTechProva/user/login');
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

                // Chiama il metodo del controller con i parametri opzionali
                $params = array_slice($URL, 3); // Ottiene i parametri opzionali dall'URL
                $decodedParams = array_map('urldecode', $params); // Decodifica i parametri URL

                call_user_func_array([$controllerClass, $methodName], $decodedParams); // Chiama il metodo con i parametri
            } else {
                // Metodo non trovato, gestisce l'errore (es. mostra una pagina 404)
                header('Location: /EpTechProva/user/home');
                exit;
            }
        } else {
            // File del controller non trovato, reindirizza alla home page
            header('Location: /EpTechProva/user/home');
            exit;
        }
    }

    private function isPublic($controller, $method) {
        // Definisce le rotte pubbliche
        $publicRoutes = [
            'user' => ['home', 'login', 'logout', 'signUp', 'confirmEmail'], // Rotte pubbliche per il controller 'utente'
            'purchase' => ['viewProduct','shop','addToCart', 'showCart', 'removeFromCart', 'emptyCart', 'updateQuantity'], // Rotte pubbliche per il controller 'purchase'
            //'cart' => ['showCart', 'showAddForm', 'addProduct', 'removeProduct', 'emptyCart', 'totalPrice', 'changeQuantity'], // Rotte pubbliche per il controller 'cart'
            // Aggiungi altri controller e metodi pubblici se necessario
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

    private function hasPermission($roleUser, $controller, $method) {
        // Define your user roles and permissions here
        $rolePermissions = [
            'admin' => [
                'user' => ['userDataForm', 'userDataSection', 'deleteAccount', 'changePass', 'changeUserData'],
                'admin' => ['manageUsers','filterUsersPaginated', 'deleteUser', 'blockUser', 'unblockUser', 'manageProducts', 'deleteProduct', 'manageReviews','manageOrders', 'changeOrderStatus', 'manageSection'],
                'product' => ['listProducts', 'addProduct', 'modifyProduct', 'deleteProduct'],
                'order' => ['listOrders', 'viewOrder', 'deleteOrder', 'editOrder'],
                'review' => ['listReviews', 'canRespond', 'respondToReview'],
                'shipping' => ['listShipping', 'editShipping', 'deleteShipping'],

                // Add more controllers and methods for admin
            ],
            'registered_user' => [
                'user' => ['userDataForm', 'userDataSection', 'userHistoryOrders', 'deleteAccount', 'changePass', 'changeUserData', 'shipping', 'creditCards', 'addCards', 'deleteCreditCard','addShipping', 'deleteShipping', 'reactivateShipping', 'reactiveCreditCard'],
                'purchase' => ['effettuaCheckout', 'completeOrder', 'errorOrder', 'detailOrder', ],
                'review' => ['addReview', 'editReview'],
                //'cart' => ['showCart', 'showAddForm', 'addProduct', 'removeProduct', 'emptyCart', 'totalPrice', 'changeQuantity'],
                'shipping' => ['editShipping', 'deleteShipping'],
                // Add more controllers and methods for acquirente
            ],
            'user_blocked' => [
                'user' => ['home', 'login', 'logout'],
                // Add more controllers and methods for venditore
            ],
            // Add more roles as needed
        ];

        return isset($rolePermissions[$roleUser][$controller]) && in_array($method, $rolePermissions[$roleUser][$controller]);
    }

}