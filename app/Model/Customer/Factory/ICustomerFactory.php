<?php declare(strict_types = 1);

namespace Model\Customer\Factory;

use Model\Customer\Entity\Customer;

interface ICustomerFactory
{

	public function createFromId(int $id): Customer;

	public function create(
		string $firstName,
		string $lastName,
		string $email,
		string|null $phone = null,
		int $id = 0,
	): Customer;

}
