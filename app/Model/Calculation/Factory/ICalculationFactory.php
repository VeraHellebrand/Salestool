<?php declare(strict_types = 1);

namespace Model\Calculation\Factory;

use Model\Calculation\DTO\CalculationDTO;
use Model\Calculation\DTO\CalculationInput;
use Model\Calculation\Entity\Calculation;

interface ICalculationFactory
{

	public function createFromInput(
		CalculationInput $input,
		int $id,
		string $createdAt,
		string|null $updatedAt = null,
	): Calculation;

	public function createDTOFromEntity(Calculation $entity): CalculationDTO;

}
