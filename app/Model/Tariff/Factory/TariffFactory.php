<?php declare(strict_types = 1);

namespace Model\Tariff\Factory;

use Enum\TariffCode;
use Model\Tariff\Entity\Tariff;
use Model\Tariff\Repository\ITariffRepository;
use RuntimeException;
use ValueError;

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
	public function createFromCode(string|TariffCode $code): Tariff
	{
		try {
			$tariffCode = $code instanceof TariffCode ? $code : TariffCode::from($code);
		} catch (ValueError $e) {
			throw new RuntimeException("Tariff with code '" . (string) $code . "' not found.", 0, $e);
		}

		$tariff = $this->tariffRepository->findByCode($tariffCode);
		if ($tariff === null) {
			throw new RuntimeException("Tariff with code '{$tariffCode->value}' not found.");
		}

		return $tariff;
	}

}
