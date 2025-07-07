<?php declare(strict_types = 1);

namespace Model\Tariff\DTO;

use Model\Tariff\Entity\Tariff;

final class TariffMapper
{

	public static function toDTO(Tariff $tariff): TariffDTO
	{
		return new TariffDTO(
			$tariff->getId(),
			$tariff->getTariffCode(),
			$tariff->getName(),
			$tariff->getDescription(),
			$tariff->getPriceNoVat(),
			$tariff->getVatPercent(),
			$tariff->getPriceWithVat(),
			$tariff->getCurrencyCode(),
		);
	}

	public static function fromDTO(TariffDTO $dto): Tariff
	{
		return new Tariff(
			$dto->id,
			$dto->tariffCode,
			$dto->name,
			$dto->description,
			$dto->priceNoVat,
			$dto->vatPercent,
			$dto->priceWithVat,
			$dto->currencyCode,
		);
	}

}
