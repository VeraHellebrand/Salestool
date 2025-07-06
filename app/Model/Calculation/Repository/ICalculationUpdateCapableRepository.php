<?php declare(strict_types = 1);

namespace Model\Calculation\Repository;

use Model\Calculation\Entity\Calculation;

interface ICalculationUpdateCapableRepository extends ICalculationRepository
{

	public function updateStatus(Calculation $calculation): void;

	public function insert(Calculation $calculation): int;

}
