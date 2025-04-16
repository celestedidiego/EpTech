<?php
use Doctrine\ORM\EntityRepository;

class FRegisteredUser extends EntityRepository {

    //Trova un utente registrato tramite l'email
    public function findRegisteredUser($email){
        $dql = "SELECT ru FROM ERegisteredUser ru WHERE ru.email = ?1";
        $query = getEntityManager()->createQuery($dql);
        $query->setParameter(1, $email);
        $query->setMaxResults(1);
        return $query->getResult();
    }

    //Trova un utente registrato tramite Id
    public function findRegisteredUserById($id){
        $dql = "SELECT ru FROM ERegisteredUser ru WHERE ru.registeredUserId = ?1";
        $query = getEntityManager()->createQuery($dql);
        $query->setParameter(1, $id);
        $query->setMaxResults(1);
        return $query->getResult();
    }

    public function insertNewRegisteredUser(ERegisteredUser $user){
        $em = getEntityManager();
        $em->persist($user);
        $em->flush();
    }

    public function deleteRegisteredUser(ERegisteredUser $user) {
        $em = getEntityManager();
        $found_user = $em->find(ERegisteredUser::class, $user->getIdRegisteredUser());
        if ($found_user) {
            $found_user->setDeleted(true);
            $em->persist($found_user);
            $em->flush();
        }
    }



    //Aggiornare la password dell'utente registrato
    public function updatePass(ERegisteredUser $user, $new_password){
        $em = getEntityManager();
        $found_user = $em->find(ERegisteredUser::class, $user->getIdRegisteredUser());
        $found_user->setPassword(password_hash($new_password, PASSWORD_DEFAULT));
         //Aggiorno la sessione
         $_SESSION['user']->setPassword(password_hash($new_password, PASSWORD_DEFAULT));
        $em->persist($found_user);
        $em->flush();
    }

    public function updateRegisteredUser(ERegisteredUser $user, $array_data){
        $em = getEntityManager();
        $found_user = $em->find(ERegisteredUser::class, $user->getIdRegisteredUser());
        $found_user->setName($array_data['name']);
        $found_user->setSurname($array_data['surname']);
        $found_user->setUsername($array_data['username']);
        //Aggiorno la sessione
        $_SESSION['user']->setName($array_data['name']);
        $_SESSION['user']->setSurname($array_data['surname']);
        $_SESSION['user']->setUsername($array_data['username']);
        $em->persist($found_user);
        $em->flush();
    }
}