<?php declare(strict_types = 1);

namespace Model\Customer\Repository;

use Model\Customer\Entity\Customer;

interface ICustomerUpdateCapableRepository extends ICustomerRepository
{

	public function update(Customer $customer): void;

	public function insert(Customer $customer): int;

}
