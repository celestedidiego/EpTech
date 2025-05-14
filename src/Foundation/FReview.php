<?php
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Class FReview
 * Repository per la gestione delle recensioni dei prodotti.
 */
class FReview extends EntityRepository {

    /**
     * Trova una recensione tramite ID.
     * @param int $idReview
     * @return array
     */
    public function findReviewByID($idReview)
    {
        $dql = "SELECT r FROM EReview r WHERE r.idReview = :idReview";
        $query = getEntityManager()->createQuery($dql);
        $query->setParameter(1, $idReview);
        $query->setMaxResults(1);
        return $query->getResult();
    }

    /**
     * Restituisce le recensioni dei prodotti gestiti da un admin, paginati.
     * @param mixed $admin
     * @param int $page
     * @param int $itemsPerPage
     * @return array
     */
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

    /**
     * Restituisce le recensioni di un prodotto, paginati.
     * @param mixed $product
     * @param int $page
     * @param int $itemsPerPage
     * @return array
     */
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

    /**
     * Restituisce la recensione di un utente registrato per un prodotto.
     * @param mixed $registeredUser
     * @param mixed $product
     * @return mixed
     */
    public function getReviewUser($registeredUser, $product) {
        $em = getEntityManager();
        return $em->getRepository('EReview')->findOneBy([
            'registeredUser' => $registeredUser,
            'product' => $product
        ]);
    }

    /**
     * Inserisce una nuova recensione.
     * @param mixed $review
     * @return void
     */
    public function addReview($review) {
        $em = getEntityManager();
        $em->persist($review);
        $em->flush();
    }
    
    /**
     * Elimina una recensione.
     * @param mixed $review
     * @return void
     */
    public function deleteReview($review) {
        $em = getEntityManager();
        $em->remove($review);
        $em->flush();
    }

    /**
     * Verifica se l'utente ha acquistato un prodotto.
     * @param mixed $productId
     * @return bool
     */
    public function hasPurchasedProduct($productId) {
        $em = getEntityManager();
        $found_user = $em->find(ERegisteredUser::class, $_SESSION['user']->getIdRegisteredUser());
        $qb = $em->createQueryBuilder();
        $result = $qb->select('COUNT(DISTINCT o.idOrder)')
        ->from('EOrder', 'o')
        ->join('o.itemOrder', 'io')
        ->where('io.product = :product')
        ->andWhere('o.registeredUser = :user')
        ->andWhere('o.orderStatus = :status')
        ->setParameter('product', $productId)
        ->setParameter('user', $found_user)
        ->setParameter('status', 'Consegnato') // Assicurati di usare lo stato corretto per gli ordini completati
        ->getQuery()
        ->getSingleScalarResult();

        return $result > 0;  // Restituisce true se il cliente ha acquistato il prodotto, false altrimenti
    }

    /**
     * Restituisce le recensioni tramite nome prodotto, paginati.
     * @param string $productName
     * @param int $page
     * @param int $itemsPerPage
     * @return array
     */
    public function getReviewsByProductName($productName, $page = 1, $itemsPerPage = 10) {
        $offset = ($page - 1) * $itemsPerPage;
    
        $dql = "SELECT r FROM EReview r
                JOIN r.product p
                WHERE p.nameProduct LIKE :productName
                ORDER BY r.idReview DESC";
    
        $query = getEntityManager()->createQuery($dql)
            ->setParameter('productName', '%' . $productName . '%')
            ->setFirstResult($offset)
            ->setMaxResults($itemsPerPage);
    
        $paginator = new Paginator($query, fetchJoinCollection: true);
    
        return [
            'items' => iterator_to_array($paginator),
            'n_reviews' => count($paginator),
            'currentPage' => $page,
            'itemsPerPage' => $itemsPerPage,
            'totalPages' => ceil(count($paginator) / $itemsPerPage)
        ];
    }
}