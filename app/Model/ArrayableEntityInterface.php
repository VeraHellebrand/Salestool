<?php declare(strict_types = 1);

namespace Model;

interface ArrayableEntityInterface
{

	/**
	 * @return array<string, mixed>
	 */
	public function toArray(): array;

	/**
	 * @param array<string, mixed> $row
	 * @return static
	 */
	public static function fromDbRow(array $row): static;

}
