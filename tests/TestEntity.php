<?php

namespace Revys\RevyAdmin\Tests;

use Revys\Revy\App\Entity;
use Revys\Revy\App\Traits\Translatable;
use Revys\Revy\App\Traits\WithImages;

class TestEntity extends Entity
{
    use Translatable, WithImages;

    public static $translatedAttributes = ['multilang_field'];
}