<?php

namespace Ofcold\NumericComponent;

/**
 * Class for safe numeric operations
 */
class Numeric
{
	protected string $dirtyValue;

	protected int $scale;

	public function __construct(
		string|float|int|Numeric $value,
		?int $scale = null
	) {
		$scale = $scale ?: 8;
		$this->dirtyValue = $value instanceof self
			? (string) $value
			: $this->format(
				decimals: $scale,
				value: $value
			);

		$this->scale = $scale;
	}

	/**
	 * Get the body of the response.
	 *
	 * @return string
	 */
	public function __toString(): string
	{
		return $this->trimTrailingZero($this->format($this->scale));
	}

	public function get(): string
	{
		return $this->__toString();
	}

	public function positive(): string
	{
		return $this->trimTrailingZero($this->format(
			decimals: $this->scale,
			value: abs($this->dirtyValue)
		));
	}

	public function negative(): string
	{
		return $this->trimTrailingZero($this->format(
			decimals: $this->scale,
			value: -abs($this->dirtyValue)
		));
	}

	public function createNumeric(string|float|int|Numeric $value): string
	{
		return (string) ($value instanceof Numeric ? $value : new self($value, $this->scale));
	}

	public function add(string|float|int|Numeric $value): self
	{
		$this->dirtyValue = bcadd((string) $this, $this->createNumeric($value), $this->scale);

		return $this;
	}

	public function sub(string|float|int|Numeric $value): self
	{
		$this->dirtyValue = bcsub((string) $this, $this->createNumeric($value), $this->scale);

		return $this;
	}

	public function mul(string|float|int|Numeric $value): self
	{
		$this->dirtyValue = bcmul((string) $this, $this->createNumeric($value), $this->scale);

		return $this;
	}

	public function div(string|float|int|Numeric $value): self
	{
		$this->dirtyValue = bcdiv((string) $this, $this->createNumeric($value), $this->scale);

		return $this;
	}

	public function equals(string|float|int|Numeric $value): bool
	{
		return bccomp((string) $this, $this->createNumeric($value), $this->scale) === 0;
	}

	public function greaterThan(string|float|int|Numeric $value): bool
	{
		return bccomp((string) $this, $this->createNumeric($value), $this->scale) === 1;
	}

	public function lessThan(string|float|int|Numeric $value): bool
	{
		return bccomp((string) $this, $this->createNumeric($value), $this->scale) === -1;
	}

	public function greaterThanOrEqual(string|float|int|Numeric $value): bool
	{
		return bccomp((string) $this, $this->createNumeric($value), $this->scale) >= 0;
	}

	/**
	 * Determine whether the current value is less than or equal to the incoming value.
	 *
	 * @param  Numeric $value
	 *
	 * @return boolean
	 */
	public function lessThanOrEqual(string|float|int|Numeric $value): bool
	{
		return bccomp((string) $this, $this->createNumeric($value), $this->scale) <= 0;
	}

	/**
	 * Get the minimum value of the given values
	 *
	 * @param  string|float|int|Numeric[]  ...$values
	 */
	public function min(...$values): Numeric
	{
		$min = $this;

		foreach ($values as $value) {
			if (num($value)->lessThan($min)) {
				$min = $value;
			}
		}

		return new self($min, $this->scale);
	}

	/**
	 * Get the maximum value of the given values
	 *
	 * @param  string|float|int|Numeric[]  ...$values
	 */
	public function max(...$values): Numeric
	{
		$max = $this;
		foreach ($values as $value) {
			if (num($value)->greaterThan($max)) {
				$max = $value;
			}
		}

		return new self($max, $this->scale);
	}

	public function format(
		int $decimals = 8,
		string $decimalSeparator = '.',
		string $thousandsSeparator = '',
		mixed $value = null
	): string {
		$value ??= $this->dirtyValue;

		return number_format(
			(float) $value,
			$decimals,
			$decimalSeparator,
			$thousandsSeparator
		);
	}

	protected function trimTrailingZero(string $value): string
	{
		return str_contains($value, '.')
			? rtrim(rtrim($value, '0'), '.')
			: $value;
	}

	public function scale(int $scale): self
	{
		$this->scale = $scale;

		return $this;
	}
}
