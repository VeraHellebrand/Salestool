<?php declare(strict_types = 1);

namespace Model\Customer\Service;

use Model\Customer\DTO\CustomerInput;
use Model\Customer\Entity\Customer;
use Model\Customer\Factory\ICustomerFactory;
use Model\Customer\Repository\ICustomerUpdateCapableRepository;
use Tracy\ILogger;
use function json_encode;

final class CustomerUpdateService implements ICustomerUpdateService
{

	public function __construct(
		private ICustomerUpdateCapableRepository $repository,
		private ICustomerFactory $factory,
		private ILogger $logger,
	)
	{
	}

	public function update(int $id, CustomerInput $input): Customer
	{
		$original = $this->repository->get($id);
		$old = $original->toDbArray();
		$updated = $this->factory->updateFromInput($input, $original);

		$this->repository->update($updated);
		$this->logger->log(
			'Customer updated | id: ' . $original->getId()
				. ' | old: ' . json_encode($old)
				. ' | new: ' . json_encode($updated->toDbArray()),
			ILogger::INFO,
		);

		return $updated;
	}

}
