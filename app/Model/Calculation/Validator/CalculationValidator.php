<?php declare(strict_types = 1);

namespace Model\Calculation\Validator;

use Enum\CalculationStatus;
use Respect\Validation\Validator as v;
use RuntimeException;
use ValueError;
use function array_key_exists;
use function in_array;
use function is_string;

final class CalculationValidator
{

	/**
	 * Validates the status field in a JSON array (API input)
	 *
	 * @param array<string, mixed> $json
	 * @throws RuntimeException
	 */
	public function validateStatusInput(array $json): void
	{
		if (!array_key_exists('status', $json)) {
			throw new RuntimeException('Missing required field: status');
		}

		if (!is_string($json['status'])) {
			throw new RuntimeException('Status must be a string');
		}

		$this->validateStatusValue($json['status']);
	}

		/**
		 * Validates that the given status string is a valid CalculationStatus value
		 *
		 * @throws RuntimeException
		 */
	public function validateStatusValue(string $status): void
	{
		try {
			CalculationStatus::from($status);
		} catch (ValueError) {
			throw new RuntimeException('Invalid status value');
		}
	}

	/**
	 * Validates allowed status transition for calculation
	 *
	 * @throws RuntimeException
	 */
	public function validateStatusTransition(CalculationStatus $from, CalculationStatus $to): void
	{
		if ($from === $to) {
			return; // no change
		}

		switch ($from) {
			case CalculationStatus::NEW:
				if ($to !== CalculationStatus::PENDING) {
					throw new RuntimeException('Status can only change from NEW to PENDING');
				}

				break;
			case CalculationStatus::PENDING:
				if (!in_array($to, [CalculationStatus::ACCEPTED, CalculationStatus::REJECTED], true)) {
					throw new RuntimeException('Status can only change from PENDING to ACCEPTED or REJECTED');
				}

				break;
			case CalculationStatus::ACCEPTED:
			case CalculationStatus::REJECTED:
				throw new RuntimeException('Status cannot be changed after ACCEPTED or REJECTED');
		}
	}

	/**
	 * Validates input array for calculation creation (API input) using Respect/Validation
	 *
	 * @param array<string, mixed> $json
	 * @throws RuntimeException
	 */
	public function validateCreateInput(array $json): void
	{
		$validator = v::key('customerId', v::intType()->min(1))
			->key('tariffId', v::intType()->min(1))
			->key('priceWithVat', v::numericVal()->min(0));

		$validator->assert($json);
	}

}
