<?php declare(strict_types = 1);

namespace Model\Calculation\Entity;

use DateTimeImmutable;
use Enum\CalculationStatus;
use Enum\CurrencyCode;
use Model\ArrayableEntityInterface;

final class Calculation implements ArrayableEntityInterface
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

	/**
	 * Vytvoří instanci z databázového řádku
	 *
	 * @param array<string, mixed> $row
	 */
	public static function fromDbRow(array $row): static
	{
		return new static(
			(int) $row['id'],
			(int) $row['customer_id'],
			(int) $row['tariff_id'],
			(float) $row['price_no_vat'],
			(int) $row['vat_percent'],
			(float) $row['price_with_vat'],
			CurrencyCode::from($row['currency']),
			CalculationStatus::from($row['status']),
			new DateTimeImmutable($row['created_at']),
			isset($row['updated_at']) && $row['updated_at'] ? new DateTimeImmutable($row['updated_at']) : null,
		);
	}

	/**
	 * Vytvoří instanci z obecného pole (např. z API)
	 *
	 * @param array<string, mixed> $data
	 */
	public static function fromArray(array $data): static
	{
		return new static(
			(int) $data['id'],
			(int) $data['customer_id'],
			(int) $data['tariff_id'],
			(float) $data['price_no_vat'],
			(int) $data['vat_percent'],
			(float) $data['price_with_vat'],
			CurrencyCode::from($data['currency']),
			CalculationStatus::from($data['status']),
			new DateTimeImmutable($data['created_at']),
			isset($data['updated_at']) && $data['updated_at'] ? new DateTimeImmutable($data['updated_at']) : null,
		);
	}

	/**
	 * Vrací pole s daty entity (pro export, testy, atd.)
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(): array
	{
		return [
			'id' => $this->getId(),
			'customer_id' => $this->getCustomerId(),
			'tariff_id' => $this->getTariffId(),
			'price_no_vat' => $this->getPriceNoVat(),
			'vat_percent' => $this->getVatPercent(),
			'price_with_vat' => $this->getPriceWithVat(),
			'currency' => $this->getCurrency()->value,
			'status' => $this->getStatus()->value,
			'created_at' => $this->getCreatedAt()->format('Y-m-d H:i:s'),
			'updated_at' => $this->getUpdatedAt()?->format('Y-m-d H:i:s'),
		];
	}

}
