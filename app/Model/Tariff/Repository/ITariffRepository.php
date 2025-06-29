<?php declare(strict_types = 1);

namespace Model\Tariff\Repository;

use Enum\TariffCode;
use Model\Tariff\Entity\Tariff;

interface ITariffRepository
{

	public function get(int $id): Tariff;

	/**
	 * @return array<Tariff>
	 */
	public function findAll(): array;

	public function findByCode(TariffCode $code): Tariff|null;

}
