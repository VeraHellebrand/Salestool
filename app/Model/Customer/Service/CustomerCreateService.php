<?php declare(strict_types = 1);

namespace Model\Customer\Service;

use Dibi\UniqueConstraintViolationException;
use Model\Customer\DTO\CustomerInput;
use Model\Customer\Entity\Customer;
use Model\Customer\Factory\ICustomerFactory;
use Model\Customer\Repository\ICustomerUpdateCapableRepository;
use RuntimeException;
use Tracy\ILogger;

final class CustomerCreateService implements ICustomerCreateService
{

	public function __construct(
		private ICustomerUpdateCapableRepository $repository,
		private ICustomerFactory $factory,
		private ILogger $logger,
	)
	{
	}

	/**
	 * @throws RuntimeException|UniqueConstraintViolationException
	 */
	public function create(CustomerInput $input): Customer
	{
		$entity = $this->factory->createFromInput($input);

		$id = $this->repository->insert($entity);

		$this->logger->log('Customer created: ' . $id, ILogger::INFO);

		return $this->repository->get($id);
	}

}
