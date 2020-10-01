<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findHomepageProducts()
    {
        // DQL : Doctrine Query Language
        /*
        $dql = "SELECT p FROM App\Entity\Product p
                WHERE p.price <= :maxPrice
                ORDER BY p.dateCreated DESC";

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('maxPrice', 10);
        $results = $query->getResult();
        return $results;
        */

        // QueryBuilder : ensemble de méthodes OO qui permettent de construire dynamiquement une requête DQL
        $queryBuilder = $this->createQueryBuilder('p');
        $queryBuilder->addOrderBy('p.dateCreated', 'DESC');

        $maxPrice = 10;

        if ($maxPrice > 0) {
            $queryBuilder->andWhere('p.price <= :maxPrice');
            $queryBuilder->setParameter('maxPrice', 10);
        }

        $query = $queryBuilder->getQuery();
        $results = $query->getResult();
        return $results;
    }


    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
