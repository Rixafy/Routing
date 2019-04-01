<?php

declare(strict_types=1);

namespace Rixafy\Routing\Route;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Rixafy\Doctrination\Language\Language;
use Rixafy\DoctrineTraits\ActiveTrait;
use Rixafy\DoctrineTraits\DateTimeTrait;
use Rixafy\DoctrineTraits\UniqueTrait;

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

    public function __construct(RouteData $routeData)
    {
        $this->name = $routeData->name;
        $this->controller = $routeData->controller;
        $this->target = $routeData->target;
        $this->parameters = $routeData->parameters;
        $this->language = $routeData->language;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getController(): string
    {
        return $this->controller;
    }

    /**
     * @return UuidInterface
     */
    public function getTarget(): UuidInterface
    {
        return $this->target;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @return Language
     */
    public function getLanguage(): Language
    {
        return $this->language;
    }
}