<?php declare(strict_types = 1);

namespace Model\Customer\Service;

use Model\Customer\DTO\CustomerInput;
use Model\Customer\Entity\Customer;

interface ICustomerUpdateService
{

	public function update(int $id, CustomerInput $input): Customer;

}
