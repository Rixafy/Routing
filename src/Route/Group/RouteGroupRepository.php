<?php

declare(strict_types=1);

namespace Rixafy\Routing\Route\Group;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;

class RouteGroupRepository
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getRepository()
    {
        return $this->entityManager->getRepository(RouteGroup::class);
    }

    /**
     * @param UuidInterface $id
     * @return RouteGroup
     * @throws RouteGroupNotFoundException
     */
    public function get(UuidInterface $id): RouteGroup
    {
        /** @var RouteGroup $routeGroup */
        $routeGroup = $this->getRepository()->findOneBy([
            'id' => $id
        ]);

        if ($routeGroup === null) {
            throw new RouteGroupNotFoundException('RouteGroup with id "' . $id . '" not found.');
        }

        return $routeGroup;
    }

    /**
     * @param string $name
     * @return RouteGroup
     * @throws RouteGroupNotFoundException
     */
    public function getByName(string $name): RouteGroup
    {
        /** @var RouteGroup $routeGroup */
        $routeGroup = $this->getRepository()->findOneBy([
            'name' => $name
        ]);

        if ($routeGroup === null) {
            throw new RouteGroupNotFoundException('RouteGroup with name "' . $name . '" not found.');
        }

        return $routeGroup;
    }

    public function getAll(): array
    {
        return $this->getQueryBuilderForAll()->getQuery()->getResult();
    }

    public function getQueryBuilderForAll(): QueryBuilder
    {
        return $this->getRepository()->createQueryBuilder('r');
    }
}