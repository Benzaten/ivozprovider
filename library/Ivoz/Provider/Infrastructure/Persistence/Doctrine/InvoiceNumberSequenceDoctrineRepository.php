<?php

namespace Ivoz\Provider\Infrastructure\Persistence\Doctrine;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Ivoz\Provider\Domain\Model\InvoiceNumberSequence\InvoiceNumberSequence;
use Ivoz\Provider\Domain\Model\InvoiceNumberSequence\InvoiceNumberSequenceRepository;

/**
 * InvoiceNumberSequenceDoctrineRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class InvoiceNumberSequenceDoctrineRepository extends ServiceEntityRepository implements InvoiceNumberSequenceRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvoiceNumberSequence::class);
    }
}
