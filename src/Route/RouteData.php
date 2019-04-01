<?php

namespace Rixafy\Routing\Route;

use Ramsey\Uuid\UuidInterface;
use Rixafy\Doctrination\Language\Language;

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
}