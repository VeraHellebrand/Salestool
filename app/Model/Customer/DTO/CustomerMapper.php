<?php declare(strict_types = 1);

namespace Model\Customer\DTO;

use Model\Customer\Entity\Customer;

final class CustomerMapper
{

	public static function toDTO(Customer $customer): CustomerDTO
	{
		return new CustomerDTO(
			$customer->getId(),
			$customer->getFirstName(),
			$customer->getLastName(),
			$customer->getEmail(),
			$customer->getPhone(),
			$customer->getCreatedAt(),
			$customer->getUpdatedAt(),
		);
	}

	public static function fromDTO(CustomerDTO $dto): Customer
	{
		return new Customer(
			$dto->id,
			$dto->firstName,
			$dto->lastName,
			$dto->email,
			$dto->phone,
			$dto->createdAt,
			$dto->updatedAt,
		);
	}

}
