<?php declare(strict_types = 1);

namespace Model\Tariff\Entity;

use Enum\CurrencyCode;
use Enum\TariffCode;
use Enum\VatPercent;
use Model\ArrayableEntityInterface;

final class Tariff implements ArrayableEntityInterface
{

	public function __construct(
		private readonly int $id,
		private readonly TariffCode $tariffCode,
		private readonly string $name,
		private readonly string $description,
		private readonly float $priceNoVat,
		private readonly VatPercent $vatPercent,
		private readonly float $priceWithVat,
		private readonly CurrencyCode $currencyCode,
	)
	{
	}

	public function getId(): int
	{
		return $this->id;
	}

	public function getTariffCode(): TariffCode
	{
		return $this->tariffCode;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getDescription(): string
	{
		return $this->description;
	}

	public function getPriceNoVat(): float
	{
		return $this->priceNoVat;
	}

	public function getVatPercent(): VatPercent
	{
		return $this->vatPercent;
	}

	public function getPriceWithVat(): float
	{
		return $this->priceWithVat;
	}

	public function getCurrencyCode(): CurrencyCode
	{
		return $this->currencyCode;
	}

	/**
	 * @param array<string, mixed> $row
	 */
	public static function fromDbRow(array $row): static
	{
		return new self(
			$row['id'],
			TariffCode::from($row['code']),
			$row['name'],
			$row['description'],
			(float) $row['price_no_vat'],
			VatPercent::from($row['vat_percent']),
			(float) $row['price_with_vat'],
			CurrencyCode::from($row['currency']),
		);
	}

	/**
	 * @return array<string, mixed>
	 */
	public function toDbArray(): array
	{
		return [
			'id' => $this->getId(),
			'code' => $this->getTariffCode()->value,
			'name' => $this->getName(),
			'description' => $this->getDescription(),
			'price_no_vat' => $this->getPriceNoVat(),
			'vat_percent' => $this->getVatPercent()->value,
			'price_with_vat' => $this->getPriceWithVat(),
			'currency' => $this->getCurrencyCode()->value,
		];
	}

}
