<?php
use Doctrine\ORM\EntityRepository;

class FCategory extends EntityRepository {

    /*
    public function __construct($entityManager) {
        // Passa l'EntityManager e la classe dell'entità al costruttore della classe padre
        parent::__construct($entityManager, $entityManager->getClassMetadata('ECategory'));
    }
    */
    
    /*
    public function getAllCategories(){
        // Modifica per usare $this->getEntityManager()
        $dql = "SELECT cat FROM ECategory cat";
        $query = getEntityManager()->createQuery($dql);
        return $query->getArrayResult();
    }
    */

    // Trova una categoria specifica nel database utilizzando il nome della categoria
    
    /*
    public function findCategory($category){
        // Modifica per usare $this->getEntityManager()
        $dql = "SELECT c FROM ECategory c WHERE c.nameCategory = ?1";
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter(1, $category);
        $query->setMaxResults(1);
        return $query->getResult();
    }
    */

    /*
    public function findCategory($category) {
        $dql = "SELECT cat FROM ECategory cat WHERE cat.nameCategory = ?1";
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter(1, $category);
        $query->setMaxResults(1);
        return $query->getOneOrNullResult(); // Restituisce un singolo oggetto o null
    }
    */

    public function getAllCategories(){
        $dql = "SELECT cat FROM ECategory cat";
        $query = getEntityManager()->createQuery($dql);
        return $query->getArrayResult();
    }
    
    public function findCategoria($category){
        $dql = "SELECT cat FROM ECategory cat WHERE cat.nameCtegory = ?1";
        $query = getEntityManager()->createQuery($dql);
        $query->setParameter(1, $category);
        $query->setMaxResults(1);
        return $query->getResult();
    }
}
?>
