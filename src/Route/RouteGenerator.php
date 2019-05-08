<?php

declare(strict_types=1);

namespace Rixafy\Routing\Route;

use Doctrine\ORM\EntityManagerInterface;

class RouteGenerator
{
	/** @var RouteRepository */
	private $routeRepository;

	/** @var RouteFactory */
	private $routeFactory;

	/** @var EntityManagerInterface */
	private $entityManager;

	public function __construct(
		RouteRepository $routeRepository,
		EntityManagerInterface $entityManager,
		RouteFactory $routeFactory
	) {
		$this->routeRepository = $routeRepository;
		$this->entityManager = $entityManager;
		$this->routeFactory = $routeFactory;
	}
}
