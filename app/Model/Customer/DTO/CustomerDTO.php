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

	public static function fromArray(array $data): static
	{
		return new self(
			(int) $data['id'],
			$data['first_name'],
			$data['last_name'],
			$data['email'],
			$data['phone'] ?? null,
			new DateTimeImmutable($data['created_at']),
			isset($data['updated_at']) && $data['updated_at'] ? new DateTimeImmutable(
				$data['updated_at'],
			) : null,
		);
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
