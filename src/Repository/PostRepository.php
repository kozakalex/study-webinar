<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Post::class);
        $this->paginator = $paginator;
    }

    public function getAll(int $page = 1, int $limit = 5, ?string $sortBy = null, ?string $orderBy = null)
    {
        $qb = $this->createQueryBuilder('p')
            ->setMaxResults(5)
            ->setFirstResult(5);

        if ($sortBy) {
            $qb->orderBy('p.' . $sortBy, $orderBy);
        }

        return $this->paginator->paginate($qb->getQuery(), 1, 5);

//        return $qb->getQuery()->getResult();
    }

}
