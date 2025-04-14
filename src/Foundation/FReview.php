<?php
use Doctrine\ORM\EntityRepository;

class FReview extends EntityRepository {

    /*
    public function __construct($entityManager) {
        // Passa l'EntityManager e la classe dell'entità al costruttore della classe padre
        parent::__construct($entityManager, $entityManager->getClassMetadata('EReview'));
    }
    */

    public function findReviewByID($idReview)
    {
        $dql = "SELECT r FROM EReview r WHERE r.idReview = :idReview";
        $query = getEntityManager()->createQuery($dql);
        $query->setParameter(1, $idReview);
        $query->setMaxResults(1);
        return $query->getResult();
    }

    public function getReviewAdmin($admin, $page = 1, $itemsPerPage = 4) {
        $qb = $this->createQueryBuilder('r')
            ->join('r.product', 'p')
            ->where('p.admin = :admin')
            ->setParameter('admin', $admin);
    
        $totalItems = count($qb->getQuery()->getResult());
    
        $qb->setFirstResult(($page - 1) * $itemsPerPage)
           ->setMaxResults($itemsPerPage);
    
        $items = $qb->getQuery()->getResult();
    
        return [
            'items' => $items,
            'n_reviews' => $totalItems,
            'currentPage' => $page,
            'itemsPerPage' => $itemsPerPage,
            'totalPages' => ceil($totalItems / $itemsPerPage)
        ];
    }

    public function getReviewsProduct($product, $page = 1, $itemsPerPage = 5) {
        $qb = $this->createQueryBuilder('r')
            ->where('r.product = :product')
            ->setParameter('product', $product);

        $totalItems = count($qb->getQuery()->getResult());

        $qb->setFirstResult(($page - 1) * $itemsPerPage)
           ->setMaxResults($itemsPerPage);

        $items = $qb->getQuery()->getResult();

        return [
            'items' => $items,
            'n_reviews' => $totalItems,
            'currentPage' => $page,
            'itemsPerPage' => $itemsPerPage,
            'totalPages' => ceil($totalItems / $itemsPerPage)
        ];
    }

    public function getReviewUser($registeredUser, $product) {
        $em = getEntityManager();
        return $em->getRepository('EReview')->findOneBy([
            'registeredUser' => $registeredUser,
            'product' => $product
        ]);
    }

    public function insertReview($review) {
        $em = getEntityManager();
        $em->persist($review);
        $em->flush();
    }
    
    public function deleteReview($review) {
        $em = getEntityManager();
        $em->remove($review);
        $em->flush();
    }

    public function hasPurchasedProduct($productId) {
        $em = getEntityManager();
        $found_user = $em->find(ERegisteredUser::class, $_SESSION['user']->getIdRegisteredUser());
        $qb = $em->createQueryBuilder();
        $result = $qb->select('COUNT(DISTINCT o.idOrder)')
        ->from('EOrder', 'o')
        ->join('o.itemOrder', 'io')
        ->where('io.product = :product')
        ->andWhere('o.registeredUser = :user')
        ->setParameter('product', $productId)
        ->setParameter('user', $found_user)
        ->getQuery()
        ->getSingleScalarResult();

        return $result > 0;  // Restituisce true se il cliente ha acquistato il prodotto, false altrimenti
    }
}