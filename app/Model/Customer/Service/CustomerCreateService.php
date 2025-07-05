<?php declare(strict_types = 1);

namespace Model\Customer\Service;

use Dibi\UniqueConstraintViolationException;
use Model\Customer\DTO\CustomerDTO;
use Model\Customer\DTO\CustomerInput;
use Model\Customer\Factory\CustomerFactory;
use Model\Customer\Repository\ICustomerUpdateCapableRepository;
use Model\Customer\Validator\CustomerValidator;
use RuntimeException;
use Tracy\ILogger;
use function json_encode;

final class CustomerCreateService
{

	public function __construct(
		private ICustomerUpdateCapableRepository $repository,
		private CustomerFactory $factory,
		private CustomerValidator $validator,
		private ILogger $logger,
	)
	{
	}

	/**
	 * @throws RuntimeException|UniqueConstraintViolationException
	 */
	public function create(CustomerInput $input): CustomerDTO
	{
			$this->validator->validate($input);
			$customer = $this->factory->create(
				$input->firstName,
				$input->lastName,
				$input->email,
				$input->phone,
			);
			$customerWithId = $this->repository->save($customer);

			$dto = $this->factory->createDTOFromEntity($customerWithId);

			$this->logger->log(
				'Customer created | id: ' . $customerWithId->getId()
					. ' | data: ' . json_encode($dto->toArray()),
				ILogger::INFO,
			);

			return $dto;
	}

}
