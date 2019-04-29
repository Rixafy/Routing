<?php

declare(strict_types=1);

namespace Rixafy\Routing\Route;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;
use Rixafy\Routing\Route\Exception\RouteNotFoundException;

class RouteRepository
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

	/**
	 * @return EntityRepository|ObjectRepository
	 */
    public function getRepository()
    {
        return $this->entityManager->getRepository(Route::class);
    }

    /**
     * @throws RouteNotFoundException
     */
    public function get(UuidInterface $id, UuidInterface $routeGroupId): Route
    {
        /** @var Route $route */
		$route = $this->getRepository()->findOneBy([
			'id' => $id,
			'group' => $routeGroupId
		]);

        if ($route === null) {
            throw RouteNotFoundException::byId($id);
        }

        return $route;
    }

    /**
     * @throws RouteNotFoundException
     */
    public function getByName(string $name, UuidInterface $routeGroupId): Route
    {
        /** @var Route $route */
        $route = $this->getRepository()->findOneBy([
            'name' => $name,
			'group' => $routeGroupId
        ]);

        if ($route === null) {
            throw RouteNotFoundException::byName($name);
        }

        return $route;
    }

    /**
     * @throws RouteNotFoundException
     */
    public function getByTarget(UuidInterface $targetId): Route
    {
        /** @var Route $route */
        $route = $this->getRepository()->findOneBy([
            'target' => $targetId
        ]);

        if ($route === null) {
            throw RouteNotFoundException::byTarget($targetId);
        }

        return $route;
    }

    public function getQueryBuilderForAll(UuidInterface $routeSiteId): QueryBuilder
    {
        return $this->getRepository()->createQueryBuilder('e')
			->where('e.site = :routeSite')->setParameter('routeSite', $routeSiteId->getBytes());
    }

    public function getQueryBuilderForAllInGroup(UuidInterface $routeGroupId, UuidInterface $routeSiteId): QueryBuilder
    {
        return $this->getQueryBuilderForAll($routeSiteId)
			->andWhere('e.group = :routeGroup')->setParameter('routeGroup', $routeGroupId->getBytes());
    }

    public function getNameCounter(string $routeName, UuidInterface $siteId, UuidInterface $groupId = null): int
	{
		if ($groupId !== null) {
			$result = $this->getQueryBuilderForAllInGroup($groupId, $siteId)
				->select('MAX(e.group_name_counter) as result_count')
				->andWhere('e.name = :name')->setParameter('name', $routeName);
		} else {
			$result = $this->getQueryBuilderForAll($siteId)
				->select('MAX(e.group_name_counter) as result_count')
				->andWhere('e.name = :name')->setParameter('name', $routeName);
		}

		$result = $result->getQuery()
			->setMaxResults(1)
			->getSingleScalarResult();

		return (int) $result['result_count'];
	}
}
