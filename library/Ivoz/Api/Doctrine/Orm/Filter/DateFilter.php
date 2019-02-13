<?php

namespace Ivoz\Api\Doctrine\Orm\Filter;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter as BaseDateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Core\Metadata\Resource\Factory\ResourceMetadataFactoryInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Ivoz\Core\Domain\Model\Helper\DateTimeHelper;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * @inheritdoc
 */
class DateFilter extends BaseDateFilter
{
    const SERVICE_NAME = 'ivoz.api.filter.date';

    use FilterTrait;

    protected $tokenStorage;

    public function __construct(
        ManagerRegistry $managerRegistry,
        $requestStack = null,
        LoggerInterface $logger = null,
        array $properties = null,
        ResourceMetadataFactoryInterface $resourceMetadataFactory,
        TokenStorage $tokenStorage
    ) {
        $this->resourceMetadataFactory = $resourceMetadataFactory;
        $this->tokenStorage = $tokenStorage;
        return parent::__construct($managerRegistry, $requestStack, $logger, $properties);
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription(string $resourceClass): array
    {
        $metadata = $this->resourceMetadataFactory->create($resourceClass);
        $this->overrideProperties($metadata->getAttributes());

        return $this->filterDescription(
            parent::getDescription($resourceClass)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function apply(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        string $operationName = null,
        array $context = []
    ) {
        $metadata = $this->resourceMetadataFactory->create($resourceClass);
        $this->overrideProperties($metadata->getAttributes());

        return parent::apply($queryBuilder, $queryNameGenerator, $resourceClass, $operationName, $context);
    }

    /**
     * @inherited
     * @see BaseDateFilter::addWhere
     */
    protected function addWhere(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $alias, string $field, string $operator, string $value, string $nullManagement = null, $type = null)
    {
        $value = DateTimeHelper::stringToUtc(
            $value,
            'Y-m-d H:i:s',
            $this->getUserDateTimeZone()
        );

        return parent::addWhere(...func_get_args());
    }

    /**
     * @return \DateTimeZone
     */
    private function getUserDateTimeZone()
    {
        $token = $this->tokenStorage->getToken();

        $timeZone = $token
            ->getUser()
            ->getTimezone();

        $tz = $timeZone
            ? $timeZone->getTz()
            : 'UTC';

        return new \DateTimeZone($tz);
    }
}
