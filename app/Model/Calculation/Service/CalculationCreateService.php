<?php declare(strict_types = 1);

namespace Model\Calculation\Service;

use LogicException;
use Model\Calculation\DTO\CalculationDTO;
use Model\Calculation\DTO\CalculationInput;
use Model\Calculation\Factory\ICalculationFactory;
use Model\Calculation\Repository\ICalculationUpdateCapableRepository;
use Model\Calculation\Validator\CalculationValidator;
use Tracy\ILogger;

final class CalculationCreateService
{

	public function __construct(
		private ICalculationUpdateCapableRepository $repository,
		private ICalculationFactory $factory,
		private CalculationValidator $validator,
		private ILogger $logger,
	)
	{
	}

	public function create(CalculationInput $input): CalculationDTO
	{
		// TODO: implement
		throw new LogicException('Not implemented');
	}

}
