<?php

declare(strict_types=1);

namespace Kejubayer\Steadfast\Facades;

use Illuminate\Support\Facades\Facade;

class Steadfast extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'steadfast';
    }
}
