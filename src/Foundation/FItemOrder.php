<?php
use Doctrine\ORM\EntityRepository;

/**
 * Class FItemOrder
 * Repository per la gestione degli item degli ordini.
 */
class FItemOrder extends EntityRepository {

    /**
     * Aggiunge un item a un ordine.
     * @param EOrder $order
     * @param EProduct $product
     * @param int $quantity
     * @return EItemOrder
     */
    public function addItemOrder(EOrder $order, EProduct $product, $quantity) {
        $em = $this->getEntityManager();
        $itemOrder = new EItemOrder(); 
        $itemOrder->setOrder($order);
        $itemOrder->setProduct($product);
        $itemOrder->setQuantity($quantity);
        
        $em->persist($itemOrder);
        $em->flush();
        
        return $itemOrder;
    }
    
    /**
     * Trova un item ordine tramite ID ordine e ID prodotto.
     * @param int $orderId
     * @param int $productId
     * @return EItemOrder|null
     */
    public function findItemOrder($orderId, $productId) {
        return $this->findOneBy([
            'order_id' => $orderId,
            'product_id' => $productId
        ]);
    }
    
}