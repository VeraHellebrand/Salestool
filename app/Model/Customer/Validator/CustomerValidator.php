<?php declare(strict_types = 1);

namespace Model\Customer\Validator;

use Respect\Validation\Validator as v;

final class CustomerValidator
{

	   /**
		* @param array<string, mixed> $json
		*/
	public function validateCreateInput(array $json): void
	{
		$validator = v::key('first_name', v::stringType()->notEmpty()->length(1, 100))
		 ->key('last_name', v::stringType()->notEmpty()->length(1, 100))
		 ->key('email', v::email())
		 ->key('phone', v::optional(v::stringType()->length(0, 50)));
		$validator->assert($json);
	}

}
