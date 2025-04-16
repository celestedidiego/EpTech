<?php
use Doctrine\ORM\EntityRepository;

class FItemOrder extends EntityRepository {

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
    
    public function findItemOrder($orderId, $productId) {
        return $this->findOneBy([
            'order_id' => $orderId,
            'product_id' => $productId
        ]);
    }
    
}