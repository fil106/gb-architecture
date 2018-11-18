<?php

declare(strict_types = 1);

namespace Sorting;

class Sort
{

    public function sorting(ISorting $sorting, array $arr)
    {
        return $sorting->sort($arr);
    }
}