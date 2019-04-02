<?php

declare(strict_types=1);

namespace Rixafy\Routing\Route;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Rixafy\Doctrination\Language\Language;
use Rixafy\DoctrineTraits\ActiveTrait;
use Rixafy\DoctrineTraits\DateTimeTrait;
use Rixafy\DoctrineTraits\UniqueTrait;
use Rixafy\Routing\Route\Group\RouteGroup;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="route")
 */
class Route
{
    use UniqueTrait;
    use ActiveTrait;
    use DateTimeTrait;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=31)
     * @var string
     */
    protected $controller;

    /**
     * @ORM\Column(type="uuid_binary", unique=true)
     * @var UuidInterface
     */
    protected $target;

    /**
     * @ORM\Column(type="json", unique=true)
     * @var array
     */
    protected $parameters;

    /**
     * @ORM\ManyToOne(targetEntity="\Rixafy\Doctrination\Language\Language")
     * @var Language
     */
    private $language;

    /**
     * @ORM\ManyToOne(targetEntity="\Rixafy\Routing\Route\Group\RouteGroup")
     * @var RouteGroup
     */
    private $group;

    public function __construct(RouteData $routeData)
    {
        $this->controller = $routeData->controller;
        $this->target = $routeData->target;
        $this->parameters = $routeData->parameters;
        $this->language = $routeData->language;
        $this->group = $routeData->group;
        $this->edit($routeData);
    }

    public function edit(RouteData $routeData): void
    {
        $this->name = $routeData->name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getController(): string
    {
        return $this->controller;
    }

    public function getTarget(): UuidInterface
    {
        return $this->target;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function getLanguage(): Language
    {
        return $this->language;
    }

    public function getGroup(): RouteGroup
    {
        return $this->group;
    }
}