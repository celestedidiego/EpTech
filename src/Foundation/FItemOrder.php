<?php
use Doctrine\ORM\EntityRepository;

class FItemOrder extends EntityRepository {

    /*
    public function __construct($entityManager) {
        // Passa l'EntityManager e la classe dell'entità al costruttore della classe padre
        parent::__construct($entityManager, $entityManager->getClassMetadata('EItemOrder'));
    }
    */

    /*
    public function findByOrder($idOrder) {
        return $this->findBy(['order' => $idOrder]);
    }

    public function addItemOrder(EItemOrder $itemOrder) {
        $em = $this->_em;
        try {
            $em->persist($itemOrder);
            $em->flush();
            return true;
        } catch (Exception $e) {
            // Gestisci l'eccezione, ad esempio loggando l'errore
            return false;
        }
    }

    public function findItemOrder($orderId, $productId) {
        return $this->findOneBy([
            'orderId' => $orderId,
            'productId' => $productId
        ]);
    }

    
    public function updateItemOrder(EItemOrder $itemOrder) {
        $em = $this->_em;
        try {
            $em->persist($itemOrder);
            $em->flush();
            return true;
        } catch (Exception $e) {
            // Gestisci l'eccezione, ad esempio loggando l'errore
            return false;
        }
    }

    public function deleteItemOrder(EItemOrder $itemOrder) {
        $em = $this->_em;
        try {
            $em->remove($itemOrder);
            $em->flush();
            return true;
        } catch (Exception $e) {
            // Gestisci l'eccezione, ad esempio loggando l'errore
            return false;
        }
    }
    */

    //Aggiungere altri metodi se necessario

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

    /*
    public function getAllOrders($admin, $currentPage = 1, $pageSize = 4)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('DISTINCT o')
        ->from('EOrder', 'o')
        ->join('o.itemOrder', 'io') // Relazione tra ordine e prodotti ordinati
        ->join('io.productId', 'p') // Relazione con i prodotti
        ->where('p.admin = :admin') // Filtra per admin
        ->setParameter('admin', $admin) // Imposta il parametro dell'admin
        ->orderBy('o.dateTime', 'DESC'); // Ordina gli ordini per data in ordine decrescente

        $query = $qb->getQuery();

        // Calcola il numero totale di risultati
        $totalItems = count($query->getResult());

        // Applica la paginazione
        $query->setFirstResult(($currentPage - 1) * $pageSize)
            ->setMaxResults($pageSize);

        // Esegui la query
        $results = $query->getResult();

        return [
            'orders' => $results, // Lista degli ordini
            'n_orders' => $totalItems, // Numero totale di ordini
            'currentPage' => $currentPage, // Pagina corrente
            'pageSize' => $pageSize, // Numero di ordini per pagina
            'totalPages' => ceil($totalItems / $pageSize) // Numero totale di pagine
        ];
    }
    */

    public function findItemOrder($orderId, $productId) {
        return $this->findOneBy([
            'order_id' => $orderId,
            'product_id' => $productId
        ]);
    }
    
}