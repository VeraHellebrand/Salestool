<?php declare(strict_types = 1);

namespace Model\Calculation\Validator;

use Enum\CalculationStatus;
use Model\Calculation\Helper\CalculationHelper;
use Respect\Validation\Validator as v;
use RuntimeException;
use function is_string;

final class CalculationValidator implements ICalculationValidator
{

	/**
	 * Validates the status field in a JSON array (API input)
	 *
	 * @param array<string, mixed> $json
	 * @throws RuntimeException
	 */
	public function validateStatusInput(array $json): void
	{
		$validator = v::key(
			'status',
			v::stringType()->callback(
				static fn ($value) => is_string($value) && CalculationStatus::tryFrom($value) !== null,
			),
		);
		$validator->assert($json);
	}

	/**
	 * Validates allowed status transition for calculation
	 *
	 * @throws RuntimeException
	 */
	public function validateStatusTransition(CalculationStatus $from, CalculationStatus $to): void
	{
		if ($from === $to) {
			return;
		}

		if (CalculationHelper::isFinalized($from)) {
			throw new RuntimeException('Status cannot be changed after accepted or rejected');
		}

		if ($from === CalculationStatus::NEW && $to !== CalculationStatus::PENDING) {
			throw new RuntimeException('Status can only change from new to pending');
		}

		if ($from === CalculationStatus::PENDING && !CalculationHelper::isFinalized($to)) {
			throw new RuntimeException('Status can only change from pending to accepted or rejected');
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
