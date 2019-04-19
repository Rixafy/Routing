<?php

namespace Rixafy\Routing\Route;

use Ramsey\Uuid\UuidInterface;
use Rixafy\Language\Language;
use Rixafy\Routing\Route\Group\RouteGroup;

class RouteData
{
    /** @var string */
    public $name;

    /** @var string */
    public $controller;

    /** @var UuidInterface */
    public $target;

    /** @var array */
    public $parameters;

    /** @var Language */
    public $language;

    /** @var RouteGroup */
    public $group;
}
