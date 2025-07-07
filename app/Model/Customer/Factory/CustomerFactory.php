<?php declare(strict_types = 1);

namespace Model\Customer\Factory;

use Common\Clock\DateTimeProvider;
use Model\Customer\DTO\CustomerInput;
use Model\Customer\DTO\CustomerMapper;
use Model\Customer\Entity\Customer;
use Model\Customer\Repository\ICustomerRepository;

final class CustomerFactory implements ICustomerFactory
{

	public function __construct(
		private ICustomerRepository $customerRepository,
		private DateTimeProvider $dateTimeProvider,
	)
	{
	}

	public function createCustomerListResponse(): array
	{
		$entities = $this->customerRepository->findAll();
		$result = [];
		foreach ($entities as $entity) {
			$result[] = CustomerMapper::toDTO($entity)->toArray();
		}

		return $result;
	}

	public function createCustomerDetailResponse(int $id): array
	{
		$entity = $this->customerRepository->get($id);

		return CustomerMapper::toDTO($entity)->toArray();
	}

	public function exists(int $id): bool
	{
		return $this->customerRepository->exists($id);
	}

	public function createFromInput(CustomerInput $input): Customer
	{
		return new Customer(
			0,
			$input->firstName,
			$input->lastName,
			$input->email,
			$input->phone,
			$this->dateTimeProvider->now(),
			null,
		);
	}

	public function updateFromInput(CustomerInput $input, Customer $original): Customer
	{
		return new Customer(
			$original->getId(),
			$input->firstName,
			$input->lastName,
			$input->email,
			$input->phone,
			$original->getCreatedAt(),
			$this->dateTimeProvider->now(),
		);
	}

}
