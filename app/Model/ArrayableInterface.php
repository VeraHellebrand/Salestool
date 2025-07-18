<?php declare(strict_types = 1);

namespace Model;

interface ArrayableInterface
{

	/**
	 * @return array<string, mixed>
	 */
	public function toArray(): array;

}
