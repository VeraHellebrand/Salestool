<?php declare(strict_types = 1);

namespace Model\Customer\Repository;

use Model\Customer\Entity\Customer;

interface ICustomerUpdateCapableRepository extends ICustomerRepository
{

	   /**
		* Persist new customer (create)
		*/
	public function save(Customer $customer): Customer;

	   /**
		* Persist changes to a customer (update by ID)
		*/
	public function update(Customer $customer): void;

}
