<?php declare(strict_types = 1);

namespace Model\Tariff\Factory;

use Common\Clock\DateTimeProvider;
use Enum\TariffCode;
use Model\Tariff\DTO\TariffDTO;
use Model\Tariff\DTO\TariffInput;
use Model\Tariff\DTO\TariffMapper;
use Model\Tariff\Entity\Tariff;
use Model\Tariff\Repository\ITariffRepository;
use RuntimeException;
use function round;

final class TariffFactory implements ITariffFactory
{

	public function __construct(private ITariffRepository $tariffRepository, private DateTimeProvider $dateTimeProvider)
	{
	}

	public function createDTOFromEntity($tariff): TariffDTO
	{
		return TariffMapper::toDTO($tariff);
	}

	public function createEntityFromDTO($dto): Tariff
	{
		return TariffMapper::fromDTO($dto);
	}

	   /**
		* Vrátí entitu Tariff podle kódu (např. pro kalkulaci, NE pro vytváření nových tarifů)
		*
		* @throws RuntimeException Pokud tarif s daným kódem neexistuje
		*/
	public function getByCode(TariffCode $code): Tariff
	{
			$tariff = $this->tariffRepository->findByCode($code);
		if ($tariff === null) {
			   throw new RuntimeException("Tariff with code '{$code->value}' not found.");
		}

			return $tariff;
	}

	   /**
		* Aktualizuje existující Tariff podle DTO (použít pro update)
		*/
	public function update(TariffInput $input, Tariff $original): Tariff
	{
			$priceNoVat = $input->priceNoVat;
			$vatPercent = $input->vatPercent;
		$priceWithVat = round($priceNoVat * (1 + $vatPercent->value / 100), 2);
			$now = $this->dateTimeProvider->now();

			return new Tariff(
				$original->getId(),
				$original->getTariffCode(),
				$original->getName(),
				$input->description,
				$priceNoVat,
				$vatPercent,
				$priceWithVat,
				$original->getCurrencyCode(),
				$input->isActive,
				$original->getCreatedAt(),
				$now,
			);
	}

}
