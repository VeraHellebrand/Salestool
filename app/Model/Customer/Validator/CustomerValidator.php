<?php declare(strict_types = 1);

namespace Model\Customer\Validator;

use InvalidArgumentException;
use Model\Customer\DTO\CustomerInput;
use Respect\Validation\Validator as v;

final class CustomerValidator
{

	public function validate(CustomerInput $input): void
	{
		if (!v::stringType()->notEmpty()->length(1, 100)->validate($input->firstName)) {
			throw new InvalidArgumentException('Invalid first name');
		}

		if (!v::stringType()->notEmpty()->length(1, 100)->validate($input->lastName)) {
			throw new InvalidArgumentException('Invalid last name');
		}

		if (!v::email()->validate($input->email)) {
			throw new InvalidArgumentException('Invalid email');
		}

		if ($input->phone !== null && !v::phone()->validate($input->phone)) {
			throw new InvalidArgumentException('Invalid phone');
		}
	}

}
