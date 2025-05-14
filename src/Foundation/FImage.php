<?php
use Doctrine\ORM\EntityRepository;

/**
 * Class FImage
 * Repository per la gestione delle immagini dei prodotti.
 */
class FImage extends EntityRepository {
    /**
     * Inserisce una nuova immagine.
     * @param EImage $image
     * @return void
     */
    public function insertImage(EImage $image){
        $em = getEntityManager();
        $em->persist($image);
        $em->flush();
    }

    /**
     * Trova un'immagine tramite ID.
     * @param int $image
     * @return array
     */
    public function findImage($image){
        $dql = "SELECT im FROM EImage im WHERE im.idImage = ?1";
        $query = getEntityManager()->createQuery($dql);
        $query->setParameter(1, $image);
        $query->setMaxResults(1);
        return $query->getResult();
    }

    /**
     * Restituisce tutte le immagini di un prodotto come array.
     * @param EProduct $product
     * @return array
     */
    public function getAllImages(EProduct $product){
        $dql = "SELECT im
            FROM EImage im
            WHERE im.product = ?1
            ORDER BY im.name ASC"; //ordinamento dlle immagini per nome file
        $query = getEntityManager()->createQuery($dql);
        $query->setParameter(1, $product);
        $tmp_images = $query->getArrayResult();

        $array_images = [];
        foreach($tmp_images as $image){
            //Poiché $array_immagini[0]['imageData'] contiene l'id della Risorsa, uso
            //la funzione stream_get_contents($array_immagini[0]['imageData']) per 
            //riottenere la stringa base64 memorizzata nel database per poi
            //assegnarla di nuovo all'array 
            $image['imageData'] = stream_get_contents($image['imageData']);
            $array_images[] = $image;
        }
        return $array_images;
    }

    /**
     * Restituisce tutte le immagini di un prodotto come oggetti Doctrine.
     * @param EProduct $product
     * @return array
     */
    public function getAllObjectImages(EProduct $product){
        $dql = "SELECT im
            FROM EImage im
            WHERE im.product = ?1";
        $query = getEntityManager()->createQuery($dql);
        $query->setParameter(1, $product);
        return $query->getResult();
    }

    /**
     * Elimina tutte le immagini associate a un prodotto.
     * @param int $productId
     * @return void
     */
    public function deleteAllImages($productId){
        $em = getEntityManager();
        $found_product = $em->find(EProduct::class, $productId);
        $found_images = self::getAllObjectImages($found_product);
        if(!$found_product->isDeleted()){
            foreach($found_images as $image){
                $em->remove($image);
            }
        }
        $em->flush();
    }
}