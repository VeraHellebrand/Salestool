<?php declare(strict_types = 1);

namespace Model\Calculation\Repository;

use LogicException;
use Model\Calculation\Entity\Calculation;

final class CalculationRepository implements ICalculationRepository
{

	public function get(int $id): Calculation
	{
		// TODO: implement
		throw new LogicException('Not implemented');
	}

	public function findAll(): array
	{
		// TODO: implement
		return [];
	}

}
