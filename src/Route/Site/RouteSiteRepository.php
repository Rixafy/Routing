<?php

declare(strict_types=1);

namespace Rixafy\Routing\Route\Site;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;
use Rixafy\Routing\Route\Site\Exception\RouteSiteNotFoundException;

class RouteSiteRepository
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
		return $this->entityManager->getRepository(RouteSite::class);
	}

	/**
	 * @throws RouteSiteNotFoundException
	 */
	public function get(UuidInterface $id): RouteSite
	{
		/** @var RouteSite $routeSite */
		$routeSite = $this->getRepository()->findOneBy([
			'id' => $id
		]);

		if ($routeSite === null) {
			throw RouteSiteNotFoundException::byId($id);
		}

		return $routeSite;
	}

	/**
	 * @throws RouteSiteNotFoundException
	 */
	public function getByDomainHost(string $domainHost): RouteSite
	{
		/** @var RouteSite $routeSite */
		$routeSite = $this->getRepository()->findOneBy([
			'domain_host' => $domainHost
		]);

		if ($routeSite === null) {
			throw RouteSiteNotFoundException::byDomainHost($domainHost);
		}

		return $routeSite;
	}

	public function getAll(): array
	{
		return $this->getQueryBuilderForAll()->getQuery()->getResult();
	}

	public function getQueryBuilderForAll(): QueryBuilder
	{
		return $this->getRepository()->createQueryBuilder('e');
	}
}
