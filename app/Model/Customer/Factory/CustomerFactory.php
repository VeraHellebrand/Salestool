<?php declare(strict_types = 1);

namespace Model\Customer\Factory;

use InvalidArgumentException;
use Model\Customer\Entity\Customer;
use Model\Customer\Repository\ICustomerRepository;
use Respect\Validation\Validator as v;

final class CustomerFactory implements ICustomerFactory
{

	public function __construct(private ICustomerRepository $customerRepository)
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
		$this->validateCustomerData($firstName, $lastName, $email, $phone);

		return new Customer($id, $firstName, $lastName, $email, $phone);
	}

	private function validateCustomerData(
		string $firstName,
		string $lastName,
		string $email,
		string|null $phone,
	): void
	{
		if (!v::stringType()->notEmpty()->length(1, 100)->validate($firstName)) {
			throw new InvalidArgumentException('Invalid first name');
		}

		if (!v::stringType()->notEmpty()->length(1, 100)->validate($lastName)) {
			throw new InvalidArgumentException('Invalid last name');
		}

		if (!v::email()->validate($email)) {
			throw new InvalidArgumentException('Invalid email');
		}

		if ($phone !== null && !v::phone()->validate($phone)) {
			throw new InvalidArgumentException('Invalid phone');
		}
	}

}
