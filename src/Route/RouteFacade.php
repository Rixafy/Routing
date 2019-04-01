<?php

declare(strict_types=1);

namespace Rixafy\Routing\Route;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;

class RouteFacade
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var RouteRepository */
    private $routeRepository;

    /** @var RouteFactory */
    private $routeFactory;

    /**
     * RouteFacade constructor.
     * @param EntityManagerInterface $entityManager
     * @param RouteRepository $routeRepository
     * @param RouteFactory $routeFactory
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        RouteRepository $routeRepository,
        RouteFactory $routeFactory
    ) {
        $this->entityManager = $entityManager;
        $this->routeRepository = $routeRepository;
        $this->routeFactory = $routeFactory;
    }

    /**
     * @param RouteData $routeData
     * @return Route
     */
    public function create(RouteData $routeData): Route
    {
        $route = $this->routeFactory->create($routeData);

        $this->entityManager->persist($route);
        $this->entityManager->flush();

        return $route;
    }

    /**
     * @param UuidInterface $id
     * @param RouteData $routeData
     * @return Route
     * @throws RouteNotFoundException
     */
    public function edit(UuidInterface $id, RouteData $routeData): Route
    {
        $route = $this->routeRepository->get($id);
        $route->edit($routeData);

        $this->entityManager->flush();

        return $route;
    }

    /**
     * @param UuidInterface $id
     * @throws RouteNotFoundException
     */
    public function remove(UuidInterface $id): void
    {
        $route = $this->routeRepository->get($id);
        $this->entityManager->remove($route);

        $this->entityManager->flush();
    }

    /**
     * @param UuidInterface $id
     * @return Route
     * @throws RouteNotFoundException
     */
    public function get(UuidInterface $id): Route
    {
        return $this->routeRepository->get($id);
    }
}