<?php

declare(strict_types=1);

namespace Rixafy\Routing\Route;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Rixafy\DoctrineTraits\DateTimeTrait;
use Rixafy\DoctrineTraits\UniqueTrait;
use Rixafy\Language\Language;
use Rixafy\Routing\Route\Group\RouteGroup;
use Rixafy\Routing\Route\Site\RouteSite;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="route", indexes={
 *	 @ORM\Index(columns={"name", "group_id"}),
 *	 @ORM\Index(columns={"name", "site_id"}),
 *	 @ORM\Index(columns={"target"})
 * }, uniqueConstraints={
 *	 @ORM\UniqueConstraint(columns={"target", "language_id"})
 * })
 */
class Route
{
	use UniqueTrait;

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
	 * @ORM\Column(type="uuid_binary")
	 */
	protected $target;

	/**
	 * @var array
	 * @ORM\Column(type="array", nullable=true)
	 */
	protected $previousNamesInSite = [];

	/**
	 * @var array
	 * @ORM\Column(type="array", nullable=true)
	 */
	protected $previousNamesInGroup = [];

	/**
	 * @var array
	 * @ORM\Column(type="array", nullable=true)
	 */
	protected $parameters;

	/**
	 * @var int
	 * @ORM\Column(type="integer")
	 */
	protected $siteNameCounter = 1;

	/**
	 * @var int
	 * @ORM\Column(type="integer")
	 */
	protected $groupNameCounter = 1;

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

	/**
	 * @ORM\Column(type="datetime")
	 * @var DateTime
	 */
	private $nameChangedAt;

	use DateTimeTrait;

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
		if ($data->name !== null) {
			$this->changeName($data->name);
		}
	}

	public function changeName(string $newName): void
	{
		if ($this->name !== $newName) {
			$this->archiveName();
			$this->name = $newName;
			$this->nameChangedAt = new DateTime();
			$this->resetGroupNameCounter();
			$this->resetSiteNameCounter();
			$this->site->update();
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
		return $this->name;
	}

	public function getNameInSite(): string
	{
		return $this->name . ($this->siteNameCounter !== 1 ? '-' . $this->siteNameCounter : '');
	}

	public function getNameInGroup(): string
	{
		return $this->name . ($this->groupNameCounter !== 1 ? '-' . $this->groupNameCounter : '');
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

	public function getPreviousNamesInSite(): array
	{
		return $this->previousNamesInSite;
	}

	public function getPreviousNamesInGroup(): array
	{
		return $this->previousNamesInGroup;
	}

	public function archiveName(): void
	{
		if ($this->name === null) {
			return;
		}

		$this->previousNamesInSite[] = $this->getNameInSite();
		$this->previousNamesInGroup[] = $this->getNameInSite();

		if (count($this->previousNamesInSite) > 3) {
			array_shift($this->previousNamesInSite);
		}

		if (count($this->previousNamesInGroup) > 3) {
			array_shift($this->previousNamesInGroup);
		}
	}

	public function getSite(): RouteSite
	{
		return $this->site;
	}

	public function getSiteNameCounter(): int
	{
		return $this->siteNameCounter;
	}

	public function increaseSiteNameCounter(int $increaseBy): void
	{
		$this->siteNameCounter += $increaseBy;
	}

	public function resetSiteNameCounter(): void
	{
		$this->siteNameCounter = 1;
	}

	public function getGroupNameCounter(): int
	{
		return $this->groupNameCounter;
	}

	public function increaseGroupNameCounter(int $increaseBy): void
	{
		$this->groupNameCounter += $increaseBy;
	}

	public function resetGroupNameCounter(): void
	{
		$this->groupNameCounter = 1;
	}
}
