<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    public function save(Produit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Produit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Produit[] Returns an array of Produit objects
     */
    public function findByStock(int $minStock = 0): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.stock > :minStock')
            ->setParameter('minStock', $minStock)
            ->orderBy('p.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Produit[] Returns an array of Produit objects
     */
    public function findByPrixRange(float $minPrix, float $maxPrix): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.prix >= :minPrix')
            ->andWhere('p.prix <= :maxPrix')
            ->setParameter('minPrix', $minPrix)
            ->setParameter('maxPrix', $maxPrix)
            ->orderBy('p.prix', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Produit[] Returns an array of Produit objects
     */
    public function findBySearch(string $search): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.nom LIKE :search')
            ->orWhere('p.description LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->orderBy('p.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }
} 