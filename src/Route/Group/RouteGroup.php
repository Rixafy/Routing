<?php

declare(strict_types=1);

namespace Rixafy\Routing\Route\Group;

use Doctrine\ORM\Mapping as ORM;
use Rixafy\DoctrineTraits\UniqueTrait;
use Rixafy\Routing\Route\Site\RouteSite;

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

	/**
	 * @ORM\ManyToOne(targetEntity="\Rixafy\Routing\Route\Site\RouteSite")
	 * @var RouteSite
	 */
	private $site;

    public function __construct(RouteGroupData $site)
    {
    	$this->site = $site->site;
        $this->edit($site);
    }

    public function edit(RouteGroupData $site): void
    {
        $this->name = $site->name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
