<?php declare(strict_types = 1);

namespace Model\Tariff\Repository;

use Model\Tariff\Entity\Tariff;

interface ITariffUpdateCapableRepository extends ITariffRepository
{

	/**
	 * Persistuje změny tarifu (update podle ID)
	 */
	public function update(Tariff $tariff): void;

}
