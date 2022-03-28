<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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

    // Affichage par Pagination
    public function findByPagination($currentPage, $nbResult) {
        return $this->createQueryBuilder('p')
        ->setMaxResults($nbResult)
        ->setFirstResult(($currentPage * $nbResult) - $nbResult )
        ->getQuery()->getResult();
    }

    // Filtrer par Catégorie / sousCategorie
    public function categorieSousCategorie($categorie, $sousCategorie) {
        return $this->createQueryBuilder('produit')
        ->andWhere('produit.categorie = :categorie AND produit.sousCategorie = :sousCategorie')
        ->setParameter('categorie', $categorie)
        ->setParameter('sousCategorie', $sousCategorie)
        ->getQuery()
        ->execute();

        // SELECT * FROM produit WHERE categorie_id = 1 AND sous_categorie_id = 1
    }

    // /**
    //  * @return Produit[] Returns an array of Produit objects
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
    public function findOneBySomeField($value): ?Produit
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
