<?php

declare(strict_types=1);

namespace Rixafy\Routing\Route\Group;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;

class RouteGroupFacade
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var RouteGroupRepository */
    private $routeGroupRepository;

    /** @var RouteGroupFactory */
    private $routeGroupFactory;

    /**
     * RouteGroupFacade constructor.
     * @param EntityManagerInterface $entityManager
     * @param RouteGroupRepository $routeGroupRepository
     * @param RouteGroupFactory $routeGroupFactory
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        RouteGroupRepository $routeGroupRepository,
        RouteGroupFactory $routeGroupFactory
    ) {
        $this->entityManager = $entityManager;
        $this->routeGroupRepository = $routeGroupRepository;
        $this->routeGroupFactory = $routeGroupFactory;
    }

    /**
     * @param RouteGroupData $routeGroupData
     * @return RouteGroup
     */
    public function create(RouteGroupData $routeGroupData): RouteGroup
    {
        $routeGroup = $this->routeGroupFactory->create($routeGroupData);

        $this->entityManager->persist($routeGroup);
        $this->entityManager->flush();

        return $routeGroup;
    }

    /**
     * @param UuidInterface $id
     * @param RouteGroupData $routeGroupData
     * @return RouteGroup
     * @throws RouteGroupNotFoundException
     */
    public function edit(UuidInterface $id, RouteGroupData $routeGroupData): RouteGroup
    {
        $routeGroup = $this->routeGroupRepository->get($id);
        $routeGroup->edit($routeGroupData);

        $this->entityManager->flush();

        return $routeGroup;
    }

    /**
     * @param UuidInterface $id
     * @throws RouteGroupNotFoundException
     */
    public function remove(UuidInterface $id): void
    {
        $routeGroup = $this->routeGroupRepository->get($id);
        $this->entityManager->remove($routeGroup);

        $this->entityManager->flush();
    }

    /**
     * @param UuidInterface $id
     * @return RouteGroup
     * @throws RouteGroupNotFoundException
     */
    public function get(UuidInterface $id): RouteGroup
    {
        return $this->routeGroupRepository->get($id);
    }

    /**
     * @param string $name
     * @return RouteGroup
     * @throws RouteGroupNotFoundException
     */
    public function getByName(string $name): RouteGroup
    {
        return $this->routeGroupRepository->getByName($name);
    }
}