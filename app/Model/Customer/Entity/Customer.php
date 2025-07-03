<?php declare(strict_types = 1);

namespace Model\Customer\Entity;

use Model\ArrayableEntityInterface;
use function date;

final class Customer implements ArrayableEntityInterface
{

	public function __construct(
		private int $id,
		private string $firstName,
		private string $lastName,
		private string $email,
		private string|null $phone,
		private string|null $createdAt = null,
		private string|null $updatedAt = null,
	)
	{
		if ($this->createdAt === null) {
			$this->createdAt = date('Y-m-d H:i:s');
		}
	}

	public function getId(): int
	{
		return $this->id;
	}

	public function getFirstName(): string
	{
		return $this->firstName;
	}

	public function setFirstName(string $firstName): void
	{
		$this->firstName = $firstName;
	}

	public function getLastName(): string
	{
		return $this->lastName;
	}

	public function setLastName(string $lastName): void
	{
		$this->lastName = $lastName;
	}

	public function getEmail(): string
	{
		return $this->email;
	}

	public function setEmail(string $email): void
	{
		$this->email = $email;
	}

	public function getPhone(): string|null
	{
		return $this->phone;
	}

	public function setPhone(string|null $phone): void
	{
		$this->phone = $phone;
	}

	public function getCreatedAt(): string
	{
		return $this->createdAt;
	}

	public function setCreatedAt(string $createdAt): void
	{
		$this->createdAt = $createdAt;
	}

	public function getUpdatedAt(): string|null
	{
		return $this->updatedAt;
	}

	public function setUpdatedAt(string|null $updatedAt): void
	{
		$this->updatedAt = $updatedAt;
	}

	public static function fromDbRow(array $row): static
	{
		return new static(
			(int) $row['id'],
			$row['first_name'],
			$row['last_name'],
			$row['email'],
			$row['phone'] ?? null,
			$row['created_at'],
			$row['updated_at'] ?? null,
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
			'created_at' => $this->createdAt,
			'updated_at' => $this->updatedAt,
		];
	}

}
