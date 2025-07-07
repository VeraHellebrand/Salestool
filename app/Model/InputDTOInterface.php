<?php declare(strict_types = 1);

namespace Model;

interface InputDTOInterface
{

	/**
	 * @param array<string, mixed> $data
	 * @return static
	 */
	public static function fromArray(array $data): static;

}
