<?php

declare(strict_types=1);

namespace Rixafy\Routing\Route;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;
use Rixafy\Routing\Route\Exception\RouteNotFoundException;

class RouteFacade
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var RouteRepository */
    private $routeRepository;

    /** @var RouteFactory */
    private $routeFactory;

    public function __construct(
        EntityManagerInterface $entityManager,
        RouteRepository $routeRepository,
        RouteFactory $routeFactory
    ) {
        $this->entityManager = $entityManager;
        $this->routeRepository = $routeRepository;
        $this->routeFactory = $routeFactory;
    }

    public function create(RouteData $routeData): Route
    {
        $route = $this->routeFactory->create($routeData);

        $this->entityManager->persist($route);
        $this->entityManager->flush();

        return $route;
    }

    /**
     * @throws RouteNotFoundException
     */
    public function edit(UuidInterface $id, UuidInterface $routeGroupId, RouteData $routeData): Route
    {
        $route = $this->routeRepository->get($id, $routeGroupId);
        $route->edit($routeData);

        $this->entityManager->flush();

        return $route;
    }

    /**
     * @throws RouteNotFoundException
     */
    public function remove(UuidInterface $id, UuidInterface $routeGroupId): void
    {
        $route = $this->routeRepository->get($id, $routeGroupId);
        $this->entityManager->remove($route);

        $this->entityManager->flush();
    }

    /**
     * @throws RouteNotFoundException
     */
    public function get(UuidInterface $id, UuidInterface $routeGroupId): Route
    {
        return $this->routeRepository->get($id, $routeGroupId);
    }

    /**
     * @throws RouteNotFoundException
     */
    public function getByName(string $name, UuidInterface $routeGroupId): Route
    {
        return $this->routeRepository->getByName($name, $routeGroupId);
    }

    /**
     * @throws RouteNotFoundException
     */
    public function getByTarget(UuidInterface $targetId, UuidInterface $routeGroupId): Route
    {
        return $this->routeRepository->getByTarget($targetId, $routeGroupId);
    }

    /**
	 * @return Route[]
     */
    public function getAll(UuidInterface $routeGroupId): array
    {
        return $this->routeRepository->getAll($routeGroupId);
    }
}
