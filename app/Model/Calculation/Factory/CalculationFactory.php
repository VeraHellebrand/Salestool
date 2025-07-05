<?php declare(strict_types = 1);

namespace Model\Calculation\Factory;

use LogicException;
use Model\Calculation\DTO\CalculationDTO;
use Model\Calculation\DTO\CalculationInput;
use Model\Calculation\DTO\CalculationMapper;
use Model\Calculation\Entity\Calculation;

final class CalculationFactory implements ICalculationFactory
{

	public function createFromInput(
		CalculationInput $input,
		int $id,
		string $createdAt,
		string|null $updatedAt = null,
	): Calculation
	{
		// TODO: implement
		throw new LogicException('Not implemented');
	}

	public function createDTOFromEntity(Calculation $entity): CalculationDTO
	{
		return CalculationMapper::toDTO($entity);
	}

}
