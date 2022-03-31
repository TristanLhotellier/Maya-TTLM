<?php

namespace App\Repository;

use App\Entity\Produit;
use App\Entity\Categorie;
use App\Entity\ProduitRecherche;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;


/**
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

   /**
     * @return Query
     */
    public function findAllByCriteria(ProduitRecherche $produitRecherche, ?Categorie $categorie): Query
    {
        // le "p" est un alias utilisé dans la requête
        $qb = $this->createQueryBuilder('p')
            ->orderBy('p.libelle', 'ASC');

            if ($categorie != null) {
                $qb->andWhere('p.categorie = :idCategorie')
                    ->setParameter('idCategorie', $categorie->getId());
            }

        if ($produitRecherche->getLibelle()) {
            $qb->andWhere('p.libelle LIKE :libelle')
                ->setParameter('libelle', $produitRecherche->getLibelle().'%');
        }

        if ($produitRecherche->getPrixMini()) {
            $qb->andWhere('p.prix >= :prixMini')
                ->setParameter('prixMini', $produitRecherche->getPrixMini());
        }

        if ($produitRecherche->getPrixMaxi()) {
            $qb->andWhere('p.prix < :prixMaxi')
                ->setParameter('prixMaxi', $produitRecherche->getPrixMaxi());
        }

        return $qb->getQuery();
    }

    /**
    * @return Query
    */
   public function findAllOrderByLibelle(?Categorie $categorie): Query
   {
       $entityManager = $this->getEntityManager();
        if ($categorie == null) {
            $query = $entityManager->createQuery(
                'SELECT p
                FROM App\Entity\Produit p
                ORDER BY p.libelle ASC'
            );
        } else {
        $query = $entityManager->createQuery(
           'SELECT p
           FROM App\Entity\Produit p
           WHERE p.categorie = :idCategorie
           ORDER BY p.libelle ASC'
       )->setParameter('idCategorie', $categorie->getId());
    }
       // retourne un tableau d'objets de type Produit
       return $query;
   }

}
