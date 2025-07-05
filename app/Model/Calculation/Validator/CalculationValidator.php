<?php declare(strict_types = 1);

namespace Model\Calculation\Validator;

use Enum\CalculationStatus;
use Model\Calculation\DTO\CalculationInput;
use RuntimeException;
use function in_array;

final class CalculationValidator
{

	public function validate(CalculationInput $input): void
	{
		// customerId and tariffId are immutable and must not be changed after creation
		if ($input->customerId <= 0) {
			throw new RuntimeException('Invalid customer ID');
		}

		if ($input->tariffId <= 0) {
			throw new RuntimeException('Invalid tariff ID');
		}

		if ($input->priceNoVat < 0) {
			throw new RuntimeException('Price without VAT must be non-negative');
		}

		if ($input->vatPercent < 0 || $input->vatPercent > 100) {
			throw new RuntimeException('VAT percent must be between 0 and 100');
		}

		if ($input->priceWithVat < 0) {
			throw new RuntimeException('Price with VAT must be non-negative');
		}

		if ($input->currency === '') {
			throw new RuntimeException('Currency must not be empty');
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

}
