<?php
//creare il test.php rendendo l'output più leggibile e formattando la stampa dei risultati
//"psr-4": {"Cdidi\\EpTechProva\\": "src/"} //Percorso autoload composer.json precedente 


echo "Inizio esecuzione test.php\n";

// Aumenta il limite di memoria
#ini_set('memory_limit', '1024M');

// Includi il file di configurazione per Doctrine
require_once __DIR__ ."/../../config/bootstrap.php";  // Adatta il percorso se necessario

echo "Bootstrap caricato\n";

// Verifica che $entityManager sia inizializzato
if (!isset($entityManager)) {
    die("Errore: $entityManager non è stato configurato.");
}

echo "EntityManager configurato\n";

/*





//                                            TEST PER CATEGORY



// Creazione della classe FCategoria
$fCategory = new FCategory($entityManager);

echo "FCategoria creata\n";

// Test 1: Recupera tutte le categorie
echo "Tutte le categorie:\n";
$categories = $fCategory->getAllCategories();
#var_dump($categories); // Aggiungi questo per vedere i dati grezzi
if ($categories) {
    foreach ($categories as $category) {
        echo "ID: " . $category['categoryId'] . ", Nome: " . $category['nameCategory'] . "\n";
    }
} else {
    echo "Nessuna categoria trovata.\n";
}


// Test 2: Trova una categoria per nome
$categoryName = 'Computer';  // Modifica con una categoria esistente
echo "\nCerca categoria con nome '$categoryName':\n";
$foundCategory = $fCategory->findCategory($categoryName);
#var_dump($foundCategory); // Aggiungi questo per vedere i dati grezzi
if ($foundCategory) {
    echo "ID: " . $foundCategory->getIdCategory() . ", Nome: " . $foundCategory->getNameCategory() . "\n";
    echo "Prodotti:\n";
    foreach ($foundCategory->getProducts() as $product) {
        echo " - " . $product->getNameProduct() . "\n";
    }
} else {
    echo "Categoria non trovata.\n";
}

*/
/*





//                                            TEST PER CREDIT_CARD



// Test 1: Cerca una carta di credito
$fCreditCard = new FCreditCard($entityManager);

echo "FCreditCard creata\n";

$cardNumber = '1234567891011121'; // Modifica con un numero di carta di credito esistente
echo "\nCerca carta di credito con numero '$cardNumber':\n";
$foundCard = $fCreditCard->findCreditCard($cardNumber);
#var_dump($foundCard); // Visualizza i dati grezzi
if ($foundCard) {
    echo "Numero: " . $foundCard->getCardNumber() . "\n";
    echo "Nome del titolare: " . $foundCard->getCardHolderName() . "\n";
    echo "Data di scadenza: " . $foundCard->getEndDate() . "\n";
} else {
    echo "Carta di credito non trovata.\n";
}


//Test 2: Inserire una nuova carta di credito
// Simula una sessione utente
session_start();
$_SESSION['user'] = $entityManager->find('ERegisteredUser', 1); // Assicurati che l'utente con ID 1 esista

$fCreditCard = new FCreditCard($entityManager);

echo "FCreditCard creata\n";

// Dati della nuova carta di credito
$array_data = [
    'number' => '1234567890123456',
    'holderName' => 'Alessia Pulc',
    'endDate' => '12/25',
    'cvv' => '000'
];

// Inserisci la nuova carta di credito
echo "\nInserimento nuova carta di credito:\n";
$fCreditCard->insertCreditCard($array_data);

echo "Carta di credito inserita con successo.\n";


// Test 3: Cerca la carta di credito tramite id dell'utente registrato
$fCreditCard = new FCreditCard($entityManager);

echo "FCreditCard creata\n";

// ID dell'utente di esempio
$idUtente = 2;

// Recupera tutte le carte di credito dell'utente
$creditCards = $fCreditCard->getAllCreditCardUser($idUtente);

// Stampa le carte di credito dell'utente
if ($creditCards) {
    foreach ($creditCards as $creditCard) {
        echo "Numero carta: " . $creditCard->getCardNumber() . "\n";
        echo "Nome titolare: " . $creditCard->getCardHolderName() . "\n";
        
        // Recupera l'anno di scadenza
        $endDate = $creditCard->getEndDate();
        
        // Verifica se endDate è una stringa e contiene solo l'anno
        if (is_string($endDate) && preg_match('/^\d{4}$/', $endDate)) {
            echo "Data di scadenza: " . $endDate . "\n";
        } else {
            echo "Data di scadenza: " . $endDate . "\n";
        }
        
        echo "CVV: " . $creditCard->getCVV() . "\n";
    }
} else {
    echo "Nessuna carta di credito trovata per l'utente con ID $idUtente.";
}


// Test 4: eliminare una carta di credito
$fCreditCard = new FCreditCard($entityManager);

echo "FCreditCard creata\n";

$cardNumberToDelete = '1234567890123456'; // Modifica con un numero di carta di credito esistente
$creditCardToDelete = $entityManager->find('ECreditCard', $cardNumberToDelete);

if ($creditCardToDelete) {
    echo "Eliminazione della carta di credito con numero: " . $creditCardToDelete->getCardNumber() . "<br>";
    $fCreditCard->deleteCreditCard($creditCardToDelete);
    echo "Carta di credito eliminata con successo.<br>";
} else {
    echo "Carta di credito non trovata per l'eliminazione.<br>";
}

*/
/*





//                                              TEST PER ORDER



// Test 1: Cercare l'Ordine di un utente registrato tramite Id
$fOrder = new FOrder($entityManager);

// ID dell'utente da cercare
$idUser = 1;

// Chiamata al metodo findOrderUser
try {
    $orders = $fOrder->findOrderUser($idUser);
    echo "Ordini trovati: \n";
    foreach ($orders as $order) {
        echo "ID Ordine: " . $order->getIdOrder() . "\n";
        echo "Data Ordine: " . $order->getDateTime()->format('Y-m-d') . "\n";
        echo "Stato Ordine: " . $order->getOrderStatus() . "\n";
        echo "Totale Ordine: " . $order->getTotalPrice() . "\n";
        echo "-------------------------\n";
        // Liberare la memoria per ogni ordine
        $entityManager->detach($order);
    }
    // Liberare la memoria per tutti gli oggetti gestiti
    $entityManager->clear();
} catch (Exception $e) {
    echo "Errore: " . $e->getMessage();
}


// Test 2: Creare un nuovo ordine
// Creazione e popolamento del carrello con prodotti per il test
$cart = new ECart();

// Aggiungi il primo articolo al carrello (esempio: prodotto con ID 1)
$item1 = new EItemCart();
$item1->setProduct($entityManager->find(EProduct::class, 1));  // Sostituisci con un ID prodotto valido
$item1->setQuantity(1);
$cart->addItem($item1);

// Aggiungi il secondo articolo al carrello (esempio: prodotto con ID 2)
$item2 = new EItemCart();
$item2->setProduct($entityManager->find(EProduct::class, 2));  // Sostituisci con un ID prodotto valido
$item2->setQuantity(3);
$cart->addItem($item2);

echo "Creati due articoli nel carrello:\n";
echo "- Prodotto 1: " . $item1->getProduct()->getNameProduct() . " (Quantità: " . $item1->getQuantity() . ")\n";
echo "- Prodotto 2: " . $item2->getProduct()->getNameProduct() . " (Quantità: " . $item2->getQuantity() . ")\n";

// Esegui il metodo newOrder per creare l'ordine
echo "### Tentativo di creazione dell'ordine ###\n";
try {
    // Crea un nuovo oggetto FOrder per il repository degli ordini
    $orderRepository = new FOrder($entityManager);
    $order = $orderRepository->newOrder(1, 'Via Luigi 77', '66034', '1234567891011121', $cart);

    // Stampa i dettagli dell'ordine creato
    echo "### Ordine creato con successo ###\n";
    echo "ID Ordine: " . $order->getIdOrder() . "\n";
    echo "Prezzo Totale: €" . number_format($order->getTotalPrice(), 2, ',', '.') . "\n";
    echo "Quantità Totale Prodotti: " . $order->getTotalQuantityProduct() . "\n";
    echo "Stato Ordine: " . $order->getOrderStatus() . "\n";
    echo "Data Ordine: " . $order->getDateTime()->format('Y-m-d H:i:s') . "\n";
    echo "--------------------------\n";
} catch (Exception $e) {
    // Gestione degli errori durante la creazione dell'ordine
    echo "Errore durante la creazione dell'ordine: " . $e->getMessage() . "\n";
}


// Test 3: Cambiare lo stato di un ordine tramite Id
$fOrder = new FOrder($entityManager);

// ID dell'ordine da modificare
$idOrder = 3;
$newStatus = 'preso in carico';

// Chiamata al metodo ChangeOrderStatus
try {
    $fOrder->ChangeOrderStatus($idOrder, $newStatus);
    echo "Stato dell'ordine modificato con successo.\n";

    // Recupero dell'ordine modificato
    $modifiedOrder = $entityManager->find(EOrder::class, $idOrder);
    echo "ID Ordine: " . $modifiedOrder->getIdOrder() . "\n";
    echo "Stato Ordine: " . $modifiedOrder->getOrderStatus() . "\n";
    echo "-------------------------\n";

    // Liberare la memoria per l'ordine
    $entityManager->detach($modifiedOrder);
    // Liberare la memoria per tutti gli oggetti gestiti
    $entityManager->clear();
} catch (Exception $e) {
    echo "Errore: " . $e->getMessage();
}

*/
/*





//                                        TEST PER UTENTE REGISTRATO



// Test 1: Cercare un utente registrato tramite l'email
$fUser = new FRegisteredUser($entityManager); // Assicurati che FUser sia la classe corretta

// Email dell'utente da cercare
$email = 'alessiapul@gmail.com'; // Modifica con un'email esistente nel tuo database

// Chiamata al metodo findRegisteredUser
try {
    $user = $fUser->findRegisteredUser($email);
    if ($user) {
        echo "Utente trovato: \n";
        foreach ($user as $u) {
            echo "ID: " . $u->getIdRegisteredUser() . "\n";
            echo "Nome: " . $u->getName() . "\n";
            echo "Cognome: " . $u->getSurname() . "\n";
            echo "Email: " . $u->getEmail() . "\n";
            echo "Data di nascita: " . $u->getBirthDate()->format('Y-m-d') . "\n";
            echo "Username: " . $u->getUsername() . "\n";
            // Non stampare la password per motivi di sicurezza
        }
    } else {
        echo "Nessun utente trovato con l'email '$email'.\n";
    }
} catch (Exception $e) {
    echo "Errore: " . $e->getMessage();
}


// Test 2: Cercare un utente registrato tramite Id
$fUser = new FRegisteredUser($entityManager); // Assicurati che FUser sia la classe corretta

// ID dell'utente da cercare
$id = 1; // Modifica con un ID esistente nel tuo database

// Chiamata al metodo findRegisteredUserById
try {
    $user = $fUser->findRegisteredUserById($id);
    if ($user) {
        echo "Utente trovato: \n";
        foreach ($user as $u) {
            echo "ID: " . $u->getIdRegisteredUser() . "\n";
            echo "Nome: " . $u->getName() . "\n";
            echo "Cognome: " . $u->getSurname() . "\n";
            echo "Email: " . $u->getEmail() . "\n";
            echo "Data di nascita: " . $u->getBirthDate()->format('Y-m-d') . "\n";
            echo "Username: " . $u->getUsername() . "\n";
            // Non stampare la password per motivi di sicurezza
            echo "-------------------------\n";
        }
    } else {
        echo "Nessun utente trovato con l'ID '$id'.\n";
    }
} catch (Exception $e) {
    echo "Errore: " . $e->getMessage();
}


// Test 3: Inserire un nuovo utente registrato
$newUser = new ERegisteredUser('Celeste', 'Verdi', 'celeste.verdi@example.com', new DateTime('1990-01-01'), 'celesteverdi', 'password123');

// Creazione dell'istanza della classe che contiene il metodo insertNewRegisteredUser
$fUser = new FRegisteredUser($entityManager); // Assicurati che FUser sia la classe corretta

// Chiamata al metodo insertNewRegisteredUser
try {
    $fUser->insertNewRegisteredUser($newUser);
    echo "Nuovo utente registrato con successo!\n";
    echo "Dettagli dell'utente:\n";
    echo "Nome: " . $newUser->getName() . "\n";
    echo "Cognome: " . $newUser->getSurname() . "\n";
    echo "Email: " . $newUser->getEmail() . "\n";
    echo "Data di nascita: " . $newUser->getBirthDate()->format('Y-m-d') . "\n";
    echo "Username: " . $newUser->getUsername() . "\n";
    // Non stampare la password per motivi di sicurezza
} catch (Exception $e) {
    echo "Errore: " . $e->getMessage();
}


// Test 4: Eliminare un utente registrato
$fUser = new FRegisteredUser($entityManager); // Assicurati che FUser sia la classe corretta

// ID dell'utente da eliminare
$id = 4; // Modifica con un ID esistente nel tuo database

// Recupera l'utente da eliminare
$user = $entityManager->find('ERegisteredUser', $id);

if ($user) {
    // Chiamata al metodo deletRegisteredUser
    try {
        $fUser->deletRegisteredUser($user);
        echo "Utente eliminato con successo!\n";
        #echo "Dettagli dell'utente eliminato:\n";
        #echo "ID: " . $user->getIdRegisteredUser() . "\n";
        #echo "Nome: " . $user->getName() . "\n";
        #echo "Cognome: " . $user->getSurname() . "\n";
        #echo "Email: " . $user->getEmail() . "\n";
        #echo "Data di nascita: " . $user->getBirthDate()->format('Y-m-d') . "\n";
        #echo "Username: " . $user->getUsername() . "\n";
        // Non stampare la password per motivi di sicurezza
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }
} else {
    echo "Nessun utente trovato con l'ID '$id'.\n";
}


// Test 5: Aggiornare la password di un utente registrato
$fUser = new FRegisteredUser($entityManager); // Assicurati che FUser sia la classe corretta

// ID dell'utente da aggiornare
$id = 1; // Modifica con un ID esistente nel tuo database

// Recupera l'utente da aggiornare
$user = $entityManager->find('ERegisteredUser', $id);

if ($user) {
    // Nuova password
    $new_password = '6373573'; // Modifica con la nuova password desiderata

    // Chiamata al metodo updatePass
    try {
        $fUser->updatePass($user, $new_password);
        echo "Password aggiornata con successo!\n";
        echo "Dettagli dell'utente aggiornato:\n";
        echo "ID: " . $user->getIdRegisteredUser() . "\n";
        echo "Nome: " . $user->getName() . "\n";
        echo "Cognome: " . $user->getSurname() . "\n";
        echo "Email: " . $user->getEmail() . "\n";
        echo "Data di nascita: " . $user->getBirthDate()->format('Y-m-d') . "\n";
        echo "Username: " . $user->getUsername() . "\n";
        // Non stampare la password per motivi di sicurezza
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }
} else {
    echo "Nessun utente trovato con l'ID '$id'.\n";
}


// Test 6: modificare i dati di un'utente registrato
$fUser = new FRegisteredUser($entityManager); // Assicurati che FUser sia la classe corretta

// ID dell'utente da aggiornare
$id = 2; // Modifica con un ID esistente nel tuo database


// Recupera l'utente da aggiornare
$user = $entityManager->find('ERegisteredUser', $id);

if ($user) {
    // Dati aggiornati dell'utente
    $array_data = [
        'name' => 'Mario',
        'surname' => 'Rossi',
        'username' => 'mariorossi'
    ];

    // Chiamata al metodo updateRegisteredUser
    try {
        $fUser->updateRegisteredUser($user, $array_data);
        echo "Utente aggiornato con successo!\n";
        echo "Dettagli dell'utente aggiornato:\n";
        echo "ID: " . $user->getIdRegisteredUser() . "\n";
        echo "Nome: " . $user->getName() . "\n";
        echo "Cognome: " . $user->getSurname() . "\n";
        echo "Email: " . $user->getEmail() . "\n";
        echo "Data di nascita: " . $user->getBirthDate()->format('Y-m-d') . "\n";
        echo "Username: " . $user->getUsername() . "\n";
        // Non stampare la password per motivi di sicurezza
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }
} else {
    echo "Nessun utente trovato con l'ID '$id'.\n";
}

*/
/*





//                                      TEST PER SHIPPING



// Test 1: trova un indirizzo di spedizione
$fShipping = new FShipping($entityManager); // Assicurati che FShipping sia la classe corretta

// Indirizzo e CAP da cercare
$address = 'Via Luigi 77'; // Modifica con un indirizzo esistente nel tuo database
$cap = '66034'; // Modifica con un CAP esistente nel tuo database

// Chiamata al metodo findShipping
try {
    $shipping = $fShipping->findShipping($address, $cap);
    if ($shipping) {
        echo "Spedizione trovata: \n";
        foreach ($shipping as $sh) {
            echo "ID Spedizione: " . $sh->getIdShipping() . "\n";
            echo "Indirizzo: " . $sh->getAddress() . "\n";
            echo "CAP: " . $sh->getCap() . "\n";
            echo "Città: " . $sh->getCity() . "\n";
            echo "-------------------------\n";
        }
    } else {
        echo "Nessuna spedizione trovata per l'indirizzo '$address' e CAP '$cap'.\n";
    }
} catch (Exception $e) {
    echo "Errore: " . $e->getMessage();
}

// Test 2: crea un nuovo indirizzo di spedizione
$_SESSION['user'] = $entityManager->find('ERegisteredUser', 1); // Assicurati che l'utente con ID 1 esista

// Creazione dell'istanza della classe che contiene il metodo insertShipping
$fShipping = new FShipping($entityManager); // Assicurati che FShipping sia la classe corretta

// Dati della nuova spedizione
$array_data = [
    'address' => 'Via Roma, 1',
    'cap' => '00100',
    'city' => 'Ortona',
    'recipientName' => 'Celeste',
    'recipientSurname' => 'Di Diego'
];

// Chiamata al metodo insertShipping
try {
    $fShipping->insertShipping($array_data);
    echo "Nuova spedizione inserita con successo!\n";
    echo "Dettagli della spedizione:\n";
    echo "Indirizzo: " . $array_data['address'] . "\n";
    echo "CAP: " . $array_data['cap'] . "\n";
} catch (Exception $e) {
    echo "Errore: " . $e->getMessage();
}


// Test 3: eliminare un indirizzo di spedizione
$address = new EShipping('Via Roma, 1', '00100', 'Ortona', 'Celeste', 'Di Diego');

// Esegui il metodo deleteShipping
try {
    $shippingRepository = new FShipping($entityManager);
    $found_shipping = $shippingRepository->findShipping($address->getAddress(), $address->getCap());
    if ($found_shipping) {
        echo "Spedizione trovata: " . $found_shipping[0]->getIdShipping() . "\n";
        $shippingRepository->deleteShipping($found_shipping[0]);
        echo "Spedizione eliminata con successo\n";
    } else {
        echo "Spedizione non trovata\n";
    }
} catch (Exception $e) {
    echo "Errore durante l'eliminazione della spedizione: " . $e->getMessage() . "\n";
}

*/
/*





//                                          TEST PER ADMIN



// Test 1: Cercare un amministratore tramite email
$fAdmin = new FAdmin($entityManager);

// Email dell'amministratore da cercare
$email = 'admin@gmail.com';

// Chiamata al metodo findAdmin
try {
    $admin = $fAdmin->findAdmin($email);
    if (!empty($admin)) {
        echo "Amministratore trovato:\n";
        echo "ID Amministratore: " . $admin[0]->getIdAdmin() . "\n";
        echo "Email Amministratore: " . $admin[0]->getEmail() . "\n";
        echo "Nome Amministratore: " . $admin[0]->getName() . "\n";
        echo "-------------------------\n";
    } else {
        echo "Nessun amministratore trovato con l'email: " . $email . "\n";
    }
    // Liberare la memoria per l'amministratore
    $entityManager->detach($admin[0]);
    // Liberare la memoria per tutti gli oggetti gestiti
    $entityManager->clear();
} catch (Exception $e) {
    echo "Errore: " . $e->getMessage();
}


// Test 2: Cercare un amministratore tramite id
$fAdmin = new FAdmin($entityManager);

// Email dell'amministratore da cercare
$adminId = 1;

// Chiamata al metodo findAdmin
try {
    $admin = $fAdmin->findAdminById($adminId);
    if (!empty($admin)) {
        echo "Amministratore trovato:\n";
        echo "ID Amministratore: " . $admin[0]->getIdAdmin() . "\n";
        echo "Email Amministratore: " . $admin[0]->getEmail() . "\n";
        echo "Nome Amministratore: " . $admin[0]->getName() . "\n";
        echo "-------------------------\n";
    } else {
        echo "Nessun amministratore trovato con l'email: " . $adminId . "\n";
    }
    // Liberare la memoria per l'amministratore
    $entityManager->detach($admin[0]);
    // Liberare la memoria per tutti gli oggetti gestiti
    $entityManager->clear();
} catch (Exception $e) {
    echo "Errore: " . $e->getMessage();
}


//Test 3: aggiornamento della password dell'admin
$fAdmin = new FAdmin($entityManager); // Assicurati che FUser sia la classe corretta

// ID dell'admin da aggiornare
$idAdmin = 1; // Modifica con un ID esistente nel tuo database

// Recupera l'utente da aggiornare
$admin = $entityManager->find('EAdmin', $idAdmin);

if ($admin) {
    // Nuova password
    $new_password = '657488'; // Modifica con la nuova password desiderata

    // Chiamata al metodo updatePass
    try {
        $fAdmin->updatePass($admin, $new_password);
        echo "Password aggiornata con successo!\n";
        echo "Dettagli dell'admin aggiornato:\n";
        echo "ID: " . $admin->getIdAdmin() . "\n";
        echo "Nome: " . $admin->getName() . "\n";
        echo "Cognome: " . $admin->getSurname() . "\n";
        echo "Email: " . $admin->getEmail() . "\n";
        echo "Data di nascita: " . $admin->getBirthDate()->format('Y-m-d') . "\n";
        echo "Username: " . $admin->getUsername() . "\n";
        // Non stampare la password per motivi di sicurezza
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }
} else {
    echo "Nessun utente trovato con l'ID '$idAdmin'.\n";
}


// Test 4: Modificare i dati di un amministratore
$fAdmin = new FAdmin($entityManager);

// ID dell'amministratore da aggiornare
$idAdmin = 1; // Modifica con un ID esistente nel tuo database

// Recupera l'amministratore da aggiornare
$admin = $entityManager->find('EAdmin', $idAdmin);

if ($admin) {
    // Dati aggiornati dell'amministratore
    $array_data = [
        'nome' => 'Chiara',
        'cognome' => 'Scura'
    ];

    // Chiamata al metodo updateAdmin
    try {
        $fAdmin->updateAdmin($admin, $array_data);
        echo "Amministratore aggiornato con successo!\n";
        echo "Dettagli dell'amministratore aggiornato:\n";
        echo "ID: " . $admin->getIdAdmin() . "\n";
        echo "Nome: " . $admin->getName() . "\n";
        echo "Cognome: " . $admin->getSurname() . "\n";
        echo "-------------------------\n";

        // Liberare la memoria per l'amministratore
        $entityManager->detach($admin);
        // Liberare la memoria per tutti gli oggetti gestiti
        $entityManager->clear();
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }
} else {
    echo "Nessun amministratore trovato con l'ID '$id'.\n";
}


// Test 5: Eliminare un amministratore
$fAdmin = new FAdmin($entityManager);

// ID dell'amministratore da eliminare
$idAdmin = 1; // Modifica con un ID esistente nel tuo database

// Recupera l'amministratore da eliminare
$admin = $entityManager->find('EAdmin', $idAdmin);

if ($admin) {
    // Chiamata al metodo deleteAdmin
    try {
        $fAdmin->deleteAdmin($admin);
        echo "Amministratore eliminato con successo!\n";

        // Verifica che l'amministratore sia stato eliminato
        $deletedAdmin = $entityManager->find('EAdmin', $idAdmin);
        if ($deletedAdmin === null) {
            echo "L'amministratore con ID '$idAdmin' è stato correttamente eliminato.\n";
        } else {
            echo "Errore: L'amministratore con ID '$idAdmin' non è stato eliminato.\n";
        }

        // Liberare la memoria per tutti gli oggetti gestiti
        $entityManager->clear();
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }
} else {
    echo "Nessun amministratore trovato con l'ID '$idAdmin'.\n";
}


// Test 6: Recuperare tutti gli utenti registrati paginati
$fAdmin = new FAdmin($entityManager);

// Parametri di paginazione
$page = 1;
$itemsPerPage = 10;

// Chiamata al metodo getAllUsersPaginated
try {
    $result = $fAdmin->getAllUsersPaginated($page, $itemsPerPage);
    echo "Utenti trovati:\n";
    foreach ($result['users'] as $user) {
        echo "ID: " . $user['id'] . "\n";
        echo "Nome: " . $user['name'] . "\n";
        echo "Cognome: " . $user['surname'] . "\n";
        echo "Email: " . $user['email'] . "\n";
        echo "Tipo: " . $user['tipo'] . "\n";
        echo "-------------------------\n";
    }
    echo "Total Items: " . $result['totalItems'] . "\n";
    echo "Items Per Page: " . $result['itemsPerPage'] . "\n";
    echo "Current Page: " . $result['currentPage'] . "\n";
    echo "Total Pages: " . $result['totalPages'] . "\n";
    echo "Has More Pages: " . ($result['hasMorePages'] ? 'Yes' : 'No') . "\n";

    // Liberare la memoria per tutti gli oggetti gestiti
    $entityManager->clear();
} catch (Exception $e) {
    echo "Errore: " . $e->getMessage();
}


// Test 7: Contare il numero totale di utenti registrati
$fAdmin = new FAdmin($entityManager);

try {
    // Chiamata al metodo getTotalUsersCountc
    $totalUsers = $fAdmin->getTotalUsersCountc();
    echo "Numero totale di utenti registrati: " . $totalUsers . "\n";

    // Verifica che il numero totale di utenti sia un intero positivo
    if (is_int($totalUsers) && $totalUsers >= 0) {
        echo "Il conteggio degli utenti è corretto.\n";
    } else {
        echo "Errore: Il conteggio degli utenti non è corretto.\n";
    }

    // Liberare la memoria per tutti gli oggetti gestiti
    $entityManager->clear();
} catch (Exception $e) {
    echo "Errore: " . $e->getMessage();
}

*/
/*





//                                           TEST PER REVIEW



// Test 1: Trovare la recensione per ID
// Istanzia FReview
$fReview = new FReview($entityManager);
echo "FReview istanziato\n";
// ID di esempio per il test
$idReview = 1;
try {
    $review = $fReview->findReviewByID($idReview);
    if ($review) {
        echo "Recensione trovata:\n";
        foreach ($review as $r) {
            echo "ID Recensione: " . $r->getReviewId() . "\n";
            echo "Text: " . $r->getText() . "\n";
            echo "Vote: " . $r->getVote() . "\n";
            echo "-------------------------\n";
        }
    } else {
        echo "Nessuna recensione trovata con ID: $idReview\n";
    }
} catch (Exception $e) {
    echo "Si è verificato un errore: " . $e->getMessage() . "\n";
}
echo "Fine esecuzione test.php\n";


// Test 2: Prende le recensioni di un prodotto
// Istanzia FReview
$fReview = new FReview($entityManager);
echo "FReview istanziato\n";
// Dati di esempio per il test
$product = 1; // ID del prodotto di esempio
$page = 1;
$itemsPerPage = 5;
try {
    // Ottieni le recensioni del prodotto
    $result = $fReview->getReviewProduct($product, $page, $itemsPerPage);
    echo "Recensioni trovate:\n";
    echo "Numero totale di recensioni: " . $result['n_review'] . "\n";
    echo "Pagina corrente: " . $result['currentPage'] . "\n";
    echo "Recensioni per pagina: " . $result['itemsPerPage'] . "\n";
    echo "Numero totale di pagine: " . $result['totalPages'] . "\n";
    echo "-------------------------\n";
    foreach ($result['items'] as $review) {
        echo "ID Recensione: " . $review->getReviewId() . "\n";
        echo "Text: " . $review->getText() . "\n";
        echo "Vote: " . $review->getVote() . "\n";
        echo "-------------------------\n";
    }
} catch (Exception $e) {
    echo "Si è verificato un errore: " . $e->getMessage() . "\n";
}
echo "Fine esecuzione test.php\n";


// Test 3: Prende la recensione di un utente per un prodotto
// Istanzia FReview
$fReview = new FReview($entityManager);
echo "FReview istanziato\n";
// Dati di esempio per il test
$registeredUser = 1; // ID dell'utente registrato di esempio
$product = 1; // ID del prodotto di esempio
try {
    // Ottieni la recensione dell'utente per il prodotto
    $review = $fReview->getReviewUser($registeredUser, $product);
    if ($review) {
        echo "Recensione trovata:\n";
        echo "ID Recensione: " . $review->getReviewId() . "\n";
        echo "Text: " . $review->getText() . "\n";
        echo "Vote: " . $review->getVote() . "\n";
        echo "-------------------------\n";
    } else {
        echo "Nessuna recensione trovata per l'utente con ID: $registeredUser e prodotto con ID: $product\n";
    }
} catch (Exception $e) {
    echo "Si è verificato un errore: " . $e->getMessage() . "\n";
}
echo "Fine esecuzione test.php\n";


// Test 4: Creare una nuova recensione
$fReview = new FReview($entityManager);
$text = 'Buon prodotto';
$vote = 4;
$review = new EReview($text, $vote);
// Imposta le altre proprietà necessarie della recensione
$review->setProdotto($entityManager->find('EProduct', 1)); // Assumi che il prodotto con ID 1 esista
$review->setRegisteredUser($entityManager->find('ERegisteredUser', 1)); // Assumi che l'utente con ID 1 esista

try {
    $fReview->insertReview($review);
    echo "Review created successfully.\n";
    // Liberare la memoria per la recensione
    $entityManager->detach($review);
    $entityManager->clear();
} catch (Exception $e) {
    echo "Errore: " . $e->getMessage();
}


// Test 5: Eliminazione di una recensione
// Istanzia FReview
$fReview = new FReview($entityManager);
echo "FReview istanziato\n";
// ID della recensione da eliminare
$idReview = 15; // Sostituisci con l'ID della recensione che vuoi eliminare
try {
    // Trova la recensione da eliminare
    $review = $fReview->findReviewByID($idReview);
    if ($review) {
        $fReview->deleteReview($review[0]);
        echo "Recensione eliminata con successo.\n";
    } else {
        echo "Nessuna recensione trovata con ID: $idReview\n";
    }
} catch (Exception $e) {
    echo "Si è verificato un errore: " . $e->getMessage() . "\n";
}

echo "Fine esecuzione test.php\n";

*/
/* 





//                                      TEST PER PRODUCT



// Creazione della classe FProduct
$fProduct = new FProduct($entityManager);
echo "FProduct creata\n";

// Funzione di test 1: Inserisci un nuovo prodotto
function testInserisciProdotto($fProduct, $entityManager) {
    echo "Test 2: Inserisci un nuovo prodotto\n";
    $eCategory = new ECategory("4");
    $prodotto = new EProduct("",0,"","","","",0); //Costruttore vuoto, uso i setter per inizializzare i campi
    $prodotto->setNameProduct("Thinkpad2");
    $prodotto->setPriceProduct(446);
    $prodotto->setDescription("Laptop fino2");
    $prodotto->setBrand("Lenovo2");
    $prodotto->setModel("X1C2");
    $prodotto->setColor("Nero2");
    $prodotto->setCategory($eCategory);
    $prodotto->setAvQuantity(5);

    // Persisti manualmente l'entità ECategory
    $entityManager->persist($eCategory);

    $fProduct->insertProduct($prodotto);
    echo "Prodotto inserito con successo.\n";
}

// Funzione di test 2: Elimina un prodotto
function testEliminaProdotto($fProduct, $productId) {
    echo "Test 3: Elimina un prodotto\n";
    $prodotto = $fProduct->getProductById($productId);
    if ($prodotto) {
        $fProduct->deleteProduct($prodotto);
        echo "Prodotto eliminato con successo.\n";
    } else {
        echo "Prodotto non trovato.\n";
    }
}

// Funzione di test 4: Recupera tutti i prodotti
function testRecuperaTuttiProdotti($fProduct) {
    echo "Test 1: Recupera tutti i prodotti\n";
    $prodotti = $fProduct->getAllProducts();
    if ($prodotti) {
        foreach ($prodotti as $prodotto) {
            echo "ID: " . $prodotto->getProductId() . ", Nome: " . $prodotto->getNameProduct() . "\n";
        }
    } else {
        echo "Nessun prodotto trovato.\n";
    }
}

// Funzione di test 5: Recupera un prodotto per ID
function testRecuperaProdottoPerId($fProduct, $productId) {
    echo "Test 4: Recupera un prodotto per ID\n";
    $prodotto = $fProduct->getProductById($productId);
    if ($prodotto) {
        echo "ID: " . $prodotto->getProductId() . ", Nome: " . $prodotto->getNameProduct() . "\n";
    } else {
        echo "Prodotto non trovato.\n";
    }
}

// Esegui i test
//testInserisciProdotto($fProduct, $entityManager); //Inserisci un nuovo prodotto
//testEliminaProdotto($fProduct, 4); // Sostituisci 9 con l'ID effettivo del prodotto da testare
//testRecuperaTuttiProdotti($fProduct); //Recupera tutti i prodotti
//testRecuperaProdottoPerId($fProduct, 1); // Sostituisci 6 con l'ID effettivo del prodotto da testare

*/
/*





//                                            TEST PER ITEM-ORDER


            
// Test 1: Aggiunta di un nuovo ordine di articolo
// Istanzia FItemOrder
$fItemOrder = new FItemOrder($entityManager);
echo "FItemOrder istanziato\n";

// Crea un nuovo ordine di articolo di esempio
$quantity = 10;
$order = $entityManager->find('EOrder', 1); // Assumi che l'ordine con ID 1 esista
$product = $entityManager->find('EProduct', 2); // Assumi che il prodotto con ID 1 esista

if ($order && $product) {
    $itemOrder = new EItemOrder($quantity, $order, $product);

    try {
        $success = $fItemOrder->addItemOrder($itemOrder);
        if ($success) {
            echo "Ordine di articolo aggiunto con successo.\n";
        } else {
            echo "Errore durante l'aggiunta dell'ordine di articolo.\n";
        }
    } catch (Exception $e) {
        echo "Si è verificato un errore: " . $e->getMessage() . "\n";
    }
} else {
    echo "Errore: Ordine o prodotto non trovato.\n";
}

echo "Fine esecuzione test.php\n";


// Test 2: Trova un ordine di articolo
// Istanzia FItemOrder
$fItemOrder = new FItemOrder($entityManager);
echo "FItemOrder istanziato\n";

// ID dell'ordine e ID del prodotto da cercare
$orderId = 1; // Sostituisci con l'ID dell'ordine che vuoi cercare
$productId = 1; // Sostituisci con l'ID del prodotto che vuoi cercare

try {
    // Trova l'ordine di articolo
    $itemOrder = $fItemOrder->findItemOrder($orderId, $productId);
    if ($itemOrder) {
        echo "Ordine di articolo trovato:\n";
        echo "ID Ordine: " . $itemOrder->getOrderId()->getIdOrder() . "\n";
        echo "ID Prodotto: " . $itemOrder->getProductId()->getProductId() . "\n";
        echo "Quantità: " . $itemOrder->getQuantity() . "\n";
        echo "-------------------------\n";
    } else {
        echo "Nessun ordine di articolo trovato con ID Ordine: $orderId e ID Prodotto: $productId\n";
    }
} catch (Exception $e) {
    echo "Si è verificato un errore: " . $e->getMessage() . "\n";
}

echo "Fine esecuzione test.php\n";

*/
/*





//                                                 TEST PER CART



// Test 1: Inserimento di un nuovo carrello
// Istanzia FCart
$fCart = new FCart($entityManager);
echo "FCart istanziato\n";

// Crea un nuovo carrello di esempio
$cart = new ECart();
$registeredUser = $entityManager->find('ERegisteredUser', 1); // Assumi che l'utente registrato con ID 1 esista

if ($registeredUser) {
    $cart->setRegisteredUser($registeredUser);

    try {
        // Inserisci il carrello
        $fCart->insertCart($cart);
        echo "Carrello inserito con successo.\n";
        echo "ID Carrello: " . $cart->getCartId() . "\n";
        echo "ID Utente Registrato: " . $cart->getRegisteredUser()->getIdRegisteredUser() . "\n";
        echo "-------------------------\n";
        // Liberare la memoria per il carrello
        $entityManager->detach($cart);
        $entityManager->clear();
    } catch (Exception $e) {
        echo "Si è verificato un errore: " . $e->getMessage() . "\n";
    }
} else {
    echo "Errore: Utente registrato non trovato.\n";
}

echo "Fine esecuzione test.php\n";


// Test 2: Aggiornamento di un carrello esistente
// Istanzia FCart
$fCart = new FCart($entityManager);
echo "FCart istanziato\n";

// Trova un carrello esistente
$cartId = 1; // Sostituisci con l'ID del carrello che vuoi aggiornare
$cart = $entityManager->find('ECart', $cartId);

if ($cart) {
    try {
        // Aggiorna il carrello
        // Ad esempio, aggiungi un nuovo elemento al carrello
        $item = new EItemCart();
        $item->setProduct($entityManager->find('EProduct', 1)); // Assumi che il prodotto con ID 1 esista
        $item->setQuantity(2);
        $cart->addItem($item);

        $fCart->updateCart($cart);
        echo "Carrello aggiornato con successo.\n";
        echo "ID Carrello: " . $cart->getCartId() . "\n";
        echo "ID Utente Registrato: " . $cart->getRegisteredUser()->getIdRegisteredUser() . "\n";
        echo "Numero di articoli nel carrello: " . count($cart->getItems()) . "\n";
        echo "-------------------------\n";

        // Liberare la memoria per il carrello
        $entityManager->detach($cart);
        $entityManager->clear();
    } catch (Exception $e) {
        echo "Si è verificato un errore: " . $e->getMessage() . "\n";
    }
} else {
    echo "Errore: Carrello non trovato.\n";
}

echo "Fine esecuzione test.php\n";


// Test 3: Eliminazione di un carrello esistente
// Istanzia FCart
$fCart = new FCart($entityManager);
echo "FCart istanziato\n";

// Trova un carrello esistente
$cartId = 2; // Sostituisci con l'ID del carrello che vuoi eliminare
$cart = $entityManager->find('ECart', $cartId);

if ($cart) {
    try {
        // Elimina il carrello
        $fCart->deleteCart($cart);
        echo "Carrello eliminato con successo.\n";
        echo "-------------------------\n";

        // Liberare la memoria per il carrello
        $entityManager->detach($cart);
        $entityManager->clear();
    } catch (Exception $e) {
        echo "Si è verificato un errore: " . $e->getMessage() . "\n";
    }
} else {
    echo "Errore: Carrello non trovato.\n";
}

echo "Fine esecuzione test.php\n";


// Test 4: Trova un carrello per ID
// Istanzia FCart
$fCart = new FCart($entityManager);
echo "FCart istanziato\n";

// ID del carrello da cercare
$cartId = 1; // Sostituisci con l'ID del carrello che vuoi cercare

try {
    // Trova il carrello per ID
    $cart = $fCart->findById($cartId);
    if ($cart) {
        echo "Carrello trovato:\n";
        echo "ID Carrello: " . $cart->getCartId() . "\n";
        echo "ID Utente Registrato: " . $cart->getRegisteredUser()->getIdRegisteredUser() . "\n";
        echo "Numero di articoli nel carrello: " . count($cart->getItems()) . "\n";
        echo "-------------------------\n";
    } else {
        echo "Nessun carrello trovato con ID: $cartId\n";
    }
} catch (Exception $e) {
    echo "Si è verificato un errore: " . $e->getMessage() . "\n";
}


// Test 5: Trova un carrello per ID utente
// ID dell'utente da cercare
$userId = 1; // Sostituisci con l'ID dell'utente che vuoi cercare

try {
    // Trova il carrello per ID utente
    $cart = $fCart->findByUser($userId);
    if ($cart) {
        echo "Carrello trovato per utente:\n";
        echo "ID Carrello: " . $cart->getCartId() . "\n";
        echo "ID Utente Registrato: " . $cart->getRegisteredUser()->getIdRegisteredUser() . "\n";
        echo "Numero di articoli nel carrello: " . count($cart->getItems()) . "\n";
        echo "-------------------------\n";
    } else {
        echo "Nessun carrello trovato per l'utente con ID: $userId\n";
    }
} catch (Exception $e) {
    echo "Si è verificato un errore: " . $e->getMessage() . "\n";
}

echo "Fine esecuzione test.php\n";

*/
/*





//                                          TEST PER ITEM-CART



// Test 1: Aggiunta di un nuovo elemento al carrello
// Istanzia FItemCart
$fItemCart = new FItemCart($entityManager);
echo "FItemCart istanziato\n";

// Trova un carrello esistente
$cartId = 1; // Sostituisci con l'ID del carrello a cui vuoi aggiungere l'elemento
$cart = $entityManager->find('ECart', $cartId);

if ($cart) {
    try {
        // Crea un nuovo elemento di esempio
        $itemCart = new EItemCart();
        $itemCart->setProduct($entityManager->find('EProduct', 1)); // Assumi che il prodotto con ID 1 esista
        $itemCart->setQuantity(2);
        $itemCart->setCart($cart); // Associa l'elemento al carrello

        // Aggiungi l'elemento al carrello
        $success = $fItemCart->addItemCart($itemCart);
        if ($success) {
            echo "Elemento aggiunto al carrello con successo.\n";
            echo "ID Carrello: " . $cart->getCartId() . "\n";
            echo "Numero di articoli nel carrello: " . count($cart->getItems()) . "\n";
            echo "-------------------------\n";
        } else {
            echo "Errore durante l'aggiunta dell'elemento al carrello.\n";
        }

        // Liberare la memoria per il carrello
        $entityManager->detach($cart);
        $entityManager->clear();
    } catch (Exception $e) {
        echo "Si è verificato un errore: " . $e->getMessage() . "\n";
    }
} else {
    echo "Errore: Carrello non trovato.\n";
}

echo "Fine esecuzione test.php\n";


// Test 2: Trova un elemento del carrello per ID
// Istanzia FItemCart
$fItemCart = new FItemCart($entityManager);
echo "FItemCart istanziato\n";

// ID dell'elemento del carrello da cercare
$itemCartId = 1; // Sostituisci con l'ID dell'elemento del carrello che vuoi cercare

try {
    // Trova l'elemento del carrello per ID
    $itemCart = $fItemCart->findById($itemCartId);
    if ($itemCart) {
        echo "Elemento del carrello trovato:\n";
        echo "ID Elemento: " . $itemCart->getId() . "\n";
        echo "ID Carrello: " . $itemCart->getCart()->getCartId() . "\n";
        echo "ID Prodotto: " . $itemCart->getProduct()->getProductId() . "\n";
        echo "Quantità: " . $itemCart->getQuantity() . "\n";
        echo "-------------------------\n";
    } else {
        echo "Nessun elemento del carrello trovato con ID: $itemCartId\n";
    }
} catch (Exception $e) {
    echo "Si è verificato un errore: " . $e->getMessage() . "\n";
}

echo "Fine esecuzione test.php\n";

*/





