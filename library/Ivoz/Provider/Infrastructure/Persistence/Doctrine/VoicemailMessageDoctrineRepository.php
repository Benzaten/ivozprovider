<?php

namespace Ivoz\Provider\Infrastructure\Persistence\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Ivoz\Provider\Domain\Model\VoicemailMessage\VoicemailMessage;
use Ivoz\Provider\Domain\Model\VoicemailMessage\VoicemailMessageInterface;
use Ivoz\Provider\Domain\Model\VoicemailMessage\VoicemailMessageRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * VoicemailMessageDoctrineRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class VoicemailMessageDoctrineRepository extends ServiceEntityRepository implements VoicemailMessageRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VoicemailMessage::class);
    }
}
