<?php declare(strict_types = 1);

namespace Model\Customer\Validator;

interface ICustomerValidator
{

	/**
	 * @param array<string, mixed> $json
	 */
	public function validateCreateInput(array $json): void;

	/**
	 * @param array<string, mixed> $json
	 */
	public function validateUpdateInput(array $json): void;

}
