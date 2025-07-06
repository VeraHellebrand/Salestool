<?php declare(strict_types = 1);

namespace Model\Customer\Factory;

use Model\Customer\DTO\CustomerInput;
use Model\Customer\Entity\Customer;

interface ICustomerFactory
{

	/**
	 * @return array<array<string, mixed>>
	 */
	public function createCustomerListResponse(): array;

	/**
	 * @return array<string, mixed>
	 */
	public function createCustomerDetailResponse(int $id): array;

	public function exists(int $id): bool;

	public function createFromInput(CustomerInput $input): Customer;

	public function updateFromInput(CustomerInput $input, Customer $original): Customer;

}
