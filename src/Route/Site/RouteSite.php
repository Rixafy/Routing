<?php

declare(strict_types=1);

namespace Rixafy\Routing\Route\Site;

use Doctrine\ORM\Mapping as ORM;
use Rixafy\DoctrineTraits\UniqueTrait;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="route_site")
 */
class RouteSite
{
	use UniqueTrait;

	/**
	 * @ORM\Column(type="string", length=255)
	 * @var string
	 */
	protected $domain_host;

	public function __construct(RouteSiteData $routeSiteData)
	{
		$this->edit($routeSiteData);
	}

	public function edit(RouteSiteData $routeSiteData): void
	{
		$this->domain_host = $routeSiteData->domainHost;
	}

	public function getDomainHost(): string
	{
		return $this->domain_host;
	}
}
