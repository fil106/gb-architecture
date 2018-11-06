<?php

namespace Sorting;

interface ISorting
{
    public function sort($array);
}

class SortByPrice implements ISorting
{

	function sort($array)
	{
		usort($array, function( $a, $b ){ return $a->getPrice() > $b->getPrice(); });

		return $array;
	}
}

class SortByName implements ISorting
{

	function sort($array)
	{
		usort($array, function( $a, $b ){ return $a->getName() > $b->getName(); });

		return $array;
	}
}

class Sort
{

    public function sorting(ISorting $sorting, array $arr)
    {
        return $sorting->sort($arr);
    }
}