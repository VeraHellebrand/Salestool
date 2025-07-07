<?php declare(strict_types = 1);

namespace Model\Calculation\Service;

use Model\Calculation\DTO\CalculationInput;
use Model\Calculation\Entity\Calculation;

interface ICalculationCreateService
{

	public function create(CalculationInput $input): Calculation;

}
