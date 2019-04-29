<?php

declare(strict_types=1);

namespace Rixafy\Routing\Route;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Rixafy\DoctrineTraits\ActiveTrait;
use Rixafy\DoctrineTraits\DateTimeTrait;
use Rixafy\DoctrineTraits\UniqueTrait;
use Rixafy\Language\Language;
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
     * @ORM\Column(type="string", length=31)
     * @var string
     */
    protected $action;

    /**
     * @ORM\Column(type="string", length=31)
     * @var string
     */
    protected $module;

    /**
     * @ORM\Column(type="uuid_binary", unique=true)
     * @var UuidInterface
     */
    protected $target;

    /**
     * @ORM\Column(type="array", nullable=true)
     * @var array
     */
    protected $previous_names;

    /**
     * @ORM\Column(type="array", nullable=true)
     * @var array
     */
    protected $parameters;

    /**
     * @ORM\ManyToOne(targetEntity="\Rixafy\Language\Language")
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
        $this->action = $routeData->action;
        $this->module = $routeData->module;
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

    public function getData(): RouteData
	{
		$data = new RouteData();
		$data->name = $this->name;

		return $data;
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
        return $this->parameters === null ? [] : $this->parameters;
    }

    public function getLanguage(): Language
    {
        return $this->language;
    }

    public function getGroup(): RouteGroup
    {
        return $this->group;
    }

	public function getAction(): string
	{
		return $this->action;
	}

	public function getModule(): string
	{
		return $this->module;
	}

	public function getPreviousNames(): array
	{
		return $this->previous_names === null ? [] : $this->previous_names;
	}

	public function addPreviousName(string $name): void
	{
		if ($this->previous_names === null) {
			$this->previous_names = [];
		}

		$this->previous_names[] = $name;

		if (count($this->previous_names) > 3) {
			array_shift($this->previous_names);
		}
	}
}
