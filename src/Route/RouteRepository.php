<?php

declare(strict_types=1);

namespace Rixafy\Routing\Route;

use Doctrine\ORM\EntityManagerInterface;
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

    public function getRepository()
    {
        return $this->entityManager->getRepository(Route::class);
    }

    /**
     * @throws RouteNotFoundException
     */
    public function get(UuidInterface $id): Route
    {
        /** @var Route $route */
        $route = $this->getRepository()->findOneBy([
            'id' => $id
        ]);

        if ($route === null) {
            throw RouteNotFoundException::byId($id);
        }

        return $route;
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
