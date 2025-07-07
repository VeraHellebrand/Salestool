<?php declare(strict_types = 1);

namespace Model\Customer\DTO;

use DateTimeImmutable;
use Model\ArrayableInterface;

final class CustomerDTO implements ArrayableInterface
{

	public function __construct(
		public readonly int $id,
		public readonly string $firstName,
		public readonly string $lastName,
		public readonly string $email,
		public readonly string|null $phone,
		public readonly DateTimeImmutable $createdAt,
		public readonly DateTimeImmutable|null $updatedAt = null,
	)
	{
	}

	public function toArray(): array
	{
		return [
			'id' => $this->id,
			'first_name' => $this->firstName,
			'last_name' => $this->lastName,
			'email' => $this->email,
			'phone' => $this->phone,
			'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
			'updated_at' => $this->updatedAt?->format('Y-m-d H:i:s'),
		];
	}

}
