<?php declare(strict_types = 1);

namespace Model\Calculation\Factory;

use Model\Calculation\DTO\CalculationInput;
use Model\Calculation\Entity\Calculation;

final class CalculationFactory implements ICalculationFactory
{

	public function create(CalculationInput $input): Calculation
	{
		return new Calculation(
			$input->id,
			$input->customerId,
			$input->tariffId,
			$input->priceNoVat,
			$input->vatPercent,
			$input->priceWithVat,
			$input->currency,
			$input->status,
			$input->createdAt,
			$input->updatedAt,
		);
	}

}
