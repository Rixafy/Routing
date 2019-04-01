<?php

declare(strict_types=1);

namespace Rixafy\Routing\Route\Group;

use Doctrine\ORM\Mapping as ORM;
use Rixafy\DoctrineTraits\UniqueTrait;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="route_group")
 */
class RouteGroup
{
    use UniqueTrait;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    protected $name;

    public function __construct(RouteGroupData $routeGroupData)
    {
        $this->edit($routeGroupData);
    }

    public function edit(RouteGroupData $routeGroupData): void
    {
        $this->name = $routeGroupData->name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}