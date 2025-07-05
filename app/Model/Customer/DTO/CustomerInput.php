<?php declare(strict_types = 1);

namespace Model\Customer\DTO;

final readonly class CustomerInput
{

	public function __construct(
		public string $firstName = '',
		public string $lastName = '',
		public string $email = '',
		public string|null $phone = null,
	)
	{
	}

	   /**
		* @param array<string, mixed> $data
		*/
	public static function fromArray(array $data): static
	{
		return new self(
			$data['first_name'] ?? '',
			$data['last_name'] ?? '',
			$data['email'] ?? '',
			$data['phone'] ?? null,
		);
	}

}
