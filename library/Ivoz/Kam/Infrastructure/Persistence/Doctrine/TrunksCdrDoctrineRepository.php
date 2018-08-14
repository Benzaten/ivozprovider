<?php

namespace Ivoz\Kam\Infrastructure\Persistence\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Ivoz\Kam\Domain\Model\TrunksCdr\TrunksCdrRepository;
use Ivoz\Kam\Domain\Model\TrunksCdr\TrunksCdr;
use Ivoz\Core\Infrastructure\Persistence\Doctrine\Model\Helper\CriteriaHelper;
use Ivoz\Kam\Infrastructure\Persistence\Doctrine\Traits\GetGeneratorByConditionsTrait;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * TrunksCdrDoctrineRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TrunksCdrDoctrineRepository extends ServiceEntityRepository implements TrunksCdrRepository
{
    use GetGeneratorByConditionsTrait;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TrunksCdr::class);
    }

    /**
     * @param int $invoiceId
     */
    public function resetInvoiceId(int $invoiceId)
    {
        $qb = $this
            ->createQueryBuilder('self')
            ->update($this->_entityName, 'self')
            ->set('self.invoice', ':nullValue')
            ->setParameter(':nullValue', null)
            ->where('self.invoice = :invoiceId')
            ->setParameter(':invoiceId', $invoiceId);

        return $qb->getQuery()->execute();
    }

    /**
     * @param array $conditions
     * @param int $invoiceId
     * @return mixed
     */
    public function setInvoiceId(array $conditions, int $invoiceId)
    {
        $qb = $this
            ->createQueryBuilder('self')
            ->update($this->_entityName, 'self')
            ->set('self.invoice', ':invoiceId')
            ->setParameter(':invoiceId', $invoiceId)
            ->addCriteria(
                CriteriaHelper::fromArray($conditions)
            );

        return $qb->getQuery()->execute();
    }

    /**
     * @param int $companyId
     * @param int $brandId
     * @param string $startTime
     * @return mixed
     */
    public function countUntarificattedCallsBeforeDate(int $companyId, int $brandId, string $startTime)
    {
        $qb = $this->createQueryBuilder('self');
        $conditions = [
            ['company', 'eq', $companyId],
            ['brand', 'eq', $brandId],
            ['startTime', 'lt', $startTime],
            ['carrier', 'neq', null],
            ['carrier', 'neq', ''],
            ['price', 'isNull'],
            ['cgrid', 'isNull']
        ];

        $qb
            ->select('count(self)')
            ->addCriteria(
                CriteriaHelper::fromArray($conditions)
            );

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @param int $companyId
     * @param int $brandId
     * @param string $startTime
     * @return mixed
     */
    public function countUntarificattedCallsInRange(int $companyId, int $brandId, string $startTime, string $endTime)
    {
        $qb = $this->createQueryBuilder('self');

        $conditions = [
            ['company', 'eq', $companyId],
            ['brand', 'eq', $brandId],
            ['startTime', 'gt', $startTime],
            ['endTime', 'lt', $endTime],
            ['carrier', 'neq', null],
            ['carrier', 'neq', ''],
            ['price', 'isNull'],
            ['cgrid', 'isNull']
        ];

        $qb
            ->select('count(self)')
            ->addCriteria(
                CriteriaHelper::fromArray($conditions)
            );

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @param array $pks
     * @return bool
     */
    public function areRetarificable(array $pks)
    {
        $qb = $this->createQueryBuilder('self');

        $conditions = [
            ['id', 'in', $pks],
            'or' => [
                ['invoice', 'isNotNull'],
                ['carrier', 'isNull'],
                ['carrier', 'eq', '']
            ]
        ];

        $qb
            ->select('count(self)')
            ->addCriteria(
                CriteriaHelper::fromArray($conditions)
            );

        $elementNumber = (int) $qb->getQuery()->getSingleScalarResult();

        return $elementNumber === 0;
    }

    /**
     * @param array $ids
     * @return array
     */
    public function idsToCgrid(array $ids)
    {
        $qb = $this->createQueryBuilder('self');

        $conditions = [
            ['id', 'in', $ids]
        ];

        $qb
            ->select('self.cgrid')
            ->addCriteria(
                CriteriaHelper::fromArray($conditions)
            );

        $result = $qb
            ->getQuery()
            ->getScalarResult();

        $cgrids = array_map(
            function ($item) {
                return $item['cgrid'];
            },
            $result
        );

        if (count($ids) !== count($cgrids)) {
            throw new \DomainException('Some id were not found');
        }

        return $cgrids;
    }

    /**
     * This method expects results to be marked as metered as soon as they're used:
     * a.k.a it does not apply any query offset, just a limit
     *
     * @inheritdoc
     * @see TrunksCdrRepository::getUnmeteredCallsGeneratorWithoutOffset
     */
    public function getUnmeteredCallsGeneratorWithoutOffset(int $batchSize, array $order = null)
    {
        $dateFrom = new \DateTime(
            '10 seconds ago',
            new \DateTimeZone('UTC')
        );

        /**
         * @var \Doctrine\ORM\EntityRepository $this
         */
        $qb = $this->createQueryBuilder('self');
        $qb->addCriteria(CriteriaHelper::fromArray([
            ['metered', 'eq', '0'],
            ['direction', 'eq', 'outbound'],
            ['endTime', 'lte', $dateFrom->format('Y-m-d H:i:s')],
        ]));
        $qb->setMaxResults($batchSize);

        if ($order) {
            $qb->orderBy(...$order);
        }

        $currentPage = 1;
        $continue =  true;
        while ($continue) {

            $query = $qb->getQuery();
            $results = $query->getResult();
            $continue = count($results) === $batchSize;
            $currentPage++;

            yield $results;
        }
    }
}
