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

    /**
     * Route constructor.
     * @param string $name
     * @param \Ramsey\Uuid\UuidInterface $target
     * @param array $parameters
     * @param \Rixafy\Doctrination\Language\Language $language
     */
    public function __construct(string $name, \Ramsey\Uuid\UuidInterface $target, array $parameters = [], \Rixafy\Doctrination\Language\Language $language = null)
    {
        $this->name = $name;
        $this->target = $target;
        $this->parameters = $parameters;
        $this->language = $language;
    }
}