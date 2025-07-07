<?php declare(strict_types = 1);

namespace Model\Tariff\Factory;

use Model\Tariff\DTO\TariffMapper;
use Model\Tariff\Repository\ITariffRepository;
use function array_map;

final class TariffFactory implements ITariffFactory
{

	public function __construct(private ITariffRepository $tariffRepository)
	{
	}

	/**
	 * @return array<array<string, mixed>>
	 */
	public function createTariffListResponse(): array
	{
		$tariffs = $this->tariffRepository->findAll();

		return array_map(static fn ($tariff) => TariffMapper::toDTO($tariff)->toArray(), $tariffs);
	}

	/**
	 * @return array<string, mixed>
	 */
	public function createTariffDetailResponse(int $id): array
	{
		$tariff = $this->tariffRepository->get($id);

		return TariffMapper::toDTO($tariff)->toArray();
	}

	public function exists(int $id): bool
	{
		return $this->tariffRepository->exists($id);
	}

}
