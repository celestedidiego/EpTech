<?php
use Doctrine\ORM\EntityRepository;

/**
 * Class FCategory
 * Repository per la gestione delle categorie.
 */
class FCategory extends EntityRepository {

    /**
     * Restituisce tutte le categorie.
     * @return array
     */
    public function getAllCategories(){
        $dql = "SELECT cat FROM ECategory cat";
        $query = getEntityManager()->createQuery($dql);
        return $query->getArrayResult();
    }
}
?>