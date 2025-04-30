<?php
use Doctrine\ORM\EntityRepository;

class FAdmin extends EntityRepository {

    public function findAdmin($email){
        $dql = "SELECT a FROM EAdmin a WHERE a.email = ?1";
        $query = getEntityManager()->createQuery($dql);
        $query->setParameter(1, $email);
        $query->setMaxResults(1);
        return $query->getResult();
    }

    public function findAdminById($adminId){
        $dql = "SELECT a FROM EAdmin a WHERE a.adminId = ?1";
        $query = getEntityManager()->createQuery($dql);
        $query->setParameter(1, $adminId);
        $query->setMaxResults(1);
        return $query->getResult();
    }

    public function updatePass(EAdmin $admin, $new_password){
        $em = getEntityManager();
        $found_admin = $em->find(EAdmin::class, $admin->getIdAdmin());
        $found_admin->setPassword(password_hash($new_password, PASSWORD_DEFAULT));
         //Aggiorno la sessione
        $_SESSION['user']->setPassword(password_hash($new_password, PASSWORD_DEFAULT));
        $em->persist($found_admin);
        $em->flush();
    }

    public function updateAdmin(EAdmin $admin, $array_data){
        $em = getEntityManager();
        $found_admin = $em->find(EAdmin::class, $admin->getIdAdmin());
        $found_admin->setName($array_data['name']);
        $found_admin->setSurname($array_data['surname']);
        //Aggiorno la sessione
        $_SESSION['user']->setName($array_data['name']);
        $_SESSION['user']->setSurname($array_data['surname']);
        $em->persist($found_admin);
        $em->flush();

    }

    public function deleteAdmin(EAdmin $admin) {
        $em = getEntityManager();
        $found_admin = $em->find(EAdmin::class, $admin->getIdAdmin());
        $em->remove($found_admin);
        $em->flush();
    }

    public function softDeleteUser($user) {
        $em=getEntityManager();
        $user->setDeleted(true);
        $em->persist($user);
        $em->flush();
    }

    public function getAllUsersPaginated($page = 1, $itemsPerPage = 10)
    {
        $offset = ($page - 1) * $itemsPerPage;
        $limit = $itemsPerPage + 1;  // Richiediamo un elemento in più per determinare se c'è una pagina successiva
        
        // Query per gli utenti registrati
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('ru.registeredUserId as registeredUserId', 'ru.name', 'ru.surname','ru.is_blocked', 'ru.is_deleted', 'ru.email')
           ->from('ERegisteredUser', 'ru')
           ->where('ru.is_deleted = :isDeleted')
           ->setParameter('isDeleted', false)
           ->setMaxResults($limit)
           ->setFirstResult($offset)
           ->orderBy('ru.registeredUserId', 'ASC');

        // Esecuzione della query
        $users = $qb->getQuery()->getResult();

        // Tagliamo l'array al numero di elementi richiesti
        $hasMorePages = count($users) > $itemsPerPage;
        $users = array_slice($users, 0, $itemsPerPage);

        // Conteggio totale degli utenti (questa query verrà eseguita solo quando necessario)
        $totalItems = $this->getTotalUsersCount();

        return [
            'users' => $users,
            'totalItems' => $totalItems,
            'itemsPerPage' => $itemsPerPage,
            'currentPage' => $page,
            'totalPages' => ceil($totalItems / $itemsPerPage),
            'hasMorePages' => $hasMorePages
        ];
    }

    public function getFilteredUsersPaginated($id, $page = 1, $itemsPerPage = 10)
    {
        $offset = ($page - 1) * $itemsPerPage;
        $limit = $itemsPerPage + 1; // Richiediamo un elemento in più per determinare se c'è una pagina successiva

        $em = getEntityManager();

        // Query per gli utenti registrati
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('ru.registeredUserId as registeredUserId', 'ru.name', 'ru.surname', 'ru.is_blocked', 'ru.is_deleted', 'ru.email')
        ->from('ERegisteredUser', 'ru')
        ->where('ru.is_deleted = :isDeleted')
        ->andWhere('ru.registeredUserId = :userId') // Filtra per ID utente
        ->setParameter('isDeleted', false)
        ->setParameter('userId', $id)
        ->setMaxResults($limit)
        ->setFirstResult($offset)
        ->orderBy('ru.registeredUserId', 'ASC');

        // Esecuzione della query
        $users = $qb->getQuery()->getResult();

        // Tagliamo l'array al numero di elementi richiesti
        $hasMorePages = count($users) > $itemsPerPage;
        $users = array_slice($users, 0, $itemsPerPage);

        // Conteggio totale degli utenti (questa query verrà eseguita solo quando necessario)
        $totalItems = count($users); // Modificato per riflettere il filtro applicato

        return [
            'users' => $users,
            'totalItems' => $totalItems,
            'itemsPerPage' => $itemsPerPage,
            'currentPage' => $page,
            'totalPages' => ceil($totalItems / $itemsPerPage),
            'hasMorePages' => $hasMorePages
        ];
    }

    private function getTotalUsersCount()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('COUNT(ru.registeredUserId)')
        ->from('ERegisteredUser', 'ru')
        ->where('ru.is_deleted = :isDeleted')
        ->setParameter('isDeleted', false);

        return $qb->getQuery()->getSingleScalarResult();
    }


    public function getAllReviewsPaginated($page = 1, $itemsPerPage = 5)
    {
        $offset = ($page - 1) * $itemsPerPage;
        $em = getEntityManager();

        // Query per ottenere le recensioni paginati
        $qb = $em->createQueryBuilder();
        $qb->select('r')
            ->from('EReview', 'r')
            ->leftJoin('r.registeredUser', 'ru') // Relazione con l'utente che ha scritto la recensione
            ->leftJoin('r.product', 'p') // Relazione con il prodotto recensito
            ->setFirstResult($offset)
            ->setMaxResults($itemsPerPage)
            ->orderBy('r.idReview', 'ASC');

        $query = $qb->getQuery();

        // Query semplificata per contare il totale delle recensioni
        $countQb = $em->createQueryBuilder();
        $countQb->select('COUNT(r.idReview)')
                ->from('EReview', 'r');
        
        $totalItems = $countQb->getQuery()->getSingleScalarResult();

        // Calcolo del numero totale di pagine
        $totalPages = ceil($totalItems / $itemsPerPage);

        // Ottieni i risultati come oggetti Doctrine
        $items = $query->getResult(); // Usa getResult() invece di getArrayResult()

        return [
            'items' => $items,
            'totalItems' => $totalItems,
            'n_reviews' => $totalItems,
            'itemsPerPage' => $itemsPerPage,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ];
    }

    public function findReviewsByUserId($userId, $page = 1, $itemsPerPage = 5)
    {
        $offset = ($page - 1) * $itemsPerPage;
        $em = getEntityManager();

        // Query per ottenere le recensioni paginati
        $qb = $em->createQueryBuilder();
        $qb->select('r.idReview', 'r.text', 'r.vote' , 'ru.registeredUserId as user_id', 'ru.name as user_name', 'ru.surname as user_surname', 'p.productId as product_id', 'p.nameProduct as product_name')
        ->from('EReview', 'r')
        ->leftJoin('r.user', 'ru') // Relazione con l'utente che ha scritto la recensione
        ->leftJoin('r.product', 'p') // Relazione con il prodotto recensito
        ->where('r.user = :user') // Filtra per ID utente
        ->setParameter('user', $userId)
        ->setFirstResult($offset)
        ->setMaxResults($itemsPerPage)
        ->orderBy('r.idReview', 'ASC');

        $query = $qb->getQuery();

        // Query semplificata per contare il totale delle recensioni
        $countQb = $em->createQueryBuilder();
        $countQb->select('COUNT(r.idReview)')
                ->from('EReview', 'r')
                ->where('r.user = :user') // Filtra per ID utente
                ->setParameter('user', $userId);
        
        $totalItems = $countQb->getQuery()->getSingleScalarResult();

        // Calcolo del numero totale di pagine
        $totalPages = ceil($totalItems / $itemsPerPage);

        // Ottieni i risultati come array
        $items = $query->getArrayResult();

        return [
            'items' => $items,
            'totalItems' => $totalItems,
            'itemsPerPage' => $itemsPerPage,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ];
    }
}