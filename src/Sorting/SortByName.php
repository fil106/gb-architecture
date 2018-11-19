<?php

declare(strict_types = 1);

namespace Sorting;

class SortByName implements ISorting
{

	function sort($array)
	{
		usort($array, function( $a, $b ){ return $a->getName() > $b->getName(); });

		return $array;
	}
}