<?php declare(strict_types = 1);

namespace Model\Calculation\Service;

use Model\Calculation\DTO\CalculationInput;
use Model\Calculation\Entity\Calculation;
use Model\Calculation\Factory\ICalculationFactory;
use Model\Calculation\Repository\ICalculationUpdateCapableRepository;
use Model\Customer\Repository\ICustomerRepository;
use Model\Tariff\Repository\ITariffRepository;
use RuntimeException;
use Tracy\ILogger;

final class CalculationCreateService
{

	public function __construct(
		private ICalculationUpdateCapableRepository $repository,
		private ICalculationFactory $factory,
		private ILogger $logger,
		private ITariffRepository $tariffRepository,
		private ICustomerRepository $customerRepository,
	)
	{
	}

	public function create(CalculationInput $input): Calculation
	{
		// Validate customer existence
		if (!$this->customerRepository->exists($input->customerId)) {
			throw new RuntimeException('Customer with the given ID does not exist.');
		}

		// Validate tariff existence
		if (!$this->tariffRepository->exists($input->tariffId)) {
			throw new RuntimeException('Tariff with the given ID does not exist.');
		}

		$entity = $this->factory->createFromInput($input);
		$id = $this->repository->insert($entity);

		$this->logger->log('Calculation created: ' . $id, ILogger::INFO);

		return $this->repository->get($id);
	}

}
