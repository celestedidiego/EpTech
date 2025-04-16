<?php
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class FProduct extends EntityRepository {

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