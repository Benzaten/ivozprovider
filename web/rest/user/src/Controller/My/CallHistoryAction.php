<?php

namespace Controller\My;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryResultCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGenerator;
use ApiPlatform\Core\Exception\ResourceClassNotFoundException;
use ApiPlatform\Core\Util\RequestParser;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Ivoz\Api\Doctrine\Orm\Extension\CollectionExtensionList;
use Ivoz\Kam\Domain\Model\UsersCdr\UsersCdrRepository;
use Ivoz\Kam\Domain\Model\UsersCdr\UsersCdr;
use Ivoz\Provider\Domain\Model\User\UserInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Paginator;

class CallHistoryAction
{
    protected $request;

    public function __construct(
        private TokenStorageInterface $tokenStorage,
        private UsersCdrRepository $usersCdrRepository,
        private CollectionExtensionList $collectionExtensions,
        RequestStack $requestStack
    ) {
        $this->request = $requestStack->getCurrentRequest();
    }

    public function __invoke()
    {
        $token =  $this->tokenStorage->getToken();

        if (!$token || !$token->getUser()) {
            throw new ResourceClassNotFoundException('User not found');
        }

        /** @var UserInterface $user */
        $user = $token->getUser();
        /** @phpstan-ignore-next-line  */
        $qb = $this
            ->usersCdrRepository
            ->createQueryBuilder('o');

        $qb->where(
            $qb->expr()->eq(
                'o.user',
                $user->getId()
            )
        );

        $response = $this->applyCollectionExtensions(
            $qb,
            UsersCdr::class,
            'my_call_history'
        );

        $calls = $response instanceof Paginator
            ? $response->getIterator()
            : new \ArrayIterator($response);

        $this->setUserTimezone($user, $calls);

        return $response;
    }

    protected function setUserTimezone(UserInterface $user, \Traversable $calls): void
    {
        $userTimeZone = $user->getTimezone();
        $timezone = new \DateTimeZone(
            $userTimeZone->getTz()
        );

        foreach ($calls as $call) {
            $call
                ->getStartTime()
                ->setTimezone($timezone);

            $call
                ->getEndTime()
                ->setTimezone($timezone);
        }
    }

    /**
     * @param QueryBuilder $qb
     * @param string $entityClass
     * @param string $operationName
     * @return Paginator | array | iterable
     */
    protected function applyCollectionExtensions(QueryBuilder $qb, string $entityClass, string $operationName)
    {
        $context = [];
        $queryString = RequestParser::getQueryString($this->request);
        $context['filters'] = $queryString ? RequestParser::parseRequestParams($queryString) : null;

        $queryNameGenerator = new QueryNameGenerator();
        foreach ($this->collectionExtensions->get() as $extension) {
            /** @phpstan-ignore-next-line  */
            $extension->applyToCollection(
                $qb,
                $queryNameGenerator,
                $entityClass,
                $operationName,
                $context
            );

            $returnResults =
                $extension instanceof QueryResultCollectionExtensionInterface
                && $extension->supportsResult($entityClass, $operationName);

            if ($returnResults) {
                /** @var QueryResultCollectionExtensionInterface $extension */
                return $extension->getResult($qb);
            }
        }

        return $qb->getQuery()->getResult();
    }
}
