<?php

declare(strict_types = 1);

namespace Sorting;

class SortByPrice implements ISorting
{

	function sort($array)
	{
		usort($array, function( $a, $b ){ return $a->getPrice() > $b->getPrice(); });

		return $array;
	}
}