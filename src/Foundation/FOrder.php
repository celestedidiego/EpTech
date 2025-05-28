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
                // Ottieni il prodotto con lock pessimista
                $product = $em->find(
                    EProduct::class,
                    $productId,
                    \Doctrine\DBAL\LockMode::PESSIMISTIC_WRITE
                );

                // Controlla la quantità disponibile
                if ($product->getAvQuantity() < $quantity) {
                    throw new \Exception("Quantità non disponibile per il prodotto '{$product->getNameProduct()}' (ID $productId). Disponibile: {$product->getAvQuantity()}, richiesto: $quantity");
                }

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
        $em = getEntityManager();
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