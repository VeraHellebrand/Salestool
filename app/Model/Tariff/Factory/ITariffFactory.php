<?php declare(strict_types = 1);

namespace Model\Tariff\Factory;

use Enum\TariffCode;
use Model\EntityFactoryInterface;
use Model\Tariff\DTO\TariffDTO;
use Model\Tariff\DTO\TariffInput;
use Model\Tariff\Entity\Tariff;
use RuntimeException;

/**
 * @extends EntityFactoryInterface<Tariff, TariffDTO>
 */
interface ITariffFactory extends EntityFactoryInterface
{

	/**
	 * Vrátí entitu Tariff podle kódu (např. pro kalkulaci, NE pro vytváření nových tarifů)
	 *
	 * @throws RuntimeException Pokud tarif s daným kódem neexistuje
	 */
	public function getByCode(TariffCode $code): Tariff;

	/**
	 * Aktualizuje existující Tariff podle DTO (použít pro update)
	 */
	public function update(TariffInput $input, Tariff $original): Tariff;

}
