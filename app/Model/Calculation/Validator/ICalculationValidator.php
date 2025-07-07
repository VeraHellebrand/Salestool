<?php declare(strict_types = 1);

namespace Model\Calculation\Validator;

use Enum\CalculationStatus;
use RuntimeException;

interface ICalculationValidator
{

	/**
	 * @param array<string, mixed> $json
	 */
	public function validateCreateInput(array $json): void;

	/**
	 * @param array<string, mixed> $json
	 */
	public function validateStatusInput(array $json): void;

	/**
	 * @throws RuntimeException
	 */
	public function validateStatusTransition(CalculationStatus $from, CalculationStatus $to): void;

}
