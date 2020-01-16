<?php

namespace Ivoz\Provider\Infrastructure\Persistence\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Ivoz\Provider\Domain\Model\PublicEntity\PublicEntity;
use Ivoz\Provider\Domain\Model\PublicEntity\PublicEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * PublicEntityDoctrineRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PublicEntityDoctrineRepository extends ServiceEntityRepository implements PublicEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PublicEntity::class);
    }
}
