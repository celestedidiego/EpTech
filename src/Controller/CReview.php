<?php

class CReview
{
    public static function addReview($productId)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!FPersistentManager::getInstance()->hasPurchasedProduct($productId)) {
                $_SESSION['review_error'] = "Non puoi recensire un prodotto che non hai acquistato.";
                header("Location: /EpTechProva/product/viewProduct/" . $productId);
                return;
            }

            $user = FPersistentManager::getInstance()->find(ERegisteredUser::class, $_SESSION['user']->getIdRegisteredUser());
            $product = FPersistentManager::getInstance()->find(EProduct::class, $productId);

            $existingReview = FPersistentManager::getInstance()->getReviewUser($user, $product);
            if ($existingReview) {
                $_SESSION['review_error'] = "Hai già scritto una recensione per questo prodotto. Puoi modificarla ma non aggiungerne una nuova.";
                header("Location: /EpTechProva/product/viewProduct/" . $productId);
            } else {
                try {
                    $text = $_POST['text'];
                    $vote = $_POST['vote'];

                    $review = new EReview($text,  $vote);
                    $review->setRegisteredUser($user);
                    $review->setProduct($product);

                    FPersistentManager::getInstance()->addReview($review);

                    $_SESSION['review_success'] = "La tua recensione è stata aggiunta con successo!";
                } catch (Exception $e) {
                    $_SESSION['review_error'] = "Si è verificato un errore durante l'aggiunta della recensione: " . $e->getMessage();
                }

                header("Location: /EpTechProva/product/viewProduct/" . $productId);
            }
        }
    }

    public static function editReview($productId)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = FPersistentManager::getInstance()->find(ERegisteredUser::class, $_SESSION['user']->getIdRegisteredUser());
            $product = FPersistentManager::getInstance()->find(EProduct::class, $productId);

            if (!FPersistentManager::getInstance()->hasPurchasedProduct($productId)) {
                $_SESSION['review_error'] = "Non puoi modificare una recensione per un prodotto che non hai acquistato.";
                header("Location: /EpTechProva/product/view/" . $productId);
                return;
            }

            $reviewId = $_POST['idReview'];
            $review = FPersistentManager::getInstance()->find(EReview::class, $reviewId);

            if ($review->getRegisteredUser()->getIdRegisteredUser() != $user->getIdRegisteredUser()) {
                $_SESSION['review_error'] = "Non puoi modificare una recensione che non ti appartiene.";
                header("Location: /EpTechProva/product/view/" . $productId);
            } else {
                try {
                    $text = $_POST['text'];
                    $vote = $_POST['vote'];

                    $review->setText($text);
                    $review->setVote($vote);

                    FPersistentManager::getInstance()->addReview($review);

                    $_SESSION['review_success'] = "La tua recensione è stata modificata con successo!";
                } catch (Exception $e) {
                    $_SESSION['review_error'] = "Si è verificato un errore durante la modifica della recensione: " . $e->getMessage();
                }

                header("Location: /EpTechProva/product/view/" . $productId);
            }
        }
    }

    /*
    public static function respondToReview($reviewId)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $admin = FPersistentManager::getInstance()->find(EAdmin::class, $_SESSION['user']->getIdAdmin());
            $review = FPersistentManager::getInstance()->find(EReview::class, $reviewId);

            if (!self::canRespond($admin, review: $review)) {
                $_SESSION['error'] = "Non sei autorizzato a rispondere a questa recensione.";
                header("Location: /EpTechProva/review/listReviews");
            }

            $response = $_POST['response'];

            $review->setResponseAdmin($response, new \DateTime());

            FPersistentManager::getInstance()->flush();
            $_SESSION['success'] = "Risposta inviata con successo.";
            header("Location: /EpTechProva/admin/manageReviews");
        }
    }
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
                header("Location: /EpTechProva/admin/manageReviews");
                exit;
            }

            // Recupera la risposta dal form
            $response = $_POST['risposta']; // Cambiato il nome del campo per corrispondere al template

            // Imposta la risposta dell'admin
            $review->setResponseAdmin($response, $admin);

            // Salva la modifica nel database
            FPersistentManager::getInstance()->flush();

            // Imposta un messaggio di successo
            $_SESSION['success'] = "Risposta inviata con successo.";

            // Reindirizza a manageReviews
            header("Location: /EpTechProva/admin/manageReviews");
        }
    }

    public static function listReviews()
    {
        $admin = FPersistentManager::getInstance()->find(EAdmin::class, $_SESSION['user']->getIdAdmin());
        $page = isset($_GET['reviews_page']) ? (int)$_GET['reviews_page'] : 1;
        $itemsPerPage = 4;

        $reviews = FPersistentManager::getInstance()->getReviewAdmin($admin, $page, $itemsPerPage);

        $view = new VReview();
        $view->showAdminReviews($reviews);
    }

    public static function canRespond($admin, $review)
    {
        return $review->getProduct()->getAdmin()->getIdAdmin() == $admin->getIdAdmin();
    }
}
