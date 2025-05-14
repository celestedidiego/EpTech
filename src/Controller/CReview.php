<?php

class CReview
{

    /**
     * Aggiunge una recensione per un prodotto.
     * 
     * @param int $productId ID del prodotto per il quale si desidera aggiungere una recensione.
     * 
     * @return void
     */
    public static function add($productId)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $view = new VReview();

            // Verifica se l'utente ha acquistato il prodotto prima di permettere l'aggiunta della recensione
            if (!FPersistentManager::getInstance()->hasPurchasedProduct($productId)) {
                $_SESSION['review_error'] = "Non puoi recensire un prodotto che non hai acquistato.";
                header("Location: /EpTech/purchase/viewProduct/" . $productId);
                return;
            }

            $user = FPersistentManager::getInstance()->find(ERegisteredUser::class, $_SESSION['user']->getIdRegisteredUser());
            $product = FPersistentManager::getInstance()->find(EProduct::class, $productId);

            // Verifica se esiste già una recensione per il prodotto da parte dell'utente
            $existingReview = FPersistentManager::getInstance()->getReviewUser($user, $product);
            if ($existingReview) {
                $_SESSION['review_error'] = "Hai già scritto una recensione per questo prodotto. Puoi modificarla ma non aggiungerne una nuova.";
                header("Location: /EpTech/purchase/viewProduct/" . $productId);
            } else {
                try {
                    // Aggiunge la recensione
                    $text = $_POST['text'];
                    $vote = $_POST['vote'];

                    $review = new EReview();
                    $review->setText($text);
                    $review->setVote($vote);
                    $review->setRegisteredUser($user);
                    $review->setProduct($product);

                    FPersistentManager::getInstance()->addReview($review);

                    $_SESSION['review_success'] = "La tua recensione è stata aggiunta con successo!";
                } catch (Exception $e) {
                    $_SESSION['review_error'] = "Si è verificato un errore durante l'aggiunta della recensione: " . $e->getMessage();
                }

                header("Location: /EpTech/purchase/viewProduct/" . $productId);
            }
        }
    }

    /**
     * Modifica una recensione esistente per un prodotto.
     * 
     * @param int $productId ID del prodotto per il quale si desidera modificare la recensione.
     * 
     * @return void
     */
    public static function edit($productId)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = FPersistentManager::getInstance()->find(ERegisteredUser::class, $_SESSION['user']->getIdRegisteredUser());
            $product = FPersistentManager::getInstance()->find(EProduct::class, $productId);

            // Verifica se l'utente ha acquistato il prodotto prima di permettere la modifica della recensione
            if (!FPersistentManager::getInstance()->hasPurchasedProduct($productId)) {
                $_SESSION['review_error'] = "Non puoi modificare una recensione per un prodotto che non hai acquistato.";
                header("Location: /EpTech/purchase/viewProduct/" . $productId);
                return;
            }

            $reviewId = $_POST['idReview'];
            $review = FPersistentManager::getInstance()->find(EReview::class, $reviewId);

            // Verifica se la recensione appartiene all'utente
            if ($review->getRegisteredUser()->getIdRegisteredUser() != $user->getIdRegisteredUser()) {
                $_SESSION['review_error'] = "Non puoi modificare una recensione che non ti appartiene.";
                header("Location: /EpTech/purchase/viewProduct/" . $productId);
            } else {
                try {
                    // Modifica la recensione
                    $text = $_POST['text'];
                    $vote = $_POST['vote'];

                    $review->setText($text);
                    $review->setVote($vote);

                    FPersistentManager::getInstance()->addReview($review);

                    $_SESSION['review_success'] = "La tua recensione è stata modificata con successo!";
                } catch (Exception $e) {
                    $_SESSION['review_error'] = "Si è verificato un errore durante la modifica della recensione: " . $e->getMessage();
                }

                header("Location: /EpTech/purchase/viewProduct/" . $productId);
            }
        }
    }
    
    /**
     * Permette all'admin di rispondere a una recensione.
     * 
     * @param int $reviewId ID della recensione a cui si desidera rispondere.
     * 
     * @return void
     */
    public static function respondToReview($reviewId)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Recupera l'admin e la recensione
            $admin = FPersistentManager::getInstance()->find(EAdmin::class, $_SESSION['user']->getIdAdmin());
            $review = FPersistentManager::getInstance()->find(EReview::class, $reviewId);

            // Controlla se l'admin è autorizzato a rispondere
            if (!self::canRespond($admin, $review)) {
                $_SESSION['error'] = "Non sei autorizzato a rispondere a questa recensione.";
                header("Location: /EpTech/admin/manageReviews");
                exit;
            }

            // Recupera la risposta dal form
            $response = $_POST['risposta'];

            // Imposta la risposta dell'admin
            $review->setResponseAdmin($response, $admin);

            // Salva la modifica nel database
            FPersistentManager::getInstance()->flush();

            // Imposta un messaggio di successo
            $_SESSION['success'] = "Risposta inviata con successo.";

            // Reindirizza a manageReviews
            header("Location: /EpTech/admin/manageReviews");
        }
    }

    /**
     * Visualizza l'elenco delle recensioni per la gestione da parte dell'admin.
     *
     * @return void
     */
    public static function listReviews()
    {
        $admin = FPersistentManager::getInstance()->find(EAdmin::class, $_SESSION['user']->getIdAdmin());
        $page = isset($_GET['reviews_page']) ? (int)$_GET['reviews_page'] : 1;
        $itemsPerPage = 4;

        $reviews = FPersistentManager::getInstance()->getReviewAdmin($admin, $page, $itemsPerPage);

        $view = new VReview();
        $view->showAdminReviews($reviews);
    }

     /**
     * Verifica se l'amministratore è autorizzato a rispondere alla recensione.
     *
     * @param EAdmin $admin L'admin che intende rispondere.
     * @param EReview $review La recensione alla quale vuole rispondere.
     * @return bool True se l'admin è autorizzato, false altrimenti.
     */
    public static function canRespond($admin, $review)
    {
        return $review->getProduct()->getAdmin()->getIdAdmin() == $admin->getIdAdmin();
    }
}