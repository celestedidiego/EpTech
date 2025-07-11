<?php

use Doctrine\ORM\EntityRepository;

/**
 * Class FOrder
 * Repository per la gestione degli ordini.
 */
class FOrder extends EntityRepository {

    /**
     * Trova tutti gli ordini di un utente.
     * @param int $idUser
     * @return array
     */
    public function findOrderUser($idUser)
    {
        return $this->findBy(['registeredUser' => $idUser], ['idOrder' => 'DESC']);
    }

    /**
     * Crea un nuovo ordine.
     * @param string $address
     * @param string $cap
     * @param string $cardNumber
     * @param array $cart
     * @return EOrder
     * @throws Exception
     */
    public function newOrder($address, $cap, $cardNumber, $cart){
        $em = $this->getEntityManager();
        $em->beginTransaction(); // Inizia transazione database

        try {
            $user = $em->find(ERegisteredUser::class, $_SESSION['user']->getIdRegisteredUser());
            // Verifica se l'inidirizzo di spedizione esiste
            $addressObj = FPersistentManager::getInstance()->findShipping($address, $cap);
            if (!$addressObj) {
                throw new \Exception("Indirizzo non trovato");
            }
            // Verifica se la carta di credito esiste
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
                // Ottieni il prodotto con lock pessimista
                // LOCK: Solo 1 utente alla volta può accedere
                $product = $em->find(
                    EProduct::class,
                    $productId,
                    \Doctrine\DBAL\LockMode::PESSIMISTIC_WRITE
                );

                // Controlla la quantità disponibile
                if ($product->getAvQuantity() < $quantity) {
                    throw new \Exception("Quantità non disponibile per il prodotto '{$product->getNameProduct()}' (ID $productId). Disponibile: {$product->getAvQuantity()}, richiesto: $quantity");
                }

                // Crea un singolo "item" dell'ordine
                $itemOrder = new EItemOrder();
                $itemOrder->setOrder($order); // Collega all'ordine
                $itemOrder->setProduct($product); // Collega al prodotto
                $itemOrder->setQuantity($quantity); // Imposta quantità ordinata
                $em->persist($itemOrder);

                $order->addQProductOrder($itemOrder);

                $total += $product->getPriceProduct() * $quantity;
                $quantityTotal += $quantity;

                // Aggiorna la quantità disponibile del prodotto
                // Sottrae la quantità ordinata dalle scorte disponibili
                $product->setAvQuantity($product->getAvQuantity() - $quantity);
                $em->persist($product);
            }

            $order->setTotalPrice($total);
            $order->setTotalQuantityProduct($quantityTotal);

            $em->persist($order);
            $em->flush();
            // Lock viene rilasciato al commit()
            $em->commit(); // Conferma definitivamente la transazione
            return $order;
        } catch (\Throwable $e) { // intercetta tutte le eccezioni, non solo Exception
            $em->rollback();
            throw $e;
        }
    }

    /**
     * Cambia lo stato di un ordine.
     * @param int $idOrder
     * @param string $newStatus
     * @return void
     */
    public function ChangeOrderStatus($idOrder, $newStatus){
        $em = $this->getEntityManager();
        $found_order = $em->find(EOrder::class, $idOrder);
        $found_order->setOrderStatus($newStatus);

        $em->persist($found_order);
        $em->flush();
    }

    /**
     * Aggiunge una richiesta di rimborso per un ordine.
     * @param EOrder $order
     * @return void
     */
    public function addRefundRequest(EOrder $order): void {
        $em = $this->getEntityManager();
        $refundRequest = new ERefundRequest($order);
        $em->persist($refundRequest);
        $em->flush();
    }
}