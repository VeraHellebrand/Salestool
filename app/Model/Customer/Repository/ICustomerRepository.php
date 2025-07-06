<?php declare(strict_types = 1);

namespace Model\Customer\Repository;

use Model\Customer\Entity\Customer;

interface ICustomerRepository
{

	public function get(int $id): Customer;

	public function findByEmail(string $email): Customer|null;

	   /**
		* @return array<Customer>
		*/
	public function findAll(): array;

	public function exists(int $id): bool;

}
