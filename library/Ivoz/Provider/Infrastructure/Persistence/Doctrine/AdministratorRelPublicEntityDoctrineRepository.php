<?php

namespace Ivoz\Provider\Infrastructure\Persistence\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Ivoz\Provider\Domain\Model\AdministratorRelPublicEntity\AdministratorRelPublicEntity;
use Ivoz\Provider\Domain\Model\AdministratorRelPublicEntity\AdministratorRelPublicEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * AdministratorRelPublicEntityDoctrineRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AdministratorRelPublicEntityDoctrineRepository extends ServiceEntityRepository implements AdministratorRelPublicEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AdministratorRelPublicEntity::class);
    }
}
