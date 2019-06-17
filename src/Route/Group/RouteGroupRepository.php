<?php

declare(strict_types=1);

namespace Rixafy\Routing\Route\Group;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;
use Rixafy\Routing\Route\Group\Exception\RouteGroupNotFoundException;

class RouteGroupRepository
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
		return $this->entityManager->getRepository(RouteGroup::class);
	}

	/**
	 * @throws RouteGroupNotFoundException
	 */
	public function get(UuidInterface $id): RouteGroup
	{
		/** @var RouteGroup $routeGroup */
		$routeGroup = $this->getRepository()->findOneBy([
			'id' => $id
		]);

		if ($routeGroup === null) {
			throw RouteGroupNotFoundException::byId($id);
		}

		return $routeGroup;
	}

	/**
	 * @throws RouteGroupNotFoundException
	 */
	public function getByName(string $name, UuidInterface $siteId): RouteGroup
	{
		/** @var RouteGroup $routeGroup */
		$routeGroup = $this->getRepository()->findOneBy([
			'name' => $name,
			'site' => $siteId
		]);

		if ($routeGroup === null) {
			throw RouteGroupNotFoundException::byNameAndSiteId($name, $siteId);
		}

		return $routeGroup;
	}

	public function getQueryBuilderForAll(UuidInterface $routeSiteId): QueryBuilder
	{
		return $this->getRepository()->createQueryBuilder('e')
			->where('e.site = :routeSite')->setParameter('routeSite', $routeSiteId->getBytes());
	}
}
