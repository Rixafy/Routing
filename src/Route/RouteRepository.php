<?php

declare(strict_types=1);

namespace Rixafy\Routing\Route;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;

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
    public function getByTarget(UuidInterface $targetId, UuidInterface $routeGroupId): Route
    {
        /** @var Route $route */
        $route = $this->getRepository()->findOneBy([
            'target' => $targetId,
			'group' => $routeGroupId
        ]);

        if ($route === null) {
            throw RouteNotFoundException::byTarget($targetId);
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
			->where('e.group = :routeGroup')->setParameter('routeGroup', $routeGroupId->getBytes());
    }
}
