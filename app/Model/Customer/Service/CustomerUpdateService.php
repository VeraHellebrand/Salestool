<?php declare(strict_types = 1);

namespace Model\Customer\Service;

use Model\Customer\DTO\CustomerDTO;
use Model\Customer\DTO\CustomerInput;
use Model\Customer\Entity\Customer;
use Model\Customer\Factory\CustomerFactory;
use Model\Customer\Repository\ICustomerRepository;
use Model\Customer\Validator\CustomerValidator;
use Tracy\ILogger;
use function json_encode;

final class CustomerUpdateService
{

	public function __construct(
		private ICustomerRepository $repository,
		private CustomerFactory $factory,
		private CustomerValidator $validator,
		private ILogger $logger,
	)
	{
	}

	public function update(CustomerInput $input, Customer $original): CustomerDTO
	{
		$this->validator->validate($input);
		$old = $original->toArray();
		$updated = $this->factory->update($input, $original);
		$dto = $this->factory->createDTOFromEntity($updated);

		$this->logger->log(
			'Změna zákazníka | id: ' . $original->getId()
			. ' | původní: ' . json_encode($old)
			. ' | nový: ' . json_encode($dto->toArray()),
			ILogger::INFO,
		);

		$this->repository->update($updated);

		return $dto;
	}

}
