<?php

namespace App\Repository;

use ApiPlatform\Doctrine\Orm\Paginator;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    const ITEMS_PER_PAGE = 20;

    public function __construct(
        ManagerRegistry $registry
    ) {
        parent::__construct($registry, Article::class);
    }

    public function getArticles(int $page = 1): Paginator
    {
        $firstResult = ($page -1) * self::ITEMS_PER_PAGE;

        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder->select('a', 's', 'au')
            ->from(Article::class, 'a')
            ->join('a.source', 's')
            ->join('a.author', 'au');

        $query = $queryBuilder->getQuery()
            ->setFirstResult($firstResult)
            ->setMaxResults(self::ITEMS_PER_PAGE);

        $doctrinePaginator = new DoctrinePaginator($query);
        $paginator = new Paginator($doctrinePaginator);

        return $paginator;
    }

//    /**
//     * @return Article[] Returns an array of Article objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Article
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
