<?php

use Ofcold\NumericComponent\Numeric;

if (! function_exists('num')) {
	/**
	 * Create a new Numeric instance for safe calculations
	 */
	function num(string|float|int|Numeric $value, ?int $scale = null): Numeric
	{
		return new Numeric($value, $scale);
	}
}
