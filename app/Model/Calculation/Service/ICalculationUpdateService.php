<?php declare(strict_types = 1);

namespace Model\Calculation\Service;

use Enum\CalculationStatus;
use Model\Calculation\Entity\Calculation;

interface ICalculationUpdateService
{

	public function updateStatus(int $id, CalculationStatus $status): Calculation;

}
