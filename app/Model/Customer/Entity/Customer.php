<?php declare(strict_types = 1);

namespace Model\Customer\Entity;

use DateTimeImmutable;
use Model\ArrayableEntityInterface;

final class Customer implements ArrayableEntityInterface
{

	public function __construct(
		private int $id,
		private string $firstName,
		private string $lastName,
		private string $email,
		private string|null $phone,
		private DateTimeImmutable $createdAt,
		private DateTimeImmutable|null $updatedAt = null,
	)
	{
	}

	public function getId(): int
	{
		return $this->id;
	}

	public function getFirstName(): string
	{
		return $this->firstName;
	}

	public function getLastName(): string
	{
		return $this->lastName;
	}

	public function getEmail(): string
	{
		return $this->email;
	}

	public function getPhone(): string|null
	{
		return $this->phone;
	}

	public function getCreatedAt(): DateTimeImmutable
	{
		return $this->createdAt;
	}

	public function getUpdatedAt(): DateTimeImmutable|null
	{
		return $this->updatedAt;
	}

	/**
	 * @param array<string, mixed> $row
	 */
	public static function fromDbRow(array $row): static
	{
		return new static(
			(int) $row['id'],
			$row['first_name'],
			$row['last_name'],
			$row['email'],
			$row['phone'] ?? null,
			new DateTimeImmutable($row['created_at']),
			$row['updated_at'] !== null ? new DateTimeImmutable($row['updated_at']) : null,
		);
	}

	/**
	 * @return array<string, mixed>
	 */
	public function toDbArray(): array
	{
		return [
			'id' => $this->getId(),
			'first_name' => $this->getFirstName(),
			'last_name' => $this->getLastName(),
			'email' => $this->getEmail(),
			'phone' => $this->getPhone(),
			'created_at' => $this->getCreatedAt()->format('Y-m-d H:i:s'),
			'updated_at' => $this->getUpdatedAt()?->format('Y-m-d H:i:s'),
		];
	}

}
