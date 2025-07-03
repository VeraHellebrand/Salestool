<?php declare(strict_types = 1);

namespace Model\Tariff\Factory;

use Enum\TariffCode;
use Model\Tariff\Entity\Tariff;
use Model\Tariff\Repository\ITariffRepository;
use RuntimeException;

final class TariffFactory implements ITariffFactory
{

	private ITariffRepository $tariffRepository;

	public function __construct(ITariffRepository $tariffRepository)
	{
		$this->tariffRepository = $tariffRepository;
	}

	/**
	 * Vytvoří Tariff podle kódu (např. při vytváření kalkulace)
	 *
	 * @throws RuntimeException pokud tarif neexistuje
	 */
	public function createFromCode(TariffCode $code): Tariff
	{
			$tariff = $this->tariffRepository->findByCode($code);
		if ($tariff === null) {
			   throw new RuntimeException("Tariff with code '{$code->value}' not found.");
		}

			return $tariff;
	}

}
