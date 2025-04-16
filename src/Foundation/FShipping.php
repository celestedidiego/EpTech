<?php
use Doctrine\ORM\EntityRepository;

class FShipping extends EntityRepository {

    public function findShipping($address, $cap){
        $dql = "SELECT sh FROM EShipping sh WHERE sh.address = ?1 AND sh.cap = ?2";
        $query = getEntityManager()->createQuery($dql);
        $query->setParameter(1, $address);
        $query->setParameter(2, $cap);
        $query->setMaxResults(1);
        return $query->getResult();
    }

    public function insertShipping($array_data){
        $new_shipping = new EShipping($array_data['address'], $array_data['cap'], $array_data['city'], $array_data['recipientName'], $array_data['recipientSurname']);
        $em = getEntityManager();
        $found_user = $em->find(ERegisteredUser::class, $_SESSION['user']->getIdRegisteredUser());
        $new_shipping->setShippingRegisteredUser($found_user);
        $em->persist($new_shipping);
        $em->flush();
    }

    public function getAllShippingUser($idUser)
    {
            return getEntityManager()->createQueryBuilder('sh')
            ->select('sh')
            ->from(EShipping::class, 'sh')  // Aggiunta esplicita della clausola FROM
            ->where('sh.shippingRegisteredUser = :idUser')
            ->setParameter('idUser', $idUser)
            ->orderBy('sh.is_deleted', 'ASC')
            ->addOrderBy('sh.address', 'ASC')
            ->getQuery()
            ->getResult();
        
    }

    public function deleteShipping(EShipping $address)
    {
        $em = $this->getEntityManager();
        $found_shipping = $em->getRepository(EShipping::class)->findOneBy([
            'address' => $address->getAddress(),
            'cap' => $address->getCap(),
            'city' => $address->getCity(),
            'recipientName' => $address->getRecipientName(),
            'recipientSurname' => $address->getRecipientSurname()
        ]);
        if ($found_shipping) {
            $em->remove($found_shipping);
            $em->flush();
        } else {
            throw new \Exception("Spedizione non trovata.");
        }
    }

    public function findAllActive()
    {
        return getEntityManager()->createQueryBuilder()
            ->where('sh.is_deleted = :isDeleted')
            ->setParameter('isDeleted', false)
            ->getQuery()
            ->getResult();
    }

    public function softDelete(EShipping $shipping)
    {
        $shipping->setDeleted(true);
        getEntityManager()->flush();
    }

    public function canBeHardDeleted($address, $cap): bool
    {
        $qb = getEntityManager()->createQueryBuilder();
        $count = $qb->select('COUNT(o.idOrder)')
            ->from('EOrder', 'o')
            ->join('o.shipping', 'sh')
            ->where('sh.address = :address')
            ->andWhere('sh.cap = :cap')
            ->setParameter('address', $address)
            ->setParameter('cap', $cap)
            ->getQuery()
            ->getSingleScalarResult();

        return $count === 0;
    }

}
