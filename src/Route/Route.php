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
use Rixafy\Routing\Route\Site\RouteSite;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="route", indexes={
 *     @ORM\Index(columns={"name", "site_id"}),
 *     @ORM\UniqueConstraint(columns={"group_id", "site_id"})
 * })
 */
class Route
{
    use UniqueTrait;
    use ActiveTrait;
    use DateTimeTrait;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(type="string", length=31)
     */
    protected $controller;

    /**
     * @var string
     * @ORM\Column(type="string", length=31)
     */
    protected $action;

    /**
     * @var string
     * @ORM\Column(type="string", length=31)
     */
    protected $module;

    /**
     * @var UuidInterface
     * @ORM\Column(type="uuid_binary", unique=true)
     */
    protected $target;

    /**
     * @var array
     * @ORM\Column(type="array", nullable=true)
     */
    protected $previous_names;

    /**
     * @var array
     * @ORM\Column(type="array", nullable=true)
     */
    protected $parameters;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    protected $name_counter = 1;

    /**
     * @var Language
     * @ORM\ManyToOne(targetEntity="\Rixafy\Language\Language")
     */
    private $language;

    /**
     * @var RouteGroup
     * @ORM\ManyToOne(targetEntity="\Rixafy\Routing\Route\Group\RouteGroup")
     */
    private $group;

    /**
     * @var RouteSite
     * @ORM\ManyToOne(targetEntity="\Rixafy\Routing\Route\Site\RouteSite")
     */
    private $site;

    public function __construct(RouteData $data)
    {
        $this->controller = $data->controller;
        $this->action = $data->action;
        $this->module = $data->module;
        $this->target = $data->target;
        $this->parameters = $data->parameters;
        $this->language = $data->language;
        $this->group = $data->group;
        $this->site = $data->site;
        $this->edit($data);
    }

    public function edit(RouteData $data): void
    {
    	if ($this->name !== $data->name) {
			$this->addPreviousName();
			$this->name = $data->name;
		}
    }

    public function getData(): RouteData
	{
		$data = new RouteData();
		$data->name = $this->name;

		return $data;
	}

    public function getName(): string
    {
        return $this->name . ($this->name_counter !== 1 ?? '-' . $this->name_counter);
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

	public function addPreviousName(): void
	{
		if ($this->previous_names === null) {
			$this->previous_names = [];
		}

		$this->previous_names[] = $this->getName();

		if (count($this->previous_names) > 3) {
			array_shift($this->previous_names);
		}
	}

	public function getSite(): RouteSite
	{
		return $this->site;
	}

	public function getNameCounter(): int
	{
		return $this->name_counter;
	}

	public function increaseNameCounter(int $increaseBy): void
	{
		$this->name_counter += $increaseBy;
	}

	public function resetNameCounter(): void
	{
		$this->name_counter = 1;
	}
}
