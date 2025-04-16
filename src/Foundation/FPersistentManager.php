<?php
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class FPersistentManager {
    //Singleton Class
    private static $instance;
    private $repositories = [];
    private function __construct(){

    }

    //Assicura che ci sia una sola istanza della classe FPersistentManager durante l'intero ciclo di vita dell'applicazione. Questo è utile per gestire la persistenza delle entità in modo centralizzato e coerente.
    public static function getInstance(){
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public static function setInstance($instance)
    {
        self::$instance = $instance;
    }

    //Utilizzato per ottenere il repository di una specifica classe di entità.
    public function getRepository(string $entityClass): EntityRepository
    {
        if (!isset($this->repositories[$entityClass])) {
            $this->repositories[$entityClass] = getEntityManager()->getRepository($entityClass);
        }

        return $this->repositories[$entityClass];
    }

    //Utilizzato per rendere un'entità gestita e persistente nel contesto di persistenza di Doctrine
    public function persist($entity): void
    {
       getEntityManager()->persist($entity);
    }

    //Utilizzato per rimuovere un'entità dal contesto di persistenza di Doctrine
    public function remove($entity): void
    {
       getEntityManager()->remove($entity);
    }

    //Utilizzato per applicare tutte le modifiche pendenti al database
    public function flush(): void
    {
       getEntityManager()->flush();
    }

    //Utilizzato per pulire il contesto di persistenza di Doctrine
    public function clear(): void
    {
       getEntityManager()->clear();
    }

    //Utilizzato per trovare un'entità nel database tramite il suo identificatore
    public function find(string $entityClass, $id)
    {
        return $this->getRepository($entityClass)->find($id);
    }

    //Utilizzato per trovare tutte le entità di una specifica classe nel database
    public function findAll(string $entityClass): array
    {
        return $this->getRepository($entityClass)->findAll();
    }

    //Utilizzato per trovare entità nel database basate su criteri specifici
    public function findBy(string $entityClass, array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array
    {
        return $this->getRepository($entityClass)->findBy($criteria, $orderBy, $limit, $offset);
    }

    //Utilizzato per trovare una singola entità nel database basata su criteri specifici
    public function findOneBy(string $entityClass, array $criteria)
    {
        return $this->getRepository($entityClass)->findOneBy($criteria);
    }

    //Utilizzato per aggiornare lo stato di un'entità con i dati attuali dal database
    public function refresh($entity){
        getEntityManager()->refresh($entity);
    }

    //Utilizzato per trovare un utente nel database, sia che l'utente sia un oggetto di una classe specifica (ERegisteredUser, EUnRegisteredUser, EAdmin) o una stringa (email).
    public function findUtente($user){
        /** Se $cliente è un oggetto richiamerà findCliente($cliente->getEmail())
         * altrimenti se è una stringa (cioè se è una email) richiamerà findCliente($cliente)
         */
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

    //Utilizzato per trovare un acquirente nel database tramite il suo identificatore
    public function findRegisteredUserById($registeredUserId){
        return getEntityManager()->getRepository('ERegisteredUser')->findRegisteredUserById($registeredUserId);
    }

    //Utilizzato per trovare un admin nel database tramite il suo identificatore
    public function findAdminById($adminId){
        return getEntityManager()->getRepository('EAdmin')->findAdminById($adminId);
    }

    //Inserire un nuovo utente registrato nel database
    public function insertNewUtente(ERegisteredUser $new_user): void
    {
        getEntityManager()->getRepository('ERegisteredUser')->insertNewRegisteredUser($new_user);
    }

    //Aggiornare la password di un utente
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
    public function updateUtente($user, array $array_data): void
    {
        if($user instanceof ERegisteredUser){
            getEntityManager()->getRepository('ERegisteredUser')->updateRegisteredUser($user, $array_data);
        } else if($user instanceof EAdmin){
            getEntityManager()->getRepository('EAdmin')->updateAdmin($user, $array_data);
        }
    }

    //Utilizzato per eliminare un utente registrato (ERegisteredUser) o un amministratore (EAdmin) dal database
    public function deleteUtente($user): void
    {
        if($user instanceof ERegisteredUser){
            getEntityManager()->getRepository('ERegisteredUser')->deleteRegisteredUser($user);
        } else if($user instanceof EAdmin){
            getEntityManager()->getRepository('EAdmin')->deleteAdmin($user);
        }
    }

    public function getAllProducts($currentPage){
        return getEntityManager()->getRepository('EProduct')->getAllProducts($currentPage);
    }

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

    public function getAllCategories(){
        return getEntityManager()->getRepository('ECategory')->getAllCategories();
    }

    public function insertProduct($product){
        getEntityManager()->getRepository('EProduct')->insertProduct($product);
    }

    public function findCategory($category){
        return getEntityManager()->getRepository('ECategory')->findCategory($category);
    }

    //Aggiornare le informazioni di un prodotto, inclusa la categoria
    public function updateCatProdotto($product, $category){
        getEntityManager()->getRepository('EProduct')->updateCatProdotto($product, $category);
    }

    public function deleteProduct($product){
        getEntityManager()->getRepository('EProduct')->deleteProduct($product);
    }

    public function getAllOrders($user, $page) {
        return getEntityManager()->getRepository('EItemOrder')->getAllOrders($user, $page);
    }

    public function newOrder($idShipping, $cap, $carta_id, $cart) {
        return getEntityManager()->getRepository('EOrder')->newOrder($idShipping, $cap, $carta_id, $cart);
    }

    public function addProductOrder(EOrder $order, EProduct $product, $quantity) {
        return getEntityManager()->getRepository('EItemOrder')->addProductOrder($order, $product, $quantity);
    }
    public function getAllShippingUser(ERegisteredUser $registeredUser) {
        return getEntityManager()->getRepository('EShipping')->getAllShippingUser($registeredUser->getIdRegisteredUser());
    }

    public function getAllCreditCardUser(ERegisteredUser $registeredUser) {
        return getEntityManager()->getRepository('ECreditCard')->getAllCreditCardUser($registeredUser->getIdRegisteredUser());
    }

    public function insertShipping($array_data){
        getEntityManager()->getRepository('EShipping')->insertShipping($array_data);
    }
    public function deleteShipping($idShipping){
        getEntityManager()->getRepository('EShipping')->deleteShipping($idShipping);
    }
    public function insertCreditCard($array_data) {
        getEntityManager()->getRepository('ECreditCard')->insertCreditCard($array_data);
    }

    public function deleteCreditCard($cardNumber) {
        getEntityManager()->getRepository('ECreditCard')->deleteCreditCard($cardNumber);
    }

    public function getOrderUser(){
        return getEntityManager()->getRepository('EOrder')->findOrderUser($_SESSION['user']->getIdRegisteredUser());
    }

    //Per cercare prodotti in base a una query di ricerca e, opzionalmente, una categoria.
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

    public function getProductFiltered($filters, $page = 1, $pageSize = 4) {
        $qb = getEntityManager()->createQueryBuilder();
        $qb->select('p')
        ->from('EProduct', 'p')
        ->where('p.is_deleted = false');

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

        // Imposta i risultati per la paginazione
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

    public function getAllBrands() {
        $dql = "SELECT DISTINCT p.brand FROM EProduct p";
        $query = getEntityManager()->createQuery($dql);
        $results = $query->getResult();
        return array_column($results, 'brand');
    }

    public function findItemOrder($idOrder, $productId) {
        return getEntityManager()->getRepository('ItemOrder')->findItemOrder($idOrder, $productId);
    }

    //Aggiornare un'entità nel database
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
    
    public function getProductById($productId){
        return getEntityManager()->getRepository(EProduct::class)->getProductById($productId);
    }

    public function getReviewsProduct($product, $page = 1, $itemsPerPage = 5) {
        return getEntityManager()->getRepository('EReview')->getReviewsProduct($product, $page, $itemsPerPage);
    }

    public function addReview($review) {
        getEntityManager()->getRepository('EReview')->addReview($review);
    }
    public function getReviewUser($registeredUser, $product) {
        return getEntityManager()->getRepository('EReview')->getReviewUser($registeredUser, $product);
    }

    public function getProductForAdmin($page = 1, $itemsPerPage = 10){
        $offset = ($page - 1) * $itemsPerPage;
        $limit = $itemsPerPage + 1;  // Richiediamo un elemento in più per determinare se c'è una pagina successiva
        
        $em = getEntityManager();

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
    
    private function findUserByEmail($email) {
        $user = getEntityManager()->getRepository('ERegisteredUser')->findRegisteredUser($email);
        if (!$user) {
            $user = getEntityManager()->getRepository('EAdmin')->findAdmin($email);
        }
        return $user;
    }
    
    private function findUserByObject($user) {
        if ($user instanceof ERegisteredUser) {
            return getEntityManager()->getRepository('ERegisteredUser')->findRegisteredUser($user->getEmail());
        } elseif ($user instanceof EAdmin) {
            return getEntityManager()->getRepository('EAdmin')->findAdmin($user->getEmail());
        }
        return null;
    }

    public function findCreditCard($cardNumber) {
        $dql = "SELECT car FROM ECreditCard car WHERE car.cardNumber = ?1";
        $query = getEntityManager()->createQuery($dql); // $this->getEntityManager()
        $query->setParameter(1, $cardNumber);
        $query->setMaxResults(1);
        return $query->getOneOrNullResult(); // Restituisce un singolo oggetto o null
    }

    public function findShipping($address, $cap){
        $dql = "SELECT sh FROM EShipping sh WHERE sh.address = ?1 AND sh.cap = ?2";
        $query = getEntityManager()->createQuery($dql);
        $query->setParameter(1, $address);
        $query->setParameter(2, $cap);
        $query->setMaxResults(1);
        return $query->getResult();
    }

    public function hasPurchasedProduct($product) {
        return getEntityManager()->getRepository('EReview')->hasPurchasedProduct($product);
    }

    public function getReviewAdmin($admin, $page, $itemsPerPage) {
        return getEntityManager()->getRepository('EReview')->getReviewAdmin($admin, $page, $itemsPerPage);
    }

    public function getAllProductsByAdmin(EAdmin $admin, $page, $filters){
        return getEntityManager()->getRepository('EProduct')->getAllProductsByAdmin($admin, $page, $filters);
    }

    public function updateAdminCatProduct($product, $admin, $category){
        getEntityManager()->getRepository('EProduct')->updateAdminCatProduct($product, $admin, $category);
    }

    // verificherà se una categoria ha prodotti associati.
    public function hasProducts($category)
    {
        $dql = "SELECT COUNT(p.id) FROM EProduct p WHERE p.category = ?1";
        $query = getEntityManager()->createQuery($dql);
        $query->setParameter(1, $category);
        $count = $query->getSingleScalarResult();
        return $count > 0;
    }

    // eliminerà una categoria dal database
    public function deleteCategory($category)
    {
        getEntityManager()->remove($category);
        getEntityManager()->flush();
    }

    public function softDeleteShipping(EShipping $shipping) {
        $shipping->setDeleted(true);
        getEntityManager()->flush();
    }

    public function canShippingBeHardDeleted($address, $cap): bool {
        return getEntityManager()->getRepository('EShipping')->canBeHardDeleted($address, $cap);
    }

    public function reactivateShipping(EShipping $shipping) {
        $shipping->setDeleted(false);
        getEntityManager()->flush();
    }

    public function canCreditCardBeHardDeleted($cardNumber): bool {
        return getEntityManager()->getRepository('ECreditCard')->canBeHardDeleted($cardNumber);
    }

    public function softDeleteCreditCard(ECreditCard $creditCard) {
        $creditCard->setDeleted(true);
        getEntityManager()->flush();
    }

    public function reactivateCreditCard(ECreditCard $creditCard) {
        $creditCard->setDeleted(false);
        getEntityManager()->flush();
    }

    public function addOrderData($order, $data) {
        getEntityManager()->getRepository('EOrder')->addOrderData($order, $data);
    }

    public function deleteOrder($order)
    {
        getEntityManager()->remove($order);
        getEntityManager()->flush();
    }

    public function getOrders($page, $itemsPerPage) {
        return getEntityManager()->getRepository('EOrder')->getOrders($page, $itemsPerPage);
    }

    public function getAllUsersPaginated($page, $itemsPerPage) {
        return getEntityManager()->getRepository('EAdmin')->getAllUsersPaginated($page,$itemsPerPage);
    }

    public function getFilteredUsersPaginated($adminId){
        return getEntityManager()->getRepository('EAdmin')->getFilteredUsersPaginated($adminId);
    }

    public function getLatestProductsHome($limit = 4){

        
        $array_product = $this->getRepository(EProduct::class)->getLatestNewProducts($limit);
    
        for($i = 0; $i < sizeof($array_product); $i++){
            $prod_item = FPersistentManager::getInstance()->find(EProduct::class,$array_product[$i]['productId']);
            $array_images = FPersistentManager::getInstance()->getAllImages($prod_item);
            if (is_array($array_images)) {
                foreach($array_images as $image) {
                    $array_product[$i]['images'] = $image;
                }
            }
        }
        return $array_product;
    }
    
    public function getAllImages($product){
        return getEntityManager()->getRepository('EImage')->getAllImages($product);
    }

    public function insertImage($image){
        getEntityManager()->getRepository('EImage')->insertImage($image);
    }

    public function findImage($image){
        return getEntityManager()->getRepository('EImage')->findImage($image);
    }

    public function updateImageProduct($product, $image){
        getEntityManager()->getRepository('EProduct')->updateImageProduct($product,$image);
    }

    public function deleteAllImages($productId){
        getEntityManager()->getRepository('EImage')->deleteAllImages($productId);
    }

    public function updateProduct ($product, $array_data) {
        getEntityManager()->getRepository('EProduct')->updateProduct($product, $array_data);
    }

    public function softDeleteUtente($user) {
        return getEntityManager()->getRepository('EAdmin')->softDeleteUtente($user);
    }

    public function getAllReviewsPaginated($page = 1, $itemsPerPage = 5) {
        return getEntityManager()->getRepository('EAdmin')->getAllReviewsPaginated($page,$itemsPerPage);
    }

    public function addRefundRequest(EOrder $order): void {
        $em = getEntityManager(); // Ottieni l'EntityManager
        $refundRequest = new ERefundRequest($order); // Crea una nuova richiesta di reso/rimborso
        $em->persist($refundRequest); // Prepara l'entità per il salvataggio
        $em->flush(); // Salva l'entità nel database
    }
}