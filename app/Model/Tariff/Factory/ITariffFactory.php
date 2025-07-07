<?php declare(strict_types = 1);

namespace Model\Tariff\Factory;

interface ITariffFactory
{

	/**
	 * @return array<array<string, mixed>>
	 */
	public function createTariffListResponse(): array;

	/**
	 * @return array<string, mixed>
	 */
	public function createTariffDetailResponse(int $id): array;

	public function exists(int $id): bool;

}
