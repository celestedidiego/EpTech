<?php
use Doctrine\ORM\EntityRepository;

class FCreditCard extends EntityRepository {
    
    public function findCreditCard($cardNumber) {
        $dql = "SELECT car FROM ECreditCard car WHERE car.cardNumber = ?1";
        $query = $this->getEntityManager()->createQuery($dql); 
        $query->setParameter(1, $cardNumber);
        $query->setMaxResults(1);
        return $query->getOneOrNullResult(); // Restituisce un singolo oggetto o null
    }

    public function insertCreditCard($array_data){
        $new_creditCard = new ECreditCard($array_data['cardNumber'], $array_data['cardHolderName'], $array_data['endDate'], $array_data['cvv']);
        $em = getEntityManager();
        $found_user = $em->find(ERegisteredUser::class, $_SESSION['user']->getIdRegisteredUser());
        $new_creditCard->setRegisteredUser($found_user);
        $em->persist($new_creditCard);
        $em->flush();
    }

    public function getAllCreditCardUser($idUser)
    {
        return getEntityManager()->createQueryBuilder('car')
            ->select('car')
            ->from(ECreditCard::class, 'car')
            ->where('car.registeredUser = :idUser')
            ->setParameter('idUser', $idUser)
            ->orderBy('car.is_deleted', 'ASC')
            ->addOrderBy('car.cardNumber', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function deleteCreditCard(ECreditCard $creditCard) {
        $em = $this->getEntityManager(); 
        $found_creditCard = $em->find(ECreditCard::class, $creditCard->getCardNumber());
        if ($found_creditCard) {
            $em->remove($found_creditCard);
            $em->flush();
        } else {
            echo "Carta di credito non trovata.";
        }
    }

    public function findAllActive()
    {
        return getEntityManager()->createQueryBuilder()
            ->where('car.is_deleted = :isDeleted')
            ->setParameter('isDeleted', false)
            ->getQuery()
            ->getResult();
    }

    public function softDelete(ECreditCard $creditCard)
    {
        $creditCard->setDeleted(true);
        getEntityManager()->flush();
    }

    public function canBeHardDeleted($cardNumber): bool
    {
        $qb = getEntityManager()->createQueryBuilder();
        $count = $qb->select('COUNT(o.id_ordine)')
            ->from('EOrder', 'o')
            ->where('o.card_number = :cardNumber')
            ->setParameter('cardNumber', $cardNumber)
            ->getQuery()
            ->getSingleScalarResult();

        return $count === 0;
    }

}