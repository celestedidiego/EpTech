<?php
use Doctrine\ORM\EntityRepository;

class FCategory extends EntityRepository {

    public function getAllCategories(){
        $dql = "SELECT cat FROM ECategory cat";
        $query = getEntityManager()->createQuery($dql);
        return $query->getArrayResult();
    }
}
?>
