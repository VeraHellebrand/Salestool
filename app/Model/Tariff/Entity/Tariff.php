<?php declare(strict_types = 1);

namespace Model\Tariff\Entity;

use Enum\CurrencyCode;
use Enum\TariffCode;

final class Tariff
{

	private readonly int $id;

	private readonly TariffCode $tariffCode;

	private readonly string $name;

	private readonly string $description;

	private readonly float $priceNoVat;

	private readonly int $vatPercent;

	private readonly float $priceWithVat;

	private readonly CurrencyCode $currencyCode;

	private readonly bool $isActive;

	public function __construct(
		int $id,
		TariffCode $tariffCode,
		string $name,
		string $description,
		float $priceNoVat,
		int $vatPercent,
		float $priceWithVat,
		CurrencyCode $currencyCode,
		bool $isActive,
	)
	{
		$this->id = $id;
		$this->tariffCode = $tariffCode;
		$this->name = $name;
		$this->description = $description;
		$this->priceNoVat = $priceNoVat;
		$this->vatPercent = $vatPercent;
		$this->priceWithVat = $priceWithVat;
		$this->currencyCode = $currencyCode;
		$this->isActive = $isActive;
	}

	/**
	 * @param array<string, mixed> $row
	 */
	public static function fromDbRow(array $row): self
	{
		return new self(
			$row['id'],
			TariffCode::from($row['code']),
			$row['name'],
			$row['description'],
			(float) $row['price_no_vat'],
			(int) $row['vat_percent'],
			(float) $row['price_with_vat'],
			CurrencyCode::from($row['currency']),
			(bool) $row['is_active'],
		);
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

	public function getVatPercent(): int
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

	public function isActive(): bool
	{
		return $this->isActive;
	}

	/**
	 * @return array<string, mixed>
	 */
	public function toArray(): array
	{
		return [
			'id' => $this->getId(),
			'code' => $this->getTariffCode()->value,
			'name' => $this->getName(),
			'description' => $this->getDescription(),
			'price_no_vat' => $this->getPriceNoVat(),
			'vat_percent' => $this->getVatPercent(),
			'price_with_vat' => $this->getPriceWithVat(),
			'currency' => $this->getCurrencyCode()->value,
			'is_active' => $this->isActive(),
		];
	}

}
