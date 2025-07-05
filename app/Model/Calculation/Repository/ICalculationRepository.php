<?php declare(strict_types = 1);

namespace Model\Calculation\Repository;

use Model\Calculation\Entity\Calculation;

interface ICalculationRepository
{

	/**
	 * @return array<Calculation>
	 */
	public function findAll(): array;

	public function get(int $id): Calculation;

}
