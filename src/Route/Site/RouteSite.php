<?php

declare(strict_types=1);

namespace Rixafy\Routing\Route\Site;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Rixafy\DoctrineTraits\DateTimeTrait;
use Rixafy\DoctrineTraits\UniqueTrait;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="route_site")
 */
class RouteSite
{
	use UniqueTrait;
	use DateTimeTrait;

	/**
	 * @ORM\Column(type="string", length=255)
	 * @var string
	 */
	protected $domainHost;

	public function __construct(RouteSiteData $routeSiteData)
	{
		$this->edit($routeSiteData);
	}

	public function edit(RouteSiteData $routeSiteData): void
	{
		$this->domainHost = $routeSiteData->domainHost;
	}

	public function getDomainHost(): string
	{
		return $this->domainHost;
	}

	public function update(): void
	{
		$this->updatedAt = new DateTime();
	}
}
