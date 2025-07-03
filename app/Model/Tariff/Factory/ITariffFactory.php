<?php declare(strict_types = 1);

namespace Model\Tariff\Factory;

use Enum\TariffCode;
use Model\Tariff\Entity\Tariff;
use RuntimeException;

interface ITariffFactory
{

	/**
	 * @throws RuntimeException
	 */
	public function createFromCode(string|TariffCode $code): Tariff;

}
