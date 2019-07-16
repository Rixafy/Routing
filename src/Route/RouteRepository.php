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
	public function getByName(string $name): Route
	{
		/** @var Route $route */
		$route = $this->getRepository()->findOneBy([
			'name' => $name
		]);

		if ($route === null) {
			throw RouteNotFoundException::byName($name);
		}

		return $route;
	}

	/**
	 * @throws RouteNotFoundException
	 */
	public function getByNameAndRouteGroup(string $name, UuidInterface $routeGroupId): Route
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
	public function getByNameAndRouteSite(string $name, UuidInterface $routeSiteId): Route
	{
		/** @var Route $route */
		$route = $this->getRepository()->findOneBy([
			'name' => $name,
			'site' => $routeSiteId
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
}
