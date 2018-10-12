<?php
namespace Ejecutivo\Repository;

use Doctrine\ORM\EntityRepository;
use DBAL\Entity\Ejecutivo;

/**
 * This is the custom repository class for Ejecutivo entity.
 */
class EjecutivoRepository extends EntityRepository
{
    /**
     * Retrieves all users in descending dateCreated order.
     * @return Query
     */
    public function findAllUsers()
    {
        $entityManager = $this->getEntityManager();
        
        $queryBuilder = $entityManager->createQueryBuilder();
        
        $queryBuilder->select('u')
            ->from(Ejecutivo::class, 'u')
            ->orderBy('u.dateCreated', 'DESC');
        
        return $queryBuilder->getQuery();
    }
}