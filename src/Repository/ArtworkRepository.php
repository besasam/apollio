<?php

namespace App\Repository;

use App\Entity\Artwork;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Artwork|null find($id, $lockMode = null, $lockVersion = null)
 * @method Artwork|null findOneBy(array $criteria, array $orderBy = null)
 * @method Artwork[]    findAll()
 * @method Artwork[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArtworkRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Artwork::class);
    }

    public function getArtworks(int $number=10, int $offset=0)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT a FROM App\Entity\Artwork a order by a.created_at desc'
        )->setMaxResults($number)->setFirstResult($offset);

        //TODO verify if setMaxResults->setFirstResult works, or if order has to be flipped

        return $query->execute();
    }
    // /**
    //  * @return Artwork[] Returns an array of Artwork objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Artwork
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
