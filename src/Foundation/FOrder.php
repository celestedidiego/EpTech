<?php

use Doctrine\ORM\EntityRepository;


class FOrder extends EntityRepository {

    public function findOrderUser($idUser)
    {
        return $this->findBy(['registeredUser' => $idUser], ['idOrder' => 'DESC']);
    }

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

    public function ChangeOrderStatus($idOrder, $newStatus){
        $em = getEntityManager();
        $found_order = $em->find(EOrder::class, $idOrder);
        $found_order->setOrderStatus($newStatus);

        $em->persist($found_order);
        $em->flush();
    }

    public function addRefundRequest(EOrder $order): void {
        $em = $this->getEntityManager();
        $refundRequest = new ERefundRequest($order);
        $em->persist($refundRequest);
        $em->flush();
    }
}
