<?php declare(strict_types = 1);

namespace Model\Calculation\Service;

use Model\Calculation\Factory\ICalculationFactory;
use Model\Calculation\Repository\ICalculationRepository;

final class CalculationService
{

	public function __construct(
		private readonly ICalculationRepository $repository,
		private readonly ICalculationFactory $factory,
	)
	{
	}

}
