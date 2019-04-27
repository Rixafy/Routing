<?php

declare(strict_types=1);

namespace Rixafy\Routing\Route;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;
use Rixafy\Routing\Route\Group\RouteGroup;

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
			'routeGroup' => $routeGroupId
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
			'routeGroup' => $routeGroupId
        ]);

        if ($route === null) {
            throw RouteNotFoundException::byName($name);
        }

        return $route;
    }

	/**
	 * @return Route[]
	 */
    public function getAll(UuidInterface $routeGroupId): array
    {
        return $this->getQueryBuilderForAll($routeGroupId)->getQuery()->getResult();
    }

    public function getQueryBuilderForAll(UuidInterface $routeGroupId): QueryBuilder
    {
        return $this->getRepository()->createQueryBuilder('e')
			->where('e.route_group = :routeGroup')->setParameter('routeGroup', $routeGroupId->getBytes());
    }
}
