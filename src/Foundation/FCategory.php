<?php
use Doctrine\ORM\EntityRepository;

class FCategory extends EntityRepository {

    public function getAllCategories(){
        $dql = "SELECT cat FROM ECategory cat";
        $query = getEntityManager()->createQuery($dql);
        return $query->getArrayResult();
    }
    
    /*public function findCategoria($category){
        $dql = "SELECT cat FROM ECategory cat WHERE cat.nameCtegory = ?1";
        $query = getEntityManager()->createQuery($dql);
        $query->setParameter(1, $category);
        $query->setMaxResults(1);
        return $query->getResult();
    }*/
}
?>
