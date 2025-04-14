<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/bootstrap.php';

use PHPUnit\Framework\TestCase;

class CReviewTest extends TestCase
{
    protected $persistentManager;

    public function __construct()
    {
        parent::__construct();
        $this->persistentManager = $this->createMock(FPersistentManager::class);
        FPersistentManager::setInstance($this->persistentManager);
    }

    public function testAddReview()
    {
        // Mock per ERegisteredUser
        $user = $this->createMock(ERegisteredUser::class);
        $user->method('getIdRegisteredUser')->willReturn(1);

        // Mock per EProduct
        $product = $this->createMock(EProduct::class);

        // Configura il mock di FPersistentManager
        $this->persistentManager->method('find')->willReturnMap([
            [ERegisteredUser::class, 1, $user],
            [EProduct::class, 1, $product]
        ]);
        $this->persistentManager->method('getReviewUser')->willReturn(null);
        $this->persistentManager->method('hasPurchasedProduct')->willReturn(true);

        // Simula una richiesta POST
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['text'] = 'Test review';
        $_POST['vote'] = 5;
        $_SESSION['user'] = $user;

        // Esegui il metodo addReview
        CReview::addReview(1);

        // Verifica che la recensione sia stata aggiunta con successo
        if ($_SESSION['review_success'] === 'La tua recensione è stata aggiunta con successo!') {
            echo "testAddReview passed\n";
        } else {
            echo "testAddReview failed\n";
        }
    }

    public function testEditReview()
    {
        // Mock per ERegisteredUser
        $user = $this->createMock(ERegisteredUser::class);
        $user->method('getIdRegisteredUser')->willReturn(1);

        // Mock per EProduct
        $product = $this->createMock(EProduct::class);

        // Mock per EReview
        $review = $this->createMock(EReview::class);
        $review->method('getRegisteredUser')->willReturn($user);

        // Configura il mock di FPersistentManager
        $this->persistentManager->method('find')->willReturnMap([
            [ERegisteredUser::class, 1, $user],
            [EProduct::class, 1, $product],
            [EReview::class, 1, $review]
        ]);
        $this->persistentManager->method('hasPurchasedProduct')->willReturn(true);

        // Simula una richiesta POST
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['text'] = 'Updated review';
        $_POST['vote'] = 4;
        $_POST['idReview'] = 1;
        $_SESSION['user'] = $user;

        // Esegui il metodo editReview
        CReview::editReview(1);

        // Debug
        //var_dump($_SESSION['review_success']);

        // Verifica che la recensione sia stata modificata con successo
        //$this->assertEquals('La tua recensione è stata modificata con successo!', $_SESSION['review_success']);
        if ($_SESSION['review_success'] === 'La tua recensione è stata modificata con successo!') {
            echo "testEditReview passed\n";
        } else {
            echo "testEditReview failed\n";
        }
    }

    public function testRespondToReview()
    {
        // Mock per EAdmin
        $admin = $this->createMock(EAdmin::class);
        $admin->method('getIdAdmin')->willReturn(1);

        // Mock per EProduct
        $product = $this->createMock(EProduct::class);
        $product->method('getAdmin')->willReturn($admin);

        // Mock per EReview
        $review = $this->createMock(EReview::class);
        $review->method('getProduct')->willReturn($product);

        // Configura il mock di FPersistentManager
        $this->persistentManager->method('find')->willReturnMap([
            [EAdmin::class, 1, $admin],
            [EReview::class, 1, $review]
        ]);

        // Simula una richiesta POST
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['response'] = 'Thank you for your review!';
        $_SESSION['user'] = $admin;

        // Esegui il metodo respondToReview
        CReview::respondToReview(1);

        // Verifica che la risposta sia stata inviata con successo
        if ($_SESSION['success'] === 'Risposta inviata con successo.') {
            echo "testRespondToReview passed\n";
        } else {
            echo "testRespondToReview failed\n";
        }
    }

    public function testListReviews()
    {
        // Mock per EAdmin
        $admin = $this->createMock(EAdmin::class);
        $admin->method('getIdAdmin')->willReturn(1);

        // Configura il mock di FPersistentManager
        $this->persistentManager->method('find')->willReturn($admin);
        $this->persistentManager->method('getReviewAdmin')->willReturn([]);

        // Esegui il metodo listReviews
        ob_start();
        CReview::listReviews();
        $output = ob_get_clean();

        // Verifica che la lista delle recensioni sia stata mostrata
        if (strpos($output, 'Recensioni del venditore') !== false) {
            echo "testListReviews passed\n";
        } else {
            echo "testListReviews failed\n";
        }
    }

    public function testCanRespond()
    {
        // Mock per EAdmin
        $admin = $this->createMock(EAdmin::class);
        $admin->method('getIdAdmin')->willReturn(1);

        // Mock per EProduct
        $product = $this->createMock(EProduct::class);
        $product->method('getAdmin')->willReturn($admin);

        // Mock per EReview
        $review = $this->createMock(EReview::class);
        $review->method('getProduct')->willReturn($product);

        // Verifica che l'admin possa rispondere alla recensione
        if (CReview::canRespond($admin, $review)) {
            echo "testCanRespond passed\n";
        } else {
            echo "testCanRespond failed\n";
        }
    }

    public function runTests()
    {
        //$this->testAddReview();
        //$this->testEditReview();
        //$this->testRespondToReview();
        //$this->testListReviews();
        //$this->testCanRespond();
        // Aggiungi qui altri test
    }
}
//define('PHPUNIT_RUNNING', true);
//$test = new CReviewTest();
//$test->runTests();


?>