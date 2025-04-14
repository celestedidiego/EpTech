<?php

use Doctrine\ORM\EntityRepository;


class FOrder extends EntityRepository {

    /*
    public function __construct($entityManager) {
        // Passa l'EntityManager e la classe dell'entità al costruttore della classe padre
        parent::__construct($entityManager, $entityManager->getClassMetadata('EOrder'));
    }
    */

    public function findOrderUser($idUser)
    {
        return $this->findBy(['registeredUser' => $idUser], ['idOrder' => 'DESC']);
    }

    /*
    public function newOrder($userId, $address, $cap, $numberCard, ECart $cart) {
        $em = getEntityManager();
        $em->beginTransaction();
    
        try {
            // Trova l'utente registrato utilizzando l'ID utente passato come parametro
            $user = $em->find(ERegisteredUser::class, $userId);
            $addressObj = FPersistentManager::getInstance()->findShipping($address, $cap);
            if (!$addressObj) {
                throw new \Exception("Indirizzo non trovato");
            }
            $creditCardObj = FPersistentManager::getInstance()->findCreditCard($numberCard);
            if (!$creditCardObj) {
                throw new \Exception("Carta di credito non trovata");
            }
    
            //$orderStatus = 'Pending';  // Imposta lo stato dell'ordine
            //$totalPrice = 0;  // Imposta il prezzo totale dell'ordine inizialmente a 0
            //$qTotalProduct = 0;  // Imposta la quantità totale dei prodotti inizialmente a 0
    
            $order = new EOrder();
            $order->setRegisteredUser($user);
            $order->setShipping($addressObj);
            $order->setCreditCard($creditCardObj);


    
            $total = 0;
            $quantityTotal = 0;
            $insufficientProducts = [];
    
            foreach ($cart->getItems() as $item) {
                $product = $item->getProduct();
                $quantity = $item->getQuantity();
                $productId = $product->getProductId();  // Ottieni l'ID del prodotto
    
                $product = $em->find(EProduct::class, $productId);  // Recupera il prodotto dal database
    
                // Verifica che la quantità disponibile sia sufficiente
                if ($product->getAvQuantity() < $quantity) {
                    $insufficientProducts[] = $product->getNameProduct();
                    continue;  // Salta questo prodotto e continua con il prossimo
                }
    
                $itemOrder = new EItemOrder();
                $itemOrder->setOrderId($order);
                $itemOrder->setProductId($product);
                $itemOrder->setQuantity($quantity);

                $em->persist($itemOrder);
    
                $order->addQProductOrder($itemOrder);
    
                $total += $product->getPriceProduct() * $quantity;
                $quantityTotal += $quantity;
    
                $product->setAvQuantity($product->getAvQuantity() - $quantity);
                $em->persist($product);
            }
    
            if (!empty($insufficientProducts)) {
                throw new \Exception("Quantità insufficiente per i seguenti prodotti: " . implode(", ", $insufficientProducts));
            }
    
            $order->setTotalPrice($total);
            $order->setTotalQuantityProduct($quantityTotal);
    
            $em->persist($order);
            $em->flush();
            $em->commit();
            return $order;
        } catch (Exception $e) {
            $em->rollback();
            throw $e;
        }
    }
    */

    public function newOrder($address, $cap, $cardNumber, $cart){
        $em = getEntityManager();
        $em->beginTransaction();

        try {
            $user = $em->find(ERegisteredUser::class, $_SESSION['user']->getIdRegisteredUser());
            $addressObj = FPersistentManager::getInstance()->findShipping($address, $cap);
            if (!$addressObj) {
                throw new \Exception("Indirizzo non trovato");
            }
            $cardObj = FPersistentManager::getInstance()->findCreditCard($cardNumber);
            if (!$cardObj) {
                throw new \Exception("Carta di credito non trovata");
            }

            $order = new EOrder();
            $order->setRegisteredUser($user);
            $order->setShipping($addressObj[0]);
            $order->setCreditCard($cardObj);

            $total = 0;
            $quantityTotal = 0;

            foreach ($cart as $productId => $quantity) {
                $product = $em->find(EProduct::class, $productId);
                $itemOrder = new EItemOrder();
                $itemOrder->setOrder($order);
                $itemOrder->setProduct($product);
                $itemOrder->setQuantity($quantity);
                $em->persist($itemOrder);

                $order->addQProductOrder($itemOrder);

                $total += $product->getPriceProduct() * $quantity;
                $quantityTotal += $quantity;

                $product->setAvQuantity($product->getAvQuantity() - $quantity);
                $em->persist($product);
            }

            $order->setTotalPrice($total);
            $order->setTotalQuantityProduct($quantityTotal);

            $em->persist($order);
            $em->flush();
            $em->commit();
            return $order;
        } catch (Exception $e) {
            $em->rollback();
            throw $e;
        }
    }

    /*
    public function changeOrderStatus($idOrder, $newOrderStatus) {
        $em = $this->getEntityManager();
        $em->beginTransaction();

        try {
            // Trova l'ordine utilizzando l'ID passato come parametro
            $found_order = $em->find(EOrder::class, $idOrder);
            if (!$found_order) {
                throw new \Exception("Ordine non trovato");
            }

            // Cambia lo stato dell'ordine
            $found_order->setOrderStatus($newOrderStatus);

            // Persisti le modifiche e applica il flush
            $em->persist($found_order);
            $em->flush();
            $em->commit();
        } catch (Exception $e) {
            $em->rollback();
            throw $e;
        }
    }
    */

    //Rivedere meglio questa funzione

    public function ChangeOrderStatus($idOrder, $newStatus){
        $em = getEntityManager();
        $found_order = $em->find(EOrder::class, $idOrder);
        $found_order->setOrderStatus($newStatus);

        $em->persist($found_order);
        $em->flush();
    }


}
