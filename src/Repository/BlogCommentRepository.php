<?php

namespace App\Repository;

use App\Entity\BlogComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method BlogComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method BlogComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method BlogComment[]    findAll()
 * @method BlogComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogCommentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, BlogComment::class);
    }


    public function findByBlog($blogId)
    {
        /**************************************************************************************************************
            SELECT * FROM blog_comment bc
            JOIN blog_comment_blog bcb ON bc.id = bcb.blog_comment_id
            WHERE bcb.blog_id = 2
         */




        /**/
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        /**/
        $qb->select('bc.*')
            ->from('blog_comment', 'bc')
            ->innerJoin('blog_comment_blog', 'bcb', 'ON', 'bc.id = bcb.blog_comment_id')
            //->where('bcb.blog_id = :blog_id')
            //->setParameter('blog_id', $blogId)
        ;

        $query = $qb->getQuery();

        dump($query);
        dump($query->execute());
        /**/

        return $this
            ->createQueryBuilder('comment')
            ->innerJoin('')
            //->select('comment')


            ->getQuery()
            ->getResult();

    }

//    /**
//     * @return BlogComment[] Returns an array of BlogComment objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BlogComment
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
