<?php

declare(strict_types=1);

use App\Infrastructure\Config\Environment;
use App\Infrastructure\Container;

require_once __DIR__ . '/../vendor/autoload.php';

Environment::load(dirname(__DIR__));

return new Container();
