<?php declare(strict_types = 1);

namespace Model\Calculation\DTO;

use Model\Calculation\Entity\Calculation;

final class CalculationMapper
{

	public static function toDTO(Calculation $entity): CalculationDTO
	{
		return new CalculationDTO(
			$entity->getId(),
			$entity->getCustomerId(),
			$entity->getTariffId(),
			$entity->getPriceNoVat(),
			$entity->getVatPercent(),
			$entity->getPriceWithVat(),
			$entity->getCurrency(),
			$entity->getStatus(),
			$entity->getCreatedAt(),
			$entity->getUpdatedAt(),
		);
	}

	public static function fromDTO(CalculationDTO $dto): Calculation
	{
		return new Calculation(
			$dto->id,
			$dto->customerId,
			$dto->tariffId,
			$dto->priceNoVat,
			$dto->vatPercent,
			$dto->priceWithVat,
			$dto->currency,
			$dto->status,
			$dto->createdAt,
			$dto->updatedAt,
		);
	}

}
