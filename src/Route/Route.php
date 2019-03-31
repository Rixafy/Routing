<?php

declare(strict_types=1);

namespace Rixafy\Routing\Route;

use Doctrine\ORM\Mapping as ORM;
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
     * @var \Ramsey\Uuid\UuidInterface
     */
    protected $target;

    /**
     * @ORM\Column(type="json", unique=true)
     * @var array
     */
    protected $parameters;

    /**
     * @ORM\ManyToOne(targetEntity="\Rixafy\Doctrination\Language\Language")
     * @var \Rixafy\Doctrination\Language\Language
     */
    private $language;

    public function __construct(string $name, string $controller, \Ramsey\Uuid\UuidInterface $target, array $parameters = [], \Rixafy\Doctrination\Language\Language $language = null)
    {
        $this->name = $name;
        $this->controller = $controller;
        $this->target = $target;
        $this->parameters = $parameters;
        $this->language = $language;
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
     * @return \Ramsey\Uuid\UuidInterface
     */
    public function getTarget(): \Ramsey\Uuid\UuidInterface
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
     * @return \Rixafy\Doctrination\Language\Language
     */
    public function getLanguage(): \Rixafy\Doctrination\Language\Language
    {
        return $this->language;
    }
}