<?php

declare(strict_types=1);

namespace buzzingpixel\craftstatic\factories;

use craft\db\Query;

class QueryFactory
{
    public function createQuery() : Query
    {
        return new Query();
    }
}
