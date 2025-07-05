<?php declare(strict_types = 1);

namespace Model\Calculation\Validator;

use Model\Calculation\DTO\CalculationInput;
use RuntimeException;

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

}
