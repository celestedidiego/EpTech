<?php
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class FProduct extends EntityRepository {

    /*
    public function __construct($entityManager) {
        // Passa l'EntityManager e la classe dell'entità al costruttore della classe padre
        parent::__construct($entityManager, $entityManager->getClassMetadata('EProduct'));
    }
    */

    /*
    //Inserisci un prodotto
    public function insertProduct(EProduct $product){
        $this->getEntityManager()->persist($product);
        $this->getEntityManager()->flush();
    }

    //Elimina un prodotto
    public function deleteProduct($product){
        $this->getEntityManager()->remove($product);
        $this->getEntityManager()->flush();
    }
    */

    public function insertProduct(EProduct $product){
        $em = getEntityManager();
        $em->persist($product);
        $em->flush();
    }

    public function deleteProduct($product) {
        $em = getEntityManager();
        $found_product = $em->find(EProduct::class, $product);
        if(!$found_product->isDeleted()){
            $found_product->setDeleted(true);
        }
        $em->flush();
    }

    public function updateImageProduct(EProduct $product, EImage $image){
        $em = getEntityManager();
        $found_product = $em->find(EProduct::class, $product->getProductId());
        $found_image =  $em->find(EImage::class, $image->getIdImage());
        $found_product->addImage($found_image);
        $em->persist($found_product);
        $em->flush();
    }

    /*
    public function updateAdminCatProduct(EProduct $product, EAdmin $admin, ECategory $category){
        $em = getEntityManager();
        $found_product = $em->find(EProduct::class, $product->getProductId());
        $found_admin = $em->find(EAdmin::class, $admin->getIdAdmin());
        $found_category = $em->find(ECategory::class, $category->getIdCategory());
        $found_product->setAdmin($found_admin);
        $found_product->setCategory($found_category);
        $em->persist($found_product);
        $em->flush();
    }
    */

    public function updateAdminCatProduct(EProduct $product, EAdmin $admin, ECategory $category)
    {
        $em = getEntityManager();
        $found_product = $em->find(EProduct::class, $product->getProductId());
        $found_admin = $em->find(EAdmin::class, $admin->getIdAdmin());
        $found_category = $em->find(ECategory::class, $category->getIdCategory());

        if ($found_product && $found_admin && $found_category) {
            $found_product->setAdmin($found_admin);
            $found_product->setNameCategory($found_category);
            $em->persist($found_product);
            $em->flush();
        } else {
            throw new \Exception('Errore: uno degli oggetti non è valido.');
        }
    }

    /*
    //Recupera tutti i prodotti
    public function getAllProducts(){
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('p')
           ->from('EProduct', 'p');
        $query = $qb->getQuery();
        return $query->getResult();
    }

    */

    public function getAllProducts($currentPage = 1, $pageSize = 4){
        $dql = "SELECT p
            FROM EProduct p
            WHERE p.is_deleted = false";
        $query = getEntityManager()->createQuery($dql);
        $query->setFirstResult(($currentPage - 1) * $pageSize)
        ->setMaxResults($pageSize);

        $paginator = new Paginator($query, fetchJoinCollection: true);

        return [
        'products' => iterator_to_array($paginator),
        'n_products' => count($paginator),
        'currentPage' => $currentPage,
        'pageSize' => $pageSize,
        'totalPages' => ceil(count($paginator) / $pageSize)
        ];
    }

    /*
    //Recupera un prodotto per ID
    public function getProductById($id){
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('p')
           ->from('EProduct', 'p')
           ->where('p.productId = :id')
           ->setParameter('id', $id);
        $query = $qb->getQuery();
        return $query->getOneOrNullResult();
    }
    */

    public function getProductById($id, $currentPage = 1, $pageSize = 4){
        $dql= "SELECT p 
        FROM EProduct p 
        WHERE p.productId= ?1
        AND p.is_deleted = false";
        $query = getEntityManager()->createQuery($dql);
        $query->setParameter(1, $id);
        $query->setMaxResults(1);
        $query->setFirstResult(($currentPage - 1) * $pageSize)
        ->setMaxResults($pageSize);

        $paginator = new Paginator($query, fetchJoinCollection: true);

        return [
        'products' => iterator_to_array($paginator),
        'n_prodotti' => count($paginator),
        'currentPage' => $currentPage,
        'pageSize' => $pageSize,
        'totalPages' => ceil(count($paginator) / $pageSize)
        ];
    
    }

    /*
    //Recupera gli ultimi prodotti
    public function getLatestNewProducts($limit = 4) {
        $dql = "SELECT p FROM EProduct p ORDER BY p.createdAt DESC";
        $query = $this->getEntityManager()->createQuery($dql)
            ->setMaxResults($limit);
        return $query->getResult();
    }

    */

    //Rivedere se è necessario questo metodo
    public function getLatestNewProducts($limit = 4) {
        $dql = "SELECT p.productId, p.nameProduct, p.priceProduct, c.nameCategory
                FROM EProduct p
                JOIN p.category c
                ORDER BY p.productId DESC";
        $query = $this->getEntityManager()->createQuery($dql)
            ->setMaxResults($limit);
        return $query->getResult();
    }

    public function getAllProductsByAdmin(EAdmin $admin, $page = 1, $filter = [], $pageSize = 4) {
        $qb = getEntityManager()->createQueryBuilder();
        $qb->select('p')
           ->from('EProduct', 'p')
           ->where('p.admin = :admin')
           ->setParameter('admin', $admin);
    
        if (!empty($filter['query'])) {
            $qb->andWhere('p.nameProduct LIKE :query OR p.description LIKE :query')
               ->setParameter('query', '%' . $filter['query'] . '%');
        }
        if (!empty($filter['category'])) {
            $qb->andWhere('p.nameCategory = :category')
               ->setParameter('category', $filter['category']);
        }
        if (!empty($filter['brand'])) {
            $qb->andWhere('p.brand = :brand')
               ->setParameter('brand', $filter['brand']);
        }
    
        $query = $qb->getQuery()
                    ->setFirstResult(($page - 1) * $pageSize)
                    ->setMaxResults($pageSize);
    
        $paginator = new Paginator($query, $fetchJoinCollection = true);
    
        $risultati = iterator_to_array($paginator);
    
        // Filtra i risultati per prezzo in PHP
        if (!empty($filter['prezzo_max'])) {
            $risultati = array_filter($risultati, function($prodotto) use ($filter) {
                return $prodotto->getPriceProduct() <= $filter['prezzo_max'];
            });
        }
    
        return [
            'products' => $risultati,
            'totalItems' => count($paginator),
            'currentPage' => $page,
            'itemsPerPage' => $pageSize,
            'totalPages' => ceil(count($paginator) / $pageSize)
        ];
    }

    public function updateProduct(EProduct $product, $array_data){
        $em = getEntityManager();
        $found_product = $em->find(EProduct::class, $product->getProductId());
        $found_product->setNameProduct($array_data['name']);
        $found_product->setDescription($array_data['description']);
        $found_product->setBrand($array_data['brand']);
        $found_product->setModel($array_data['model']);
        $found_product->setColor($array_data['color']);
        $found_product->setPriceProduct($array_data['priceProduct']);
        $found_product->setAvQuantity($array_data['avQuantity']);
        $em->persist($found_product);
        $em->flush();
    }

    /*
    public function getAllProductsByCategory($category, $page = 1, $itemsPerPage = 4) {
        $qb = $this->createQueryBuilder('p')
            ->where('p.nameCategory = :category')
            ->setParameter('category', $category)
            ->setFirstResult(($page - 1) * $itemsPerPage)
            ->setMaxResults($itemsPerPage);
    
        $totalItems = count($qb->getQuery()->getResult());
    
        $items = $qb->getQuery()->getResult();
    
        return [
            'items' => $items,
            'n_products' => $totalItems,
            'currentPage' => $page,
            'itemsPerPage' => $itemsPerPage,
            'totalPages' => ceil($totalItems / $itemsPerPage)
        ];
    }
    */

    public function getAllProductsByCategory($category, $currentPage = 1, $pageSize = 4){
        $dql = "SELECT p
                FROM EProduct p
                JOIN p.category c
                WHERE c.categoryId = ?1
                AND p.is_deleted = false";
        $query = getEntityManager()->createQuery($dql)
        ->setParameter(1, $category)
        ->setFirstResult(($currentPage - 1) * $pageSize)
        ->setMaxResults($pageSize);

        $paginator = new Paginator($query, fetchJoinCollection: true);

        return [
        'products' => iterator_to_array($paginator),
        'n_products' => count($paginator),
        'currentPage' => $currentPage,
        'pageSize' => $pageSize,
        'totalPages' => ceil(count($paginator) / $pageSize)
        ];
    }
}