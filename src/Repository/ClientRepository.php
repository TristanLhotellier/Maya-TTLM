<?php

namespace App\Repository;

use App\Entity\Client;
use App\Entity\ClientRecherche;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    /**
     * @return Query
     */
    public function findAllByCriteria(ClientRecherche $clientRecherche): Query
    {
        // le "c" est un alias utilisé dans la requête
        $qb = $this->createQueryBuilder('c')
            ->orderBy('c.nom', 'ASC');

        if ($clientRecherche->getTelephone()) {
            $qb->andWhere('c.telephone LIKE :telephone')
                ->setParameter('telephone', $clientRecherche->getTelephone().'%');
        }

        if ($clientRecherche->getNom()) {
            $qb->andWhere('c.nom LIKE :nom')
                ->setParameter('nom', $clientRecherche->getNom());
        }

        if ($clientRecherche->getPrenom()) {
            $qb->andWhere('c.prenom LIKE :prenom')
                ->setParameter('prenom', $clientRecherche->getPrenom());
        }

        return $qb->getQuery();
        // $query = $qb->getQuery();
        // return $query->execute();
    }

    /**
    * @return Query
    */
   public function findAllOrderByLibelle(): Query
   {
       $entityManager = $this->getEntityManager();
       $query = $entityManager->createQuery(
           'SELECT c
           FROM App\Entity\Client c
           ORDER BY c.nom ASC'
       );

       // retourne un tableau d'objets de type Client
       return $query;
   }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Client $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Client $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Client[] Returns an array of Client objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Client
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
