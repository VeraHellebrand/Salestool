<?php declare(strict_types = 1);

namespace Model\Calculation\Factory;

use Model\Calculation\DTO\CalculationInput;
use Model\Calculation\Entity\Calculation;

interface ICalculationFactory
{

	public function create(CalculationInput $input): Calculation;

}
