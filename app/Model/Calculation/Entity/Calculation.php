<?php declare(strict_types = 1);

namespace Model\Calculation\Entity;

use DateTimeImmutable;
use Enum\CalculationStatus;
use Enum\CurrencyCode;

final class Calculation
{

	public function __construct(
		private int $id,
		private int $customerId,
		private int $tariffId,
		private float $priceNoVat,
		private int $vatPercent,
		private float $priceWithVat,
		private CurrencyCode $currency,
		private CalculationStatus $status,
		private DateTimeImmutable $createdAt,
		private DateTimeImmutable|null $updatedAt = null,
	)
	{
	}

	public function getId(): int
	{
		return $this->id;
	}

	public function getCustomerId(): int
	{
		return $this->customerId;
	}

	public function getTariffId(): int
	{
		return $this->tariffId;
	}

	public function getPriceNoVat(): float
	{
		return $this->priceNoVat;
	}

	public function getVatPercent(): int
	{
		return $this->vatPercent;
	}

	public function getPriceWithVat(): float
	{
		return $this->priceWithVat;
	}

	public function getCurrency(): CurrencyCode
	{
		return $this->currency;
	}

	public function getStatus(): CalculationStatus
	{
		return $this->status;
	}

	public function getCreatedAt(): DateTimeImmutable
	{
		return $this->createdAt;
	}

	public function getUpdatedAt(): DateTimeImmutable|null
	{
		return $this->updatedAt;
	}

}
