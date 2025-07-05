<?php declare(strict_types = 1);

namespace Model\Calculation\Repository;

use Model\Calculation\Entity\Calculation;

interface ICalculationUpdateCapableRepository extends ICalculationRepository
{

	public function save(Calculation $calculation): void;

	public function updateStatus(Calculation $calculation): void;

}
