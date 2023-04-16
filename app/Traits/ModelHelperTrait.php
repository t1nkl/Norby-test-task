<?php

namespace App\Traits;

trait ModelHelperTrait
{
    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return with(new static())->getTable();
    }
}
