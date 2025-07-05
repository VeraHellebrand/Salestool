<?php declare(strict_types = 1);

namespace Model\Customer\Factory;

use Common\Clock\DateTimeProvider;
use Model\Customer\DTO\CustomerDTO;
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

	public function createFromId(int $id): Customer
	{
		return $this->customerRepository->get($id);
	}

	public function create(
		string $firstName,
		string $lastName,
		string $email,
		string|null $phone = null,
		int $id = 0,
	): Customer
	{
		$now = $this->dateTimeProvider->now();

		// ID při create = 0, po uložení do DB se použije správné ID z repository
		return new Customer(0, $firstName, $lastName, $email, $phone, $now, null);
	}

	public function createDTOFromEntity($customer): CustomerDTO
	{
		return CustomerMapper::toDTO($customer);
	}

	public function createEntityFromDTO($dto): Customer
	{
		return CustomerMapper::fromDTO($dto);
	}

	public function update(CustomerInput $input, Customer $original): Customer
	{
		$now = $this->dateTimeProvider->now();

		return new Customer(
			$original->getId(),
			$input->firstName,
			$input->lastName,
			$input->email,
			$input->phone,
			$original->getCreatedAt(),
			$now,
		);
	}

}
