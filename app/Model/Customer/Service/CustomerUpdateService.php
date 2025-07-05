<?php declare(strict_types = 1);

namespace Model\Customer\Service;

use Model\Customer\DTO\CustomerDTO;
use Model\Customer\DTO\CustomerInput;
use Model\Customer\Entity\Customer;
use Model\Customer\Factory\CustomerFactory;
use Model\Customer\Repository\ICustomerUpdateCapableRepository;
use Model\Customer\Validator\CustomerValidator;
use RuntimeException;
use Throwable;
use Tracy\ILogger;
use function json_encode;

final class CustomerUpdateService
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
		* PATCH-like update: merge missing fields from original entity, prefer snake_case keys (API style)
		*/
	public function update(CustomerInput $input, Customer $original): CustomerDTO
	{
			// PATCH: merge missing fields from original entity, prefer snake_case keys (API style)
			$merged = [
				'first_name' => $input->firstName !== '' ? $input->firstName : $original->getFirstName(),
				'last_name' => $input->lastName !== '' ? $input->lastName : $original->getLastName(),
				'email' => $input->email !== '' ? $input->email : $original->getEmail(),
				'phone' => $input->phone ?? $original->getPhone(),
			];
			try {
					$mergedInput = CustomerInput::fromArray($merged);
			} catch (Throwable) {
					throw new RuntimeException('Invalid input data');
			}

			$this->validator->validate($mergedInput);
			$old = $original->toArray();
			$updated = $this->factory->update($mergedInput, $original);
			$dto = $this->factory->createDTOFromEntity($updated);

			$this->logger->log(
				'Customer updated | id: ' . $original->getId()
					. ' | old: ' . json_encode($old)
					. ' | new: ' . json_encode($dto->toArray()),
				ILogger::INFO,
			);

			$this->repository->update($updated);

			return $dto;
	}

}
