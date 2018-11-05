<?php

namespace Sorting;

interface ISorting
{
    public function sort();
}

class SortByPrice implements ISorting
{
    public function sort()
    {
        return "Sort by price";
    }
}

class SortByName implements ISorting
{
    public function sort()
    {
        return "Sort by name";
    }
}

class Sort
{
    public function sort(ISorting $sorting)
    {
        $sorting->sort();
    }
}