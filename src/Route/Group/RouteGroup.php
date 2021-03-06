<?php

declare(strict_types=1);

namespace Rixafy\Routing\Route\Group;

use Doctrine\ORM\Mapping as ORM;
use Rixafy\DoctrineTraits\UniqueTrait;
use Rixafy\Routing\Route\Site\RouteSite;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="route_group", uniqueConstraints={
 *	 @ORM\UniqueConstraint(columns={"name", "site_id"})
 * })
 */
class RouteGroup
{
	use UniqueTrait;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $name;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=127)
	 */
	protected $prefix;

	/**
	 * @var array
	 * @ORM\Column(type="array", nullable=true)
	 */
	protected $previousPrefixes = [];

	/**
	 * @var RouteSite
	 * @ORM\ManyToOne(targetEntity="\Rixafy\Routing\Route\Site\RouteSite")
	 */
	private $site;

	public function __construct(RouteGroupData $data)
	{
		$this->site = $data->site;
		$this->edit($data);
	}

	public function edit(RouteGroupData $data): void
	{
		$this->name = $data->name;
		if ($data->prefix !== $this->prefix) {
			$this->archivePrefix();
			$this->prefix = $data->prefix;
		}
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getPrefix(): string
	{
		return $this->prefix;
	}

	public function archivePrefix(): void
	{
		$this->previousPrefixes[] = $this->prefix;

		if (count($this->previousPrefixes) > 3) {
			array_shift($this->previousPrefixes);
		}
	}

	public function getSite(): RouteSite
	{
		return $this->site;
	}
}
