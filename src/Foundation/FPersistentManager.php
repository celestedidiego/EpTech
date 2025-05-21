<?php
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Class FPersistentManager
 * Singleton per la gestione centralizzata della persistenza delle entità.
 * Fornisce metodi per operazioni CRUD e query su varie entità del dominio.
 */
class FPersistentManager {
    //Singleton Class
    private static $instance;
    private $repositories = [];
    private function __construct(){

    }

    /**
     * Restituisce l'istanza singleton (singola istanza) di FPersistentManager.
     * @return FPersistentManager
     */
    public static function getInstance(){
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Imposta l'istanza singleton (per test o override).
     * @param FPersistentManager $instance
     * @return void
     */
    public static function setInstance($instance)
    {
        self::$instance = $instance;
    }

    /**
     * Restituisce il repository Doctrine per una classe di entità.
     * @param string $entityClass
     * @return EntityRepository
     */
    public function getRepository(string $entityClass): EntityRepository
    {
        if (!isset($this->repositories[$entityClass])) {
            $this->repositories[$entityClass] = getEntityManager()->getRepository($entityClass);
        }

        return $this->repositories[$entityClass];
    }

    /**
     * Rende persistente un'entità.
     * @param object $entity
     * @return void
     */
    public function persist($entity): void
    {
       getEntityManager()->persist($entity);
    }

    /**
     * Rimuove un'entità dal contesto di persistenza.
     * @param object $entity
     * @return void
     */
    public function remove($entity): void
    {
       getEntityManager()->remove($entity);
    }

    /**
     * Applica tutte le modifiche pendenti al database.
     * @return void
     */
    public function flush(): void
    {
       getEntityManager()->flush();
    }

    /**
     * Pulisce il contesto di persistenza di Doctrine.
     * @return void
     */
    public function clear(): void
    {
       getEntityManager()->clear();
    }

    /**
     * Trova un'entità nel database tramite la sua classe e identificatore.
     * @param string $entityClass
     * @param mixed $id
     * @return object|null
     */
    public function find(string $entityClass, $id)
    {
        return $this->getRepository($entityClass)->find($id);
    }

    /**
     * Trova tutte le entità di una specifica classe nel database.
     * @param string $entityClass
     * @return array
     */
    public function findAll(string $entityClass): array
    {
        return $this->getRepository($entityClass)->findAll();
    }

    /**
     * Trova entità nel database tramite criteri specifici.
     * @param string $entityClass
     * @param array $criteria
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return array
     */
    public function findBy(string $entityClass, array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array
    {
        return $this->getRepository($entityClass)->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * Trova una singola entità nel database tramite criteri specifici.
     * @param string $entityClass
     * @param array $criteria
     * @return object|null
     */
    public function findOneBy(string $entityClass, array $criteria)
    {
        return $this->getRepository($entityClass)->findOneBy($criteria);
    }

    /**
     * Aggiorna lo stato di un'entità con i dati attuali dal database.
     * @param object $entity
     * @return void
     */
    public function refresh($entity){
        getEntityManager()->refresh($entity);
    }

     /**
     * Trova un utente (registrato o admin) tramite oggetto o email.
     * Utilizzato per trovare un utente nel database, sia che l'utente sia un oggetto di una classe specifica (ERegisteredUser, EAdmin) o una stringa (email).
     * @param mixed $user
     * @return mixed
     */
    public function findUtente($user){
        if($user instanceof ERegisteredUser){
            if(is_object($user)){
                return getEntityManager()->getRepository('ERegisteredUser')->findRegisteredUser($user->getEmail());
            }else if(is_string($user)){
                return getEntityManager()->getRepository('ERegisteredUser')->findRegisteredUser($user);
            }else{
                return null;
            }
        }elseif($user instanceof EAdmin){
            if(is_object($user)){
                return getEntityManager()->getRepository('EAdmin')->findAdmin($user->getEmail());
            }else if(is_string($user)){
                return getEntityManager()->getRepository('EAdmin')->findAdmin($user);
            }else{
                return null;
            }    
        }else if(is_string($user)){
            if(getEntityManager()->getRepository('ERegisteredUser')->findRegisteredUser($user) != null){
                return getEntityManager()->getRepository('ERegisteredUser')->findRegisteredUser($user);
            }else if(getEntityManager()->getRepository('EAdmin')->findAdmin($user)!= null){
                return getEntityManager()->getRepository('EAdmin')->findAdmin($user);
            }else{
                return null;
            }       
        }
    }

    /**
     * Trova un utente registrato tramite ID.
     * @param int $registeredUserId
     * @return mixed
     */
    public function findRegisteredUserById($registeredUserId){
        return getEntityManager()->getRepository('ERegisteredUser')->findRegisteredUserById($registeredUserId);
    }

    /**
     * Trova un admin tramite ID.
     * @param int $adminId
     * @return mixed
     */
    public function findAdminById($adminId){
        return getEntityManager()->getRepository('EAdmin')->findAdminById($adminId);
    }

    /**
     * Inserisce un nuovo utente registrato nel database.
     * @param ERegisteredUser $new_user
     * @return void
     */
    public function insertNewUser(ERegisteredUser $new_user): void
    {
        getEntityManager()->getRepository('ERegisteredUser')->insertNewRegisteredUser($new_user);
    }

    /**
     * Aggiorna la password di un utente (registrato o admin).
     * @param mixed $user
     * @param string $new_password
     * @return void
     */
    public function updatePass($user, string $new_password): void {
        if ($user instanceof ERegisteredUser) {
            $this->updateRegisteredUserPass($user, $new_password);
        } else if ($user instanceof EAdmin) {
            $this->updateAdminPass($user, $new_password);
        } else {
            throw new InvalidArgumentException('Invalid user type');
        }
    }

    private function updateRegisteredUserPass(ERegisteredUser $user, string $new_password): void {
        $em = getEntityManager();
        $found_user = $em->find(ERegisteredUser::class, $user->getIdRegisteredUser());
        $found_user->setPassword(password_hash($new_password, PASSWORD_DEFAULT));
        $em->persist($found_user);
        $em->flush();
    }

    private function updateAdminPass(EAdmin $admin, string $new_password): void {
        $em = getEntityManager();
        $found_admin = $em->find(EAdmin::class, $admin->getIdAdmin());
        $found_admin->setPassword(password_hash($new_password, PASSWORD_DEFAULT));
        $em->persist($found_admin);
        $em->flush();
    }

    //Utilizzato per aggiornare i dati di un utente registrato (ERegisteredUser) o di un amministratore (EAdmin) nel database
    public function updateUser($user, array $array_data): void
    {
        if($user instanceof ERegisteredUser){
            getEntityManager()->getRepository('ERegisteredUser')->updateRegisteredUser($user, $array_data);
        } else if($user instanceof EAdmin){
            getEntityManager()->getRepository('EAdmin')->updateAdmin($user, $array_data);
        }
    }

    /**
     * Elimina un utente registrato o admin.
     * @param mixed $user
     * @return void
     */
    public function deleteUser($user): void
    {
        if($user instanceof ERegisteredUser){
            getEntityManager()->getRepository('ERegisteredUser')->deleteRegisteredUser($user);
        } else if($user instanceof EAdmin){
            getEntityManager()->getRepository('EAdmin')->deleteAdmin($user);
        }
    }

    /**
     * Restituisce tutti i prodotti paginati.
     * @param int $currentPage
     * @return mixed
     */
    public function getAllProducts($currentPage){
        return getEntityManager()->getRepository('EProduct')->getAllProducts($currentPage);
    }

    /**
     * Restituisce tutti i prodotti della stessa categoria, escluso uno specifico prodotto.
     * @param string $category
     * @param int $productId
     * @param int $page
     * @param int $itemsPerPage
     * @return array
     */
    public function getAllSameCatProducts($category, $productId, $page = 1, $itemsPerPage = 4) {
        $prod = $this->find(EProduct::class, $productId);
        // Recupera tutti i prodotti della stessa categoria
        $all_products = $this->getRepository('EProduct')->getAllProductsByCategory($category, $page, $itemsPerPage);
        // Rimuovi il prodotto corrente dall'elenco
        if (isset($all_products['products']) && is_array($all_products['products'])) {
            foreach ($all_products['products'] as $key => $product) {
                if ($product && $product->getProductId() == $productId) {
                    unset($all_products['products'][$key]);
                }
            }
            $all_products['products'] = array_values($all_products['products']);
        } else {
            $all_products['products'] = [];
        }
        return $all_products;
    }

    /**
     * Restituisce tutte le categorie.
     * @return array
     */
    public function getAllCategories(){
        return getEntityManager()->getRepository('ECategory')->getAllCategories();
    }

    /**
     * Inserisce un nuovo prodotto.
     * @param mixed $product
     * @return void
     */
    public function insertProduct($product){
        getEntityManager()->getRepository('EProduct')->insertProduct($product);
    }

    /**
     * Trova una categoria.
     * @param mixed $category
     * @return mixed
     */
    public function findCategory($category){
        return getEntityManager()->getRepository('ECategory')->findCategory($category);
    }

    /**
     * Aggiorna le informazioni di un pordotto inclusa la categoria.
     * @param mixed $product
     * @param mixed $category
     * @return void
     */
    public function updateCatProdotto($product, $category){
        getEntityManager()->getRepository('EProduct')->updateCatProdotto($product, $category);
    }

    /**
     * Elimina un prodotto.
     * @param mixed $product
     * @return void
     */
    public function deleteProduct($product){
        getEntityManager()->getRepository('EProduct')->deleteProduct($product);
    }

    /**
     * Restituisce tutti gli ordini di un utente paginati.
     * @param mixed $user
     * @param int $page
     * @return mixed
     */
    public function getAllOrders($user, $page) {
        return getEntityManager()->getRepository('EItemOrder')->getAllOrders($user, $page);
    }

    /**
     * Crea un nuovo ordine.
     * @param mixed $idShipping
     * @param string $cap
     * @param mixed $carta_id
     * @param array $cart
     * @return mixed
     */
    public function newOrder($idShipping, $cap, $carta_id, $cart) {
        return getEntityManager()->getRepository('EOrder')->newOrder($idShipping, $cap, $carta_id, $cart);
    }

    /**
     * Aggiunge un prodotto a un ordine.
     * @param EOrder $order
     * @param EProduct $product
     * @param int $quantity
     * @return mixed
     */
    public function addProductOrder(EOrder $order, EProduct $product, $quantity) {
        return getEntityManager()->getRepository('EItemOrder')->addProductOrder($order, $product, $quantity);
    }

    /**
     * Restituisce tutte le spedizioni di un utente.
     * @param ERegisteredUser $registeredUser
     * @return mixed
     */
    public function getAllShippingUser(ERegisteredUser $registeredUser) {
        return getEntityManager()->getRepository('EShipping')->getAllShippingUser($registeredUser->getIdRegisteredUser());
    }

    /**
     * Restituisce tutte le carte di credito di un utente.
     * @param ERegisteredUser $registeredUser
     * @return mixed
     */
    public function getAllCreditCardUser(ERegisteredUser $registeredUser) {
        return getEntityManager()->getRepository('ECreditCard')->getAllCreditCardUser($registeredUser->getIdRegisteredUser());
    }

    /**
     * Inserisce una nuova spedizione.
     * @param array $array_data
     * @return void
     */
    public function insertShipping($array_data){
        getEntityManager()->getRepository('EShipping')->insertShipping($array_data);
    }

    /**
     * Elimina una spedizione.
     * @param mixed $idShipping
     * @return void
     */
    public function deleteShipping($idShipping){
        getEntityManager()->getRepository('EShipping')->deleteShipping($idShipping);
    }

    /**
     * Inserisce una nuova carta di credito.
     * @param array $array_data
     * @return void
     */
    public function insertCreditCard($array_data) {
        getEntityManager()->getRepository('ECreditCard')->insertCreditCard($array_data);
    }

    /**
     * Elimina una carta di credito.
     * @param mixed $cardNumber
     * @return void
     */
    public function deleteCreditCard($cardNumber) {
        getEntityManager()->getRepository('ECreditCard')->deleteCreditCard($cardNumber);
    }

    /**
     * Restituisce tutti gli ordini dell'utente corrente.
     * @return mixed
     */
    public function getOrderUser(){
        return getEntityManager()->getRepository('EOrder')->findOrderUser($_SESSION['user']->getIdRegisteredUser());
    }

    /**
     * Cerca prodotti tramite query di ricerca e opzionalmente di categoria.
     * @param string $query
     * @param string|null $category
     * @return array
     */
    public function searchProduct($query, $category) {
        $dql = "SELECT p FROM EProduct p WHERE p.nameProduct LIKE :query";
        if ($category) {
            $dql .= " AND p.category.nameCategory = :category";
        }
        $query = getEntityManager()->createQuery($dql)
            ->setParameter('query', '%' . $query . '%');
        if ($category) {
            $query->setParameter('category', $category);
        }
        return $query->getResult();
    }

    /**
     * Restituisce prodotti filtrati e paginati.
     * @param array $filters
     * @param int $page
     * @param int $pageSize
     * @return array
     */
    public function getProductFiltered($filters, $page = 1, $pageSize = 4) {
        $qb = getEntityManager()->createQueryBuilder();
        $qb->select('p, AVG(r.vote) AS avg_rating, SUM(io.quantity) AS sold_count')
            ->from('EProduct', 'p')
            ->leftJoin('p.reviews', 'r')
            ->leftJoin('EItemOrder', 'io', 'WITH', 'io.product = p.productId')
            ->where('p.is_deleted = false');

        // ...altri filtri...
        // Filtro per query di ricerca
        if (!empty($filters['query'])) {
            $qb->andWhere('p.nameProduct LIKE :query OR p.description LIKE :query')
            ->setParameter('query', '%' . $filters['query'] . '%');
        }

        // Filtro per categoria
        if (!empty($filters['category'])) {
            $qb->join('p.category', 'c')
            ->andWhere('c.nameCategory = :category')
            ->setParameter('category', $filters['category']);
        }

        // Filtro per marca
        if (!empty($filters['brand'])) {
            $qb->andWhere('p.brand = :brand')
            ->setParameter('brand', $filters['brand']);
        }

        // Filtro per prezzo massimo
        if (!empty($filters['prezzo_max'])) {
            $qb->andWhere('p.priceProduct <= :price_max')
            ->setParameter('price_max', $filters['prezzo_max']);
        }

        // Ordinamento
        if (!empty($filters['order_by'])) {
            switch ($filters['order_by']) {
                case 'sold_desc':
                    $qb->addOrderBy('sold_count', 'DESC');
                    break;
                case 'rating_desc':
                    $qb->addOrderBy('avg_rating', 'DESC');
                    break;
                case 'price_asc':
                    $qb->addOrderBy('p.priceProduct', 'ASC');
                    break;
                case 'price_desc':
                    $qb->addOrderBy('p.priceProduct', 'DESC');
                    break;
            }
        }

        $qb->groupBy('p.productId');
        $qb->setFirstResult(($page - 1) * $pageSize)
            ->setMaxResults($pageSize);

        $query = $qb->getQuery();
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        $results = iterator_to_array($paginator);

        return [
            'products' => $results,
            'n_products' => count($paginator),
            'currentPage' => $page,
            'itemsPerPage' => $pageSize,
            'totalPages' => ceil(count($paginator) / $pageSize),
        ];
    }

    /**
     * Restituisce tutte le marche dei prodotti.
     * @return array
     */
    public function getAllBrands() {
        $dql = "SELECT DISTINCT p.brand FROM EProduct p";
        $query = getEntityManager()->createQuery($dql);
        $results = $query->getResult();
        return array_column($results, 'brand');
    }

    /**
     * Trova un elemento dell' ordine basandosi sull'ID dell'ordine e ID del prodotto.
     * @param int $idOrder L'identificatore univoco dell'ordine.
     * @param int $productId L'identificatore univoco del prodotto.
     * @return mixed L'elemento dell'ordine corrispondente, se trovato; altrimenti null.
     */
    public function findItemOrder($idOrder, $productId) {
        return getEntityManager()->getRepository('ItemOrder')->findItemOrder($idOrder, $productId);
    }

    /**
     * Aggiorna un'entità nel database.
     * @param object $entity
     * @return bool
     */
    public function update($entity) {
        try {
            getEntityManager()->persist($entity);
            getEntityManager()->flush();
            return true;
        } catch (Exception $e) {
            // Log dell'errore
            error_log("Errore durante l'aggiornamento dell'entità: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Restituisce un prodotto tramite ID.
     * @param int $productId
     * @return mixed
     */
    public function getProductById($productId){
        return getEntityManager()->getRepository(EProduct::class)->getProductById($productId);
    }

    /**
     * Restituisce le recensioni di un prodotto tramite nome.
     * @param string $productName
     * @return mixed
     */
    public function getReviewsByProductName($productName){
        return getEntityManager()->getRepository(EReview::class)->getReviewsByProductName($productName);
    }

    /**
     * Restituisce le recensioni di un prodotto paginati.
     * @param mixed $product
     * @param int $page
     * @param int $itemsPerPage
     * @return mixed
     */
    public function getReviewsProduct($product, $page = 1, $itemsPerPage = 5) {
        return getEntityManager()->getRepository('EReview')->getReviewsProduct($product, $page, $itemsPerPage);
    }

    /**
     * Aggiunge una recensione.
     * @param mixed $review
     * @return void
     */
    public function addReview($review) {
        getEntityManager()->getRepository('EReview')->addReview($review);
    }

    /**
     * Restituisce la recensione di un utente per un prodotto.
     * @param mixed $registeredUser
     * @param mixed $product
     * @return mixed
     */
    public function getReviewUser($registeredUser, $product) {
        return getEntityManager()->getRepository('EReview')->getReviewUser($registeredUser, $product);
    }

    public function findUser($user) {
        if (is_string($user)) {
            return $this->findUserByEmail($user);
        }
    
        if ($user instanceof ERegisteredUser || $user instanceof EAdmin) {
            return $this->findUserByObject($user);
        }
    
        return null;
    }
    
     /**
     * Trova un utente tramite email.
     * @param string $email
     * @return mixed $user
     */
    private function findUserByEmail($email) {
        $user = getEntityManager()->getRepository('ERegisteredUser')->findRegisteredUser($email);
        if (!$user) {
            $user = getEntityManager()->getRepository('EAdmin')->findAdmin($email);
        }
        return $user;
    }
    
    /**
     * Trova un utente dall'oggetto.
     * @param mixed $user
     * @return mixed
     */
    private function findUserByObject($user) {
        if ($user instanceof ERegisteredUser) {
            return getEntityManager()->getRepository('ERegisteredUser')->findRegisteredUser($user->getEmail());
        } elseif ($user instanceof EAdmin) {
            return getEntityManager()->getRepository('EAdmin')->findAdmin($user->getEmail());
        }
        return null;
    }

     /**
     * Trova una carta di credito tramite numero.
     * @param string $cardNumber
     * @return ECreditCard|null
     */
    public function findCreditCard($cardNumber) {
        $dql = "SELECT car FROM ECreditCard car WHERE car.cardNumber = ?1";
        $query = getEntityManager()->createQuery($dql); 
        $query->setParameter(1, $cardNumber);
        $query->setMaxResults(1);
        return $query->getOneOrNullResult(); // Restituisce un singolo oggetto o null
    }

     /**
     * Trova una spedizione credito tramite inidirzzo e cap.
     * @param string $address
     * @param string $cap
     * @return array
     */
    public function findShipping($address, $cap){
        $dql = "SELECT sh FROM EShipping sh WHERE sh.address = ?1 AND sh.cap = ?2";
        $query = getEntityManager()->createQuery($dql);
        $query->setParameter(1, $address);
        $query->setParameter(2, $cap);
        $query->setMaxResults(1);
        return $query->getResult();
    }

     /**
     * Verifica se un untente ha comprato un prodotto per sbloccare la recensione.
     * @param mixed $product
     * @return bool
     */
    public function hasPurchasedProduct($product) {
        return getEntityManager()->getRepository('EReview')->hasPurchasedProduct($product);
    }

     /**
     * Restituisce le recensioni di un prodotto per l'amministratore.
     * @param mixed $admin
     * @param int $page
     * @param int $itemsPerPage
     * @return bool
     */
    public function getReviewAdmin($admin, $page, $itemsPerPage) {
        return getEntityManager()->getRepository('EReview')->getReviewAdmin($admin, $page, $itemsPerPage);
    }

     /**
     * Restituisce tutti i prodotti di un admin.
     * @param EAdmin $admin
     * @param int $page
     * @param array $filters
     * @return array
     */
    public function getAllProductsByAdmin(EAdmin $admin, $page, $filters){
        return getEntityManager()->getRepository('EProduct')->getAllProductsByAdmin($admin, $page, $filters);
    }

     /**
     * Fa aggiornare all'admin la categoria di un prodotto.
     * @param mixed $product
     * @param mixed $admin
     * @param mixed $category
     * @return void
     */
    public function updateAdminCatProduct($product, $admin, $category){
        getEntityManager()->getRepository('EProduct')->updateAdminCatProduct($product, $admin, $category);
    }

     /**
     * Verifica se una categoria ha prodotti associati.
     * @param mixed $category
     * @return int $count
     */
    public function hasProducts($category)
    {
        $dql = "SELECT COUNT(p.id) FROM EProduct p WHERE p.category = ?1";
        $query = getEntityManager()->createQuery($dql);
        $query->setParameter(1, $category);
        $count = $query->getSingleScalarResult();
        return $count > 0;
    }

     /**
     * Elimina una categoria dal database.
     * @param mixed $category
     * @return void
     */
    public function deleteCategory($category)
    {
        getEntityManager()->remove($category);
        getEntityManager()->flush();
    }

     /**
     * Esegue una soft delete di una spedizione.
     * @param EShipping $shipping
     * @return void
     */
    public function softDeleteShipping(EShipping $shipping) {
        $shipping->setDeleted(true);
        getEntityManager()->flush();
    }

     /**
     * Verifica se una spedizione può essere eliminata definitivamente.
     * @param mixed $address
     * @param string $cap
     * @return bool
     */
    public function canShippingBeHardDeleted($address, $cap): bool {
        return getEntityManager()->getRepository('EShipping')->canBeHardDeleted($address, $cap);
    }

     /**
     * Riattiva una spedizione precedentemente eliminata.
     * @param EShipping $shipping
     * @return void
     */
    public function reactivateShipping(EShipping $shipping) {
        $shipping->setDeleted(false);
        getEntityManager()->flush();
    }

     /**
     * Verifica se una carta di credito può essere eliminata definitivamente.
     * @param string $cardNumber
     * @return bool
     */
    public function canCreditCardBeHardDeleted($cardNumber): bool {
        return getEntityManager()->getRepository('ECreditCard')->canBeHardDeleted($cardNumber);
    }

     /**
     * Esegue una soft delete di una carta di credito.
     * @param ECreditCard $creditCard
     * @return void
     */
    public function softDeleteCreditCard(ECreditCard $creditCard) {
        $creditCard->setDeleted(true);
        getEntityManager()->flush();
    }

     /**
     * Riattiva una carta di credito precedentemente eliminata.
     * @param ECreditCard $creditCard
     * @return void 
     */
    public function reactivateCreditCard(ECreditCard $creditCard) {
        $creditCard->setDeleted(false);
        getEntityManager()->flush();
    }

     /**
     * Aggiunge dati a un ordine.
     * @param EOrder $order
     * @param array $data
     * @return void
     */
    public function addOrderData($order, $data) {
        getEntityManager()->getRepository('EOrder')->addOrderData($order, $data);
    }

     /**
     * Cancella un ordine.
     * @param EOrder $order
     * @return void
     */
    public function deleteOrder($order)
    {
        getEntityManager()->remove($order);
        getEntityManager()->flush();
    }

     /**
     * Restituisce tutti gli ordini paginati.
     * @param int $page
     * @param int $itemsPerPage
     * @return mixed
     */
    public function getOrders($page, $itemsPerPage) {
        return getEntityManager()->getRepository('EOrder')->getOrders($page, $itemsPerPage);
    }

     /**
     * Restituisce tutti gli utenti paginati.
     * @param int $page
     * @param int $itemsPerPage
     * @return mixed
     */
    public function getAllUsersPaginated($page, $itemsPerPage) {
        return getEntityManager()->getRepository('EAdmin')->getAllUsersPaginated($page,$itemsPerPage);
    }

     /**
     * Restituisce gli utenti filtrati e paginati.
     * Fa il collegamento con il repository EAdmin per ottenere gli utenti filtrati e paginati tramite adminId.
     * Perché solo gli admin possono cercare/filtrare gli utenti registrati.
     * @param int $adminId
     * @return mixed
     */
    public function getFilteredUsersPaginated($adminId){
        return getEntityManager()->getRepository('EAdmin')->getFilteredUsersPaginated($adminId);
    }

     /**
     * Restituisce gli ultimi quattro prodotti per la home page.
     * @param int $limit
     * @return array
     */
    public function getLatestProductsHome($limit = 4) {
        $array_product = $this->getRepository(EProduct::class)->getLatestNewProducts($limit);
    
        for ($i = 0; $i < sizeof($array_product); $i++) {
            $prod_item = FPersistentManager::getInstance()->find(EProduct::class, $array_product[$i]['productId']);
            $array_images = FPersistentManager::getInstance()->getAllImages($prod_item);
    
            // Prendi solo la prima immagine, se esiste
            if (is_array($array_images) && !empty($array_images)) {
                $array_product[$i]['images'] = $array_images[0]; // Prima immagine
            } else {
                $array_product[$i]['images'] = null; // Nessuna immagine
            }
        }
    
        return $array_product;
    }
    
    /**
     * Restituisce tutte le immagini di un prodotto.
     * @param mixed $product
     * @return array
     */
    public function getAllImages($product){
        return getEntityManager()->getRepository('EImage')->getAllImages($product);
    }

    /**
     * Inserisce una nuova immagine.
     * @param mixed $image
     * @return void
     */
    public function insertImage($image){
        getEntityManager()->getRepository('EImage')->insertImage($image);
    }

    /**
     * Trova un'immagine tramite ID.
     * @param mixed $image
     * @return mixed
     */
    public function findImage($image){
        return getEntityManager()->getRepository('EImage')->findImage($image);
    }

    /**
     * Aggiorna l'immagine di un prodotto.
     * @param mixed $product
     * @param mixed $image
     * @return void
     */
    public function updateImageProduct($product, $image){
        getEntityManager()->getRepository('EProduct')->updateImageProduct($product,$image);
    }

    /**
     * Elimina tutte le immagini associate a un ID prodotto.
     * @param mixed $productId
     * @return void
     */
    public function deleteAllImages($productId){
        getEntityManager()->getRepository('EImage')->deleteAllImages($productId);
    }

    /**
     * Aggiorna i dati di un prodotto.
     * @param mixed $product
     * @param array $array_data
     * @return void
     */
    public function updateProduct ($product, $array_data) {
        getEntityManager()->getRepository('EProduct')->updateProduct($product, $array_data);
    }

    /**
     * Esegue una soft delete su un utente.
     * @param mixed $user
     * @return mixed
     */
    public function softDeleteUser($user) {
        return getEntityManager()->getRepository('EAdmin')->softDeleteUser($user);
    }

    /**
     * Restituisce tutte le recensioni paginati.
     * @param int $page
     * @param int $itemsPerPage
     * @return mixed
     */
    public function getAllReviewsPaginated($page = 1, $itemsPerPage = 5) {
        return getEntityManager()->getRepository('EAdmin')->getAllReviewsPaginated($page,$itemsPerPage);
    }

    /**
     * Aggiunge una richiesta di rimborso per un ordine.
     * @param EOrder $order
     * @return void
     */
    public function addRefundRequest(EOrder $order): void {
        $em = getEntityManager(); // Ottieni l'EntityManager
        $refundRequest = new ERefundRequest($order); // Crea una nuova richiesta di reso/rimborso
        $em->persist($refundRequest); // Prepara l'entità per il salvataggio
        $em->flush(); // Salva l'entità nel database
    }
}