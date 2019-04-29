<?php

declare(strict_types=1);

namespace Rixafy\Routing\Route\Site;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;
use Rixafy\Routing\Route\Site\Exception\RouteSiteNotFoundException;

class RouteSiteFacade
{
	/** @var EntityManagerInterface */
	private $entityManager;

	/** @var RouteSiteRepository */
	private $routeSiteRepository;

	/** @var RouteSiteFactory */
	private $routeSiteFactory;

	public function __construct(
		EntityManagerInterface $entityManager,
		RouteSiteRepository $routeSiteRepository,
		RouteSiteFactory $routeSiteFactory
	) {
		$this->entityManager = $entityManager;
		$this->routeSiteRepository = $routeSiteRepository;
		$this->routeSiteFactory = $routeSiteFactory;
	}

	public function create(RouteSiteData $routeSiteData): RouteSite
	{
		$routeSite = $this->routeSiteFactory->create($routeSiteData);

		$this->entityManager->persist($routeSite);
		$this->entityManager->flush();

		return $routeSite;
	}

	/**
	 * @throws RouteSiteNotFoundException
	 */
	public function edit(UuidInterface $id, RouteSiteData $routeSiteData): RouteSite
	{
		$routeSite = $this->routeSiteRepository->get($id);
		$routeSite->edit($routeSiteData);

		$this->entityManager->flush();

		return $routeSite;
	}

	/**
	 * @throws RouteSiteNotFoundException
	 */
	public function remove(UuidInterface $id): void
	{
		$routeSite = $this->routeSiteRepository->get($id);
		$this->entityManager->remove($routeSite);

		$this->entityManager->flush();
	}

	/**
	 * @throws RouteSiteNotFoundException
	 */
	public function get(UuidInterface $id): RouteSite
	{
		return $this->routeSiteRepository->get($id);
	}

	/**
	 * @throws RouteSiteNotFoundException
	 */
	public function getByDomainHost(string $domainHost): RouteSite
	{
		return $this->routeSiteRepository->getByDomainHost($domainHost);
	}
}
