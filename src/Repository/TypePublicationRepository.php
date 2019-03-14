<?php

namespace App\Repository;

use App\Entity\TypePublication;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TypePublication|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypePublication|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypePublication[]    findAll()
 * @method TypePublication[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypePublicationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TypePublication::class);
    }

    // /**
    //  * @return TypePublication[] Returns an array of TypePublication objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TypePublication
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    
    public function findByUserId($value): TypePublication
    {
        $query = 
        'SELECT p.id,p.content,p.filepath,p.create_date as createDate,p.valid,p.name,p.author,tp.name as categorie '
        .'from publication p join type_publication tp  '
        .'where  p.id_type_id=tp.id';

        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    
    
}
