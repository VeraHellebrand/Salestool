<?php declare(strict_types = 1);

namespace Model\Customer\Service;

use Dibi\UniqueConstraintViolationException;
use Model\Customer\DTO\CustomerDTO;
use Model\Customer\DTO\CustomerInput;
use Model\Customer\Factory\CustomerFactory;
use Model\Customer\Repository\ICustomerRepository;
use Model\Customer\Validator\CustomerValidator;
use RuntimeException;

final class CustomerCreateService
{

	public function __construct(
		private ICustomerRepository $repository,
		private CustomerFactory $factory,
		private CustomerValidator $validator,
	)
	{
	}

	/**
	 * @throws RuntimeException|UniqueConstraintViolationException
	 */
	public function create(CustomerInput $input): CustomerDTO
	{
		$this->validator->validate($input);
		$customer = $this->factory->create(
			$input->firstName,
			$input->lastName,
			$input->email,
			$input->phone,
		);
		$customerWithId = $this->repository->save($customer);

		return $this->factory->createDTOFromEntity($customerWithId);
	}

}
